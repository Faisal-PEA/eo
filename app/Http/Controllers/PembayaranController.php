<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Pembayaran;
use App\Models\Pesanan;

class PembayaranController extends Controller
{
    /**
     * Proses Unggah Bukti Transfer Customer (Fix Kolom pembayarans.metode)
     */
    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id'      => 'required|exists:pesanans,id',
            'tipe_pembayaran' => 'required|in:dp,pelunasan',
            'jumlah_bayar'    => 'required|numeric|min:1000',
            'bukti_transfer'  => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'bukti_transfer.required' => 'Wajib memilih file foto struk/bukti transfer.',
            'bukti_transfer.image'    => 'File yang diunggah harus berupa gambar (JPG, PNG, atau WEBP).',
            'bukti_transfer.max'      => 'Ukuran foto maksimal adalah 3 MB.',
            'jumlah_bayar.min'        => 'Nominal pembayaran tidak valid.',
        ]);

        // Pastikan pesanan milik customer yang sedang login
        $pesanan = Pesanan::where('id', $request->pesanan_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Siapkan direktori penyimpanan bukti transfer
        $destPath = public_path('uploads/bukti');
        if (!File::isDirectory($destPath)) {
            File::makeDirectory($destPath, 0755, true, true);
        }

        // Upload file gambar dengan nama unik
        $file = $request->file('bukti_transfer');
        $fileName = 'bukti_' . time() . '_' . uniqid() . '.' . $file->extension();
        $file->move($destPath, $fileName);

        // Siapkan data pembayaran adaptif terhadap seluruh kolom di tabel pembayarans
        $pembayaranData = [
            'pesanan_id'     => $pesanan->id,
            'bukti_transfer' => $fileName,
            'status'         => 'pending',
        ];

        // 1. Nominal Pembayaran (jumlah / jumlah_bayar)
        if (Schema::hasColumn('pembayarans', 'jumlah')) {
            $pembayaranData['jumlah'] = $request->jumlah_bayar;
        }
        if (Schema::hasColumn('pembayarans', 'jumlah_bayar')) {
            $pembayaranData['jumlah_bayar'] = $request->jumlah_bayar;
        }

        // 2. Tipe/Jenis Pembayaran (jenis / tipe / tipe_pembayaran)
        if (Schema::hasColumn('pembayarans', 'jenis')) {
            $pembayaranData['jenis'] = $request->tipe_pembayaran;
        }
        if (Schema::hasColumn('pembayarans', 'tipe')) {
            $pembayaranData['tipe'] = $request->tipe_pembayaran;
        }
        if (Schema::hasColumn('pembayarans', 'tipe_pembayaran')) {
            $pembayaranData['tipe_pembayaran'] = $request->tipe_pembayaran;
        }

        // 3. Kolom Metode Pembayaran (metode / metode_pembayaran)
        if (Schema::hasColumn('pembayarans', 'metode')) {
            $pembayaranData['metode'] = 'Transfer Bank';
        }
        if (Schema::hasColumn('pembayarans', 'metode_pembayaran')) {
            $pembayaranData['metode_pembayaran'] = 'Transfer Bank';
        }

        // Simpan data pembayaran ke database
        Pembayaran::create($pembayaranData);

        // Sentuh pesanan agar updated_at terbarui untuk urutan paling atas di admin
        $pesanan->touch();

        return redirect()->route('pesanan.show', $pesanan->id)
            ->with('success', 'Bukti transfer berhasil diunggah! Tim Admin EventKu akan segera memverifikasi pembayaran Anda.');
    }
}