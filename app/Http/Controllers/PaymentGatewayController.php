<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use App\Models\Pesanan;
use App\Models\Pembayaran;

class PaymentGatewayController extends Controller
{
    /**
     * Generate Midtrans Snap Token
     */
    public function getSnapToken(Request $request, $pesanan_id)
    {
        try {
            $pesanan = Pesanan::with('user', 'paket')->where('id', $pesanan_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $tipe = $request->input('tipe', 'dp');

            // Ambil total biaya pesanan (mendukung kolom total_biaya maupun total_harga)
            $totalBiaya = (float) ($pesanan->total_biaya ?? $pesanan->total_harga ?? 0);

            // Hitung total yang sudah terverifikasi sah
            $sudahDibayar = (float) $pesanan->pembayarans->where('status', 'verified')->sum(function($pay) {
                return $pay->jumlah ?? $pay->jumlah_bayar ?? 0;
            });

            if ($tipe === 'dp' && $pesanan->status === 'pending') {
                $grossAmount = round($totalBiaya * 0.3);
                $orderId = $pesanan->kode_pesanan . '-DP-' . time();
            } else {
                $grossAmount = max(0, $totalBiaya - $sudahDibayar);
                $orderId = $pesanan->kode_pesanan . '-LUNAS-' . time();
            }

            if ($grossAmount <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan ini sudah lunas atau nominal tagihan tidak valid!'
                ], 400);
            }

            $serverKey = config('services.midtrans.server_key');
            if (empty($serverKey)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'MIDTRANS_SERVER_KEY belum diisi di file .env!'
                ], 400);
            }

            $isProduction = config('services.midtrans.is_production', false);
            $endpoint = $isProduction 
                ? 'https://app.midtrans.com/snap/v1/transactions' 
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $payload = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => $pesanan->user->name ?? 'Customer',
                    'email'      => $pesanan->user->email ?? 'customer@example.com',
                    'phone'      => $pesanan->user->no_hp ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id'       => (string) ($pesanan->paket->id ?? '1'),
                        'price'    => (int) $grossAmount,
                        'quantity' => 1,
                        'name'     => ($tipe === 'dp' ? 'DP: ' : 'Pelunasan: ') . substr($pesanan->paket->nama_paket ?? 'Paket Acara', 0, 30),
                    ]
                ],
            ];

            // Request API Snap langsung dengan bypass SSL Windows (tanpa error cert)
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
                ])
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $snapToken = $response->json()['token'] ?? null;
                $pesanan->update(['snap_token' => $snapToken]);

                return response()->json([
                    'status'     => 'success',
                    'snap_token' => $snapToken,
                ]);
            }

            $errorMsg = $response->json()['error_messages'][0] ?? 'Gagal menghubungi server Midtrans. Periksa Server Key Anda.';
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg,
            ], 400);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handler Frontend Localhost saat Sukses Bayar di Snap Popup
     */
    public function finishPayment(Request $request, $pesanan_id)
    {
        $pesanan = Pesanan::where('id', $pesanan_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $tipe = $request->input('tipe', 'dp');
        $nominal = (float) $request->input('gross_amount', 0);
        $metode = $request->input('payment_type', 'Midtrans Instant');

        $totalBiaya = (float) ($pesanan->total_biaya ?? $pesanan->total_harga ?? 0);
        if ($nominal <= 0) {
            $sudahDibayar = (float) $pesanan->pembayarans->where('status', 'verified')->sum(function($pay) {
                return $pay->jumlah ?? $pay->jumlah_bayar ?? 0;
            });
            $nominal = ($tipe === 'dp') ? round($totalBiaya * 0.3) : max(0, $totalBiaya - $sudahDibayar);
        }

        // Catat pembayaran yang berhasil diverifikasi
        $pembayaranData = [
            'pesanan_id'     => $pesanan->id,
            'bukti_transfer' => 'midtrans_auto',
            'status'         => 'verified',
        ];

        if (Schema::hasColumn('pembayarans', 'jumlah')) $pembayaranData['jumlah'] = $nominal;
        if (Schema::hasColumn('pembayarans', 'jumlah_bayar')) $pembayaranData['jumlah_bayar'] = $nominal;
        if (Schema::hasColumn('pembayarans', 'jenis')) $pembayaranData['jenis'] = $tipe;
        if (Schema::hasColumn('pembayarans', 'tipe')) $pembayaranData['tipe'] = $tipe;
        if (Schema::hasColumn('pembayarans', 'tipe_pembayaran')) $pembayaranData['tipe_pembayaran'] = $tipe;
        if (Schema::hasColumn('pembayarans', 'metode')) $pembayaranData['metode'] = 'Midtrans (' . strtoupper($metode) . ')';

        Pembayaran::create($pembayaranData);

        // Update status pesanan
        if ($tipe === 'dp') {
            $pesanan->update(['status' => 'dp_paid']);
        } else {
            $pesanan->update(['status' => 'confirmed']); // Lunas
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Webhook Notifikasi Server Midtrans
     */
    public function callback(Request $request)
    {
        try {
            $payload = $request->all();

            $transactionStatus = $payload['transaction_status'] ?? null;
            $orderId           = $payload['order_id'] ?? null;
            $grossAmount       = $payload['gross_amount'] ?? 0;
            $paymentType       = $payload['payment_type'] ?? 'midtrans';
            $fraudStatus       = $payload['fraud_status'] ?? null;

            if (!$orderId) {
                return response()->json(['message' => 'Order ID kosong'], 400);
            }

            $parts = explode('-', $orderId);
            $kodePesanan = $parts[0] . '-' . $parts[1];
            $tipeBayar   = strtolower($parts[2] ?? 'dp');

            $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
            }

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus != 'challenge') {
                    $pembayaranData = [
                        'pesanan_id'     => $pesanan->id,
                        'bukti_transfer' => 'midtrans_auto',
                        'status'         => 'verified',
                    ];

                    if (Schema::hasColumn('pembayarans', 'jumlah')) $pembayaranData['jumlah'] = $grossAmount;
                    if (Schema::hasColumn('pembayarans', 'jumlah_bayar')) $pembayaranData['jumlah_bayar'] = $grossAmount;
                    if (Schema::hasColumn('pembayarans', 'jenis')) $pembayaranData['jenis'] = $tipeBayar;
                    if (Schema::hasColumn('pembayarans', 'metode')) $pembayaranData['metode'] = 'Midtrans (' . strtoupper($paymentType) . ')';

                    Pembayaran::create($pembayaranData);

                    if ($tipeBayar === 'dp') {
                        $pesanan->update(['status' => 'dp_paid']);
                    } else {
                        $pesanan->update(['status' => 'confirmed']);
                    }
                }
            }

            return response()->json(['message' => 'Notifikasi Midtrans berhasil diproses']);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}