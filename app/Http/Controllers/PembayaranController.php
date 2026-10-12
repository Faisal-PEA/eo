<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    // Memproses Upload Bukti Transfer dari Pelanggan
    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'metode' => 'required|string',
            'jenis' => 'required|in:dp,pelunasan',
            'jumlah' => 'required|numeric|min:10000',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:3072', // Max 3MB
        ]);

        $pesanan = Pesanan::findOrFail($request->pesanan_id);

        // Keamanan: pastikan hanya pemilik pesanan yang bisa bayar
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Upload file gambar ke folder public/uploads/bukti/
        $file = $request->file('bukti_transfer');
        $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti'), $filename);

        // Simpan riwayat pembayaran ke database
        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode' => $request->metode,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'bukti_transfer' => $filename,
            'status' => 'pending', // Menunggu diverifikasi admin
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Mohon menunggu verifikasi dari admin.');
    }
}