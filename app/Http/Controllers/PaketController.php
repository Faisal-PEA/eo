<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Addon;

class PaketController extends Controller
{
    /**
     * Tampilkan Semua Paket Acara (Halaman /paket)
     */
    public function index()
    {
        $pakets = Paket::all();
        return view('paket.index', compact('pakets'));
    }

    /**
     * Tampilkan Detail Paket Acara (Halaman /paket/{id})
     */
    public function show($id)
    {
        $paket = Paket::findOrFail($id);
        $addons = Addon::all();

        return view('paket.show', compact('paket', 'addons'));
    }
}