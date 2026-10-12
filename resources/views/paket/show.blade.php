@extends('layouts.app')

@section('title', $paket->nama_paket . ' - EventKu')

@section('content')
<section class="py-5" style="background-color: var(--light-bg);">
    <div class="container">
        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="{{ url('/paket') }}" class="text-decoration-none text-muted">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Paket
            </a>
        </div>

        <div class="row g-5">
            <!-- Kolom Kiri: Foto & Deskripsi Lengkap -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                    <img src="{{ $paket->gambar }}" class="img-fluid w-100" alt="{{ $paket->nama_paket }}" style="max-height: 420px; object-fit: cover;">
                    <div class="card-body p-4 p-md-5">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                            {{ $paket->kategori }}
                        </span>
                        <h2 class="fw-bold text-dark mb-3">{{ $paket->nama_paket }}</h2>
                        <h4 class="text-primary fw-bold mb-4">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                        </h4>
                        
                        <h5 class="fw-bold mb-3">Deskripsi Layanan</h5>
                        <p class="text-muted lead fs-6" style="line-height: 1.8;">
                            {{ $paket->deskripsi }}
                        </p>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Layanan Tambahan (Add-on) yang Bisa Dipilih Saat Pesan:</h5>
                        <div class="row g-2">
                            @foreach($addons as $addon)
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-3 bg-light">
                                        <div class="fw-bold text-dark small">{{ $addon->nama_addon }}</div>
                                        <small class="text-primary fw-semibold">+ Rp {{ number_format($addon->harga, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Kotak Booking Sticky -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-3">Tertarik dengan Paket Ini?</h5>
                    <p class="text-muted small mb-4">Pesan sekarang untuk mengunci tanggal acara Anda bersama tim profesional EventKu.</p>
                    
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Harga Dasar Paket</span>
                            <span class="fw-bold">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-success small">
                            <span>Sistem Pembayaran</span>
                            <span>Bisa DP 50%</span>
                        </div>
                    </div>

                    <!-- Logika Pembatasan Tombol Pesan -->
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <!-- Jika Login Sebagai Admin: Tampilkan Peringatan Khusus -->
                            <div class="alert alert-warning border-0 rounded-3 p-3 text-center mb-0">
                                <i class="bi bi-shield-lock-fill fs-3 text-warning d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Mode Administrator</h6>
                                <p class="text-muted small mb-2">Akun Admin tidak diizinkan membuat pesanan paket acara.</p>
                                <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                    Kembali ke Panel Admin
                                </a>
                            </div>
                        @else
                            <!-- Jika Pelanggan Biasa: Tombol Pesan Aktif -->
                            <a href="{{ url('/pesanan/create/' . $paket->slug) }}" class="btn btn-primary-custom w-100 py-3 fs-6">
                                <i class="bi bi-calendar2-plus me-2"></i> Pesan Acara Sekarang
                            </a>
                        @endif
                    @else
                        <!-- Jika Belum Login: Arahkan untuk Login/Daftar -->
                        <a href="{{ url('/login') }}" class="btn btn-primary-custom w-100 py-3 fs-6">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk untuk Memesan
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection