<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\PesananAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    // Menampilkan Formulir Pemesanan Paket
    public function create($slug)
    {
        // Proteksi: Admin tidak boleh membuka form pemesanan
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('error', 'Akun Administrator tidak dapat melakukan pemesanan paket.');
        }

        $paket = Paket::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $addons = Addon::all();

        return view('pesanan.create', compact('paket', 'addons'));
    }

    // Memproses Simpan Pesanan Baru
    public function store(Request $request)
    {
        // Proteksi: Admin tidak boleh submit pesanan
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('error', 'Akun Administrator tidak dapat melakukan pemesanan paket.');
        }

        $request->validate([
            'paket_id' => 'required|exists:pakets,id',
            'tanggal_event' => 'required|date|after:today',
            'lokasi' => 'required|string|max:255',
            'jumlah_tamu' => 'required|integer|min:10',
            'catatan' => 'nullable|string',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id',
        ]);

        $paket = Paket::findOrFail($request->paket_id);
        $totalHarga = $paket->harga;

        $selectedAddons = [];
        if ($request->has('addons')) {
            $addons = Addon::whereIn('id', $request->addons)->get();
            foreach ($addons as $addon) {
                $totalHarga += $addon->harga;
                $selectedAddons[] = $addon;
            }
        }

        $kodePesanan = 'EO-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'paket_id' => $paket->id,
            'kode_pesanan' => $kodePesanan,
            'tanggal_event' => $request->tanggal_event,
            'lokasi' => $request->lokasi,
            'jumlah_tamu' => $request->jumlah_tamu,
            'catatan' => $request->catatan,
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);

        foreach ($selectedAddons as $addon) {
            PesananAddon::create([
                'pesanan_id' => $pesanan->id,
                'addon_id' => $addon->id,
                'qty' => 1,
                'subtotal' => $addon->harga,
            ]);
        }

        return redirect('/pesanan/' . $pesanan->kode_pesanan)->with('success', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran uang muka (DP).');
    }

    // Menampilkan Rincian Pesanan & Petunjuk Pembayaran
    public function show($kode_pesanan)
    {
        $pesanan = Pesanan::with(['paket', 'pesananAddons.addon', 'pembayarans', 'user'])
            ->where('kode_pesanan', $kode_pesanan)
            ->firstOrFail();

        if (Auth::id() !== $pesanan->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('pesanan.show', compact('pesanan'));
    }

    // Menampilkan Form Edit Pesanan
    public function edit($kode_pesanan)
    {
        $pesanan = Pesanan::with(['paket', 'pesananAddons'])->where('kode_pesanan', $kode_pesanan)->firstOrFail();

        if (Auth::id() !== $pesanan->user_id) {
            abort(403);
        }

        if (in_array($pesanan->status, ['preparation', 'done'])) {
            return back()->with('error', 'Pesanan yang sedang dipersiapkan atau sudah selesai tidak dapat diubah.');
        }

        $addons = Addon::all();
        $selectedAddonIds = $pesanan->pesananAddons->pluck('addon_id')->toArray();

        return view('pesanan.edit', compact('pesanan', 'addons', 'selectedAddonIds'));
    }

    // Memproses Simpan Perubahan Pesanan
    public function update(Request $request, $kode_pesanan)
    {
        $pesanan = Pesanan::with('paket')->where('kode_pesanan', $kode_pesanan)->firstOrFail();

        if (Auth::id() !== $pesanan->user_id) {
            abort(403);
        }

        $request->validate([
            'tanggal_event' => 'required|date|after:today',
            'lokasi' => 'required|string|max:255',
            'jumlah_tamu' => 'required|integer|min:10',
            'catatan' => 'nullable|string',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id',
        ]);

        $totalHarga = $pesanan->paket->harga;

        $selectedAddons = [];
        if ($request->has('addons')) {
            $addons = Addon::whereIn('id', $request->addons)->get();
            foreach ($addons as $addon) {
                $totalHarga += $addon->harga;
                $selectedAddons[] = $addon;
            }
        }

        $pesanan->update([
            'tanggal_event' => $request->tanggal_event,
            'lokasi' => $request->lokasi,
            'jumlah_tamu' => $request->jumlah_tamu,
            'catatan' => $request->catatan,
            'total_harga' => $totalHarga,
        ]);

        PesananAddon::where('pesanan_id', $pesanan->id)->delete();
        foreach ($selectedAddons as $addon) {
            PesananAddon::create([
                'pesanan_id' => $pesanan->id,
                'addon_id' => $addon->id,
                'qty' => 1,
                'subtotal' => $addon->harga,
            ]);
        }

        return redirect('/dashboard')->with('success', 'Pesanan #' . $pesanan->kode_pesanan . ' berhasil diperbarui!');
    }

    // Menghapus Pesanan
    public function destroy($kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->firstOrFail();

        if (Auth::id() !== $pesanan->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $kode = $pesanan->kode_pesanan;
        $pesanan->delete();

        return redirect('/dashboard')->with('success', 'Pesanan #' . $kode . ' berhasil dihapus.');
    }
}