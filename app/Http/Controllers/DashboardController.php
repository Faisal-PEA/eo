<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil riwayat pesanan milik customer yang sedang login
        $pesanans = Pesanan::with(['paket', 'pesananAddons.addon', 'pembayarans'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard.index', compact('pesanans'));
    }
}