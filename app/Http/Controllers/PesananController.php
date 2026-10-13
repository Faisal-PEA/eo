<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Paket;
use App\Models\Addon;
use App\Models\Pesanan;

class PesananController extends Controller
{
    /**
     * Form pembuatan pesanan baru
     */
    public function create(Request $request)
    {
        $paket_id = $request->query('paket_id');
        $paket = Paket::findOrFail($paket_id);
        $addons = Addon::all();

        return view('pesanan.create', compact('paket', 'addons'));
    }

    /**
     * Simpan pesanan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'paket_id'      => 'required|exists:pakets,id',
            'tanggal_event' => 'required|date|after:today',
            'lokasi'        => 'required|string|max:255',
            'jumlah_tamu'   => 'required|integer|min:1',
            'catatan'       => 'nullable|string|max:1000',
            'addons'        => 'nullable|array',
            'addons.*'      => 'exists:addons,id',
        ], [
            'tanggal_event.after' => 'Tanggal acara harus setelah hari ini.',
            'tanggal_event.required' => 'Tanggal acara wajib diisi.',
            'lokasi.required' => 'Lokasi acara wajib diisi.',
            'jumlah_tamu.required' => 'Jumlah tamu wajib diisi.',
        ]);

        $paket = Paket::findOrFail($request->paket_id);

        // Hitung total harga: harga paket + subtotal semua addon
        $totalHarga = (float) $paket->harga;
        $addonData = [];

        if ($request->addons) {
            foreach ($request->addons as $addonId) {
                $addon = Addon::find($addonId);
                if ($addon) {
                    $qty = 1;
                    $subtotal = (float) $addon->harga * $qty;
                    $totalHarga += $subtotal;

                    // Simpan ke pivot dengan qty dan subtotal
                    $addonData[$addonId] = [
                        'qty'      => $qty,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        // Buat kode pesanan unik
        $kodePesanan = 'EVO-' . strtoupper(Str::random(6)) . '-' . date('dmY');

        // Simpan pesanan
        $pesanan = Pesanan::create([
            'user_id'       => Auth::id(),
            'paket_id'      => $paket->id,
            'kode_pesanan'  => $kodePesanan,
            'tanggal_event' => $request->tanggal_event,
            'lokasi'        => $request->lokasi,
            'jumlah_tamu'   => $request->jumlah_tamu,
            'catatan'       => $request->catatan,
            'total_harga'   => $totalHarga,
            'status'        => 'pending',
        ]);

        // Simpan addon ke tabel pivot
        if (!empty($addonData)) {
            $pesanan->addons()->sync($addonData);
        }

        return redirect()->route('pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan dengan pembayaran.');
    }

    /**
     * Halaman detail & invoice pesanan customer
     */
    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'paket', 'addons', 'pembayarans'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pesanan.show', compact('pesanan'));
    }

    /**
     * Batalkan pesanan (hanya yang masih pending)
     */
    public function destroy($id)
    {
        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $pesanan->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}