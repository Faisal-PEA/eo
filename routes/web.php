<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesananController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
Route::get('/paket/{slug}', [PaketController::class, 'show'])->name('paket.show');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');

// Autentikasi Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Halaman yang Memerlukan Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard Customer
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Alur Pemesanan & Pembayaran Customer
    Route::get('/pesanan/create/{slug}', [PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/{kode_pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{kode_pesanan}/edit', [PesananController::class, 'edit'])->name('pesanan.edit');
    Route::put('/pesanan/{kode_pesanan}', [PesananController::class, 'update'])->name('pesanan.update');
    Route::delete('/pesanan/{kode_pesanan}', [PesananController::class, 'destroy'])->name('pesanan.destroy');
    
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // Panel Khusus Role Admin (SB Admin)
    Route::middleware(IsAdmin::class)->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Kelola Pesanan
        Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('admin.pesanan');
        Route::get('/pesanan/{kode_pesanan}', [AdminController::class, 'detailPesanan'])->name('admin.pesanan.detail');
        Route::post('/pesanan/{id}/status', [AdminController::class, 'updateStatusPesanan']);
        Route::post('/pembayaran/{id}/verifikasi', [AdminController::class, 'verifikasiPembayaran']);

        // Kelola Paket Event (CRUD)
        Route::get('/paket', [AdminController::class, 'paket'])->name('admin.paket');
        Route::post('/paket', [AdminController::class, 'storePaket'])->name('admin.paket.store');
        Route::put('/paket/{id}', [AdminController::class, 'updatePaket'])->name('admin.paket.update');
        Route::delete('/paket/{id}', [AdminController::class, 'destroyPaket'])->name('admin.paket.destroy');

        // Kelola Galeri Portofolio (CRUD)
        Route::get('/galeri', [AdminController::class, 'galeri'])->name('admin.galeri');
        Route::post('/galeri', [AdminController::class, 'storeGaleri'])->name('admin.galeri.store');
        Route::delete('/galeri/{id}', [AdminController::class, 'destroyGaleri'])->name('admin.galeri.destroy');
    });
});