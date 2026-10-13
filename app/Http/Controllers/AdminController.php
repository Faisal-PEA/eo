<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Paket;
use App\Models\Galeri;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Admin
     */
    public function dashboard()
    {
        $totalPesanan    = Pesanan::count();
        $totalCustomer   = User::where('role', 'customer')->count();
        $totalPaket      = Paket::count();
        $pesananPending  = Pesanan::where('status', 'pending')->count();
        
        $totalPendapatan = 0;
        if (Schema::hasColumn('pembayarans', 'jumlah')) {
            $totalPendapatan = Pembayaran::where('status', 'verified')->sum('jumlah');
        } elseif (Schema::hasColumn('pembayarans', 'jumlah_bayar')) {
            $totalPendapatan = Pembayaran::where('status', 'verified')->sum('jumlah_bayar');
        }

        $pembayaranPending = Pembayaran::with(['pesanan.user'])
            ->where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $pesananTerbaru = Pesanan::with(['user', 'paket'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan',
            'totalCustomer',
            'totalPaket',
            'totalPendapatan',
            'pesananPending',
            'pembayaranPending',
            'pesananTerbaru'
        ));
    }

    /**
     * Tampilkan Semua Daftar Pesanan Customer (/admin/pesanan)
     */
    public function pesananIndex(Request $request)
    {
        $query = Pesanan::with(['user', 'paket', 'pembayarans', 'addons.addon'])->latest();

        if ($request->has('status') && $request->status !== 'all' && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->paginate(15);

        return view('admin.pesanan', compact('pesanans'));
    }

    /**
     * Detail Pesanan Customer
     */
    public function pesananDetail($id)
    {
        $pesanan = Pesanan::with(['user', 'paket', 'addons.addon', 'pembayarans'])
            ->findOrFail($id);

        return view('admin.pesanan_detail', compact('pesanan'));
    }

    /**
     * Update Status Pesanan Real-time
     */
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,dp_paid,confirmed,preparation,done,cancelled'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . $pesanan->kode_pesanan . ' berhasil diperbarui!');
    }

    /**
     * Verifikasi Bukti Pembayaran (DP -> "DP Paid", Pelunasan -> "Lunas / Confirmed")
     */
    public function verifikasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected'
        ]);

        $pembayaran = Pembayaran::with('pesanan')->findOrFail($id);
        $pembayaran->update(['status' => $request->status]);

        // LOGIKA STATUS OTOMATIS:
        if ($request->status === 'verified') {
            $tipe = strtolower($pembayaran->jenis ?? $pembayaran->tipe ?? $pembayaran->tipe_pembayaran ?? 'dp');

            if ($tipe === 'dp') {
                // Saat pembayaran DP diverifikasi: Status menjadi dp_paid
                $pembayaran->pesanan->update(['status' => 'dp_paid']);
            } elseif ($tipe === 'pelunasan') {
                // Saat pembayaran Pelunasan diverifikasi: Status menjadi confirmed (Lunas)
                $pembayaran->pesanan->update(['status' => 'confirmed']);
            }
        }

        $pesan = $request->status === 'verified' 
            ? 'Pembayaran berhasil diverifikasi! Status acara otomatis diperbarui.' 
            : 'Pembayaran telah ditolak.';

        return back()->with('success', $pesan);
    }

    /**
     * CRUD: Kelola Paket Acara
     */
    public function paketIndex()
    {
        $pakets = Paket::latest()->get();
        return view('admin.paket', compact('pakets'));
    }

    public function paketStore(Request $request)
    {
        $request->validate([
            'nama_paket'  => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'deskripsi'   => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $destPath = public_path('uploads/paket');
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true, true);
            }
            $gambarName = time() . '_' . uniqid() . '.' . $request->gambar->extension();
            $request->gambar->move($destPath, $gambarName);
        }

        Paket::create([
            'nama_paket' => $request->nama_paket,
            'kategori'   => $request->kategori,
            'harga'      => $request->harga,
            'deskripsi'  => $request->deskripsi,
            'gambar'     => $gambarName,
        ]);

        return back()->with('success', 'Paket acara baru berhasil ditambahkan!');
    }

    public function paketUpdate(Request $request, $id)
    {
        $paket = Paket::findOrFail($id);

        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori'   => 'required|string|max:100',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'nama_paket' => $request->nama_paket,
            'kategori'   => $request->kategori,
            'harga'      => $request->harga,
            'deskripsi'  => $request->deskripsi,
        ];

        if ($request->hasFile('gambar')) {
            $destPath = public_path('uploads/paket');
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true, true);
            }
            if ($paket->gambar && File::exists($destPath . '/' . $paket->gambar)) {
                File::delete($destPath . '/' . $paket->gambar);
            }
            $gambarName = time() . '_' . uniqid() . '.' . $request->gambar->extension();
            $request->gambar->move($destPath, $gambarName);
            $data['gambar'] = $gambarName;
        }

        $paket->update($data);

        return back()->with('success', 'Data paket acara berhasil diperbarui!');
    }

    public function paketDestroy($id)
    {
        $paket = Paket::findOrFail($id);
        if ($paket->gambar && File::exists(public_path('uploads/paket/' . $paket->gambar))) {
            File::delete(public_path('uploads/paket/' . $paket->gambar));
        }
        $paket->delete();

        return back()->with('success', 'Paket acara berhasil dihapus.');
    }

    /**
     * CRUD: Kelola Galeri Dokumentasi Acara
     */
    public function galeriIndex()
    {
        $galeris = Galeri::latest()->get();
        return view('admin.galeri', compact('galeris'));
    }

    public function galeriStore(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'gambar'   => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $destPath = public_path('uploads/galeri');
        if (!File::isDirectory($destPath)) {
            File::makeDirectory($destPath, 0755, true, true);
        }

        $gambarName = time() . '_' . uniqid() . '.' . $request->gambar->extension();
        $request->gambar->move($destPath, $gambarName);

        Galeri::create([
            'judul'    => $request->judul,
            'kategori' => $request->kategori,
            'gambar'   => $gambarName,
        ]);

        return back()->with('success', 'Foto dokumentasi galeri berhasil ditambahkan!');
    }

    public function galeriDestroy($id)
    {
        $galeri = Galeri::findOrFail($id);
        if ($galeri->gambar && File::exists(public_path('uploads/galeri/' . $galeri->gambar))) {
            File::delete(public_path('uploads/galeri/' . $galeri->gambar));
        }
        $galeri->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}