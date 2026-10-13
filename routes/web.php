<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentGatewayController;

/*
|--------------------------------------------------------------------------
| Web Routes - EventKu
|--------------------------------------------------------------------------
*/

// Halaman Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
Route::get('/paket/{id}', [PaketController::class, 'show'])->name('paket.show');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');

// Autentikasi Manual
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Autentikasi Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Customer Protected Routes (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PesananController::class, 'index'])->name('dashboard');
    Route::get('/pesanan/buat/{paket_id}', [PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{id}/edit', [PesananController::class, 'edit'])->name('pesanan.edit');
    Route::put('/pesanan/{id}', [PesananController::class, 'update'])->name('pesanan.update');
    Route::delete('/pesanan/{id}', [PesananController::class, 'destroy'])->name('pesanan.destroy');

    // Pembayaran Manual
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // Midtrans Snap Token & Localhost Finish Handler
    Route::post('/midtrans/token/{pesanan_id}', [PaymentGatewayController::class, 'getSnapToken'])->name('midtrans.token');
    Route::post('/midtrans/finish/{pesanan_id}', [PaymentGatewayController::class, 'finishPayment'])->name('midtrans.finish');
});

// Webhook Callback Midtrans dari Server Luar (Bypass CSRF)
Route::post('/midtrans/callback', [PaymentGatewayController::class, 'callback'])->name('midtrans.callback');

// Admin Protected Routes (Role Admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Kelola Pesanan
    Route::get('/pesanan', [AdminController::class, 'pesananIndex'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [AdminController::class, 'pesananDetail'])->name('pesanan.detail');
    Route::put('/pesanan/{id}/status', [AdminController::class, 'updateStatusPesanan'])->name('pesanan.updateStatus');
    Route::post('/pembayaran/{id}/verifikasi', [AdminController::class, 'verifikasiPembayaran'])->name('pembayaran.verifikasi');

    // CRUD Paket
    Route::get('/paket', [AdminController::class, 'paketIndex'])->name('paket.index');
    Route::post('/paket', [AdminController::class, 'paketStore'])->name('paket.store');
    Route::put('/paket/{id}', [AdminController::class, 'paketUpdate'])->name('paket.update');
    Route::delete('/paket/{id}', [AdminController::class, 'paketDestroy'])->name('paket.destroy');

    // CRUD Galeri
    Route::get('/galeri', [AdminController::class, 'galeriIndex'])->name('galeri.index');
    Route::post('/galeri', [AdminController::class, 'galeriStore'])->name('galeri.store');
    Route::delete('/galeri/{id}', [AdminController::class, 'galeriDestroy'])->name('galeri.destroy');
});