<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 paket aktif teratas untuk ditampilkan di landing page
        $pakets = Paket::where('is_active', true)->take(4)->get();

        return view('home', compact('pakets'));
    }
}