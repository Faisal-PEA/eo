<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Dashboard Utama Admin
    public function dashboard()
    {
        $totalPesanan = Pesanan::count();
        $totalPendapatan = Pembayaran::where('status', 'verified')->sum('jumlah');
        $pesananPending = Pesanan::where('status', 'pending')->count();
        $totalPaket = Paket::count();

        // 5 Pesanan terbaru yang masuk
        $pesananTerbaru = Pesanan::with(['user', 'paket'])->latest()->take(5)->get();

        // Pembayaran yang masih butuh verifikasi admin
        $pembayaranPending = Pembayaran::with(['pesanan.user', 'pesanan.paket'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan', 
            'totalPendapatan', 
            'pesananPending', 
            'totalPaket', 
            'pesananTerbaru', 
            'pembayaranPending'
        ));
    }

    // Halaman Manajemen Semua Pesanan
    public function pesanan()
    {
        $pesanans = Pesanan::with(['user', 'paket', 'pembayarans'])->latest()->get();
        return view('admin.pesanan', compact('pesanans'));
    }

    // Detail Pesanan Khusus di Panel SB Admin
    public function detailPesanan($kode_pesanan)
    {
        $pesanan = Pesanan::with(['user', 'paket', 'pesananAddons.addon', 'pembayarans'])
            ->where('kode_pesanan', $kode_pesanan)
            ->firstOrFail();

        return view('admin.pesanan_detail', compact('pesanan'));
    }

    // Update Status Acara oleh Admin
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,dp_paid,confirmed,preparation,done,cancelled'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . $pesanan->kode_pesanan . ' berhasil diubah menjadi: ' . strtoupper($request->status));
    }

    // Verifikasi Bukti Pembayaran (Terima atau Tolak)
    public function verifikasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan_admin' => 'nullable|string'
        ]);

        $pembayaran = Pembayaran::with('pesanan')->findOrFail($id);
        $pembayaran->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        if ($request->status === 'verified') {
            if ($pembayaran->jenis === 'dp') {
                $pembayaran->pesanan->update(['status' => 'dp_paid']);
            } elseif ($pembayaran->jenis === 'pelunasan') {
                $pembayaran->pesanan->update(['status' => 'confirmed']);
            }
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    // Halaman Daftar Semua Paket untuk Admin
    public function paket()
    {
        $pakets = Paket::latest()->get();
        return view('admin.paket', compact('pakets'));
    }

    // Simpan Paket Baru dengan Upload File Foto
    public function storePaket(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric|min:100000',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $tujuanFolder = public_path('uploads/paket');
        if (!file_exists($tujuanFolder)) {
            mkdir($tujuanFolder, 0777, true);
        }

        $file = $request->file('foto');
        $filename = 'paket_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($tujuanFolder, $filename);

        Paket::create([
            'nama_paket' => $request->nama_paket,
            'slug' => Str::slug($request->nama_paket) . '-' . time(),
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'gambar' => $filename,
            'is_active' => true,
        ]);

        return back()->with('success', 'Paket event baru dan fotonya berhasil ditambahkan!');
    }

    // Update Paket
    public function updatePaket(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric|min:100000',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $paket = Paket::findOrFail($id);
        $gambar = $paket->gambar;

        if ($request->hasFile('foto')) {
            $tujuanFolder = public_path('uploads/paket');
            if (!file_exists($tujuanFolder)) {
                mkdir($tujuanFolder, 0777, true);
            }

            $file = $request->file('foto');
            $filename = 'paket_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($tujuanFolder, $filename);
            $gambar = $filename;
        }

        $paket->update([
            'nama_paket' => $request->nama_paket,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
        ]);

        return back()->with('success', 'Paket event berhasil diperbarui!');
    }

    // Hapus Paket
    public function destroyPaket($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();

        return back()->with('success', 'Paket event berhasil dihapus!');
    }

    // Halaman Galeri Admin
    public function galeri()
    {
        $galeris = Galeri::latest()->get();
        return view('admin.galeri', compact('galeris'));
    }

        // Upload Foto Galeri Baru
    public function storeGaleri(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $file = $request->file('foto');
        $filename = 'galeri_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Simpan ke public/uploads/galeri
        $file->move(public_path('uploads/galeri'), $filename);

        Galeri::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'gambar' => $filename,
        ]);

        return back()->with('success', 'Foto portofolio berhasil ditambahkan ke galeri!');
    }

    // Hapus Foto Galeri
    public function destroyGaleri($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus!');
    }
}