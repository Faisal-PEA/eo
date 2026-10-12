<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    // Menampilkan semua daftar paket acara (dengan filter kategori)
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $query = Paket::where('is_active', true);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $pakets = $query->get();
        $kategoriList = ['Semua', 'Pernikahan', 'Ulang Tahun', 'Seminar'];

        return view('paket.index', compact('pakets', 'kategoriList', 'kategori'));
    }

    // Menampilkan halaman rincian 1 paket beserta daftar add-on yang tersedia
    public function show($slug)
    {
        $paket = Paket::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $addons = Addon::all();

        return view('paket.show', compact('paket', 'addons'));
    }
}