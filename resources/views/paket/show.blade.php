@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        
        <!-- BREADCRUMB NAVIGASI -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-teal text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('paket.index') }}" class="text-teal text-decoration-none">Paket Acara</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">{{ $paket->nama_paket }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- KOLOM FOTO & DESKRIPSI UTAMA -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 overflow-hidden shadow-sm mb-4">
                    @php
                        $imgSrc = null;
                        if ($paket->gambar) {
                            if (Str::startsWith($paket->gambar, ['http://', 'https://'])) {
                                $imgSrc = $paket->gambar;
                            } else {
                                $imgSrc = asset('uploads/paket/' . $paket->gambar);
                            }
                        }
                    @endphp

                    <div style="height: 420px; background-color: #1E293B;" class="position-relative overflow-hidden">
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" 
                                 class="w-100 h-100 object-fit-cover" 
                                 alt="{{ $paket->nama_paket }}"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&q=80';">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <span class="badge position-absolute top-0 start-0 m-4 px-3 py-2 rounded-pill shadow-sm" 
                              style="background-color: rgba(27, 42, 50, 0.85); backdrop-filter: blur(6px); color: #fff;">
                            {{ $paket->kategori }}
                        </span>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h2 class="fw-extrabold text-dark mb-0">{{ $paket->nama_paket }}</h2>
                            <span class="badge badge-teal px-3 py-2 fs-6">
                                <i class="bi bi-tag-fill me-1"></i> Kategori {{ $paket->kategori }}
                            </span>
                        </div>
                        <hr class="opacity-25 my-4">

                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-info-circle text-teal me-2"></i>Deskripsi & Fasilitas Layanan
                        </h5>
                        <p class="text-secondary fs-6" style="line-height: 1.8; white-space: pre-line;">
                            {{ $paket->deskripsi }}
                        </p>

                        <div class="p-4 rounded-3 mt-4" style="background-color: #F8FAFC; border: 1px dashed rgba(36, 157, 143, 0.3);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: #E6F5F3; width: 45px; height: 45px;">
                                    <i class="bi bi-gem text-teal fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Layanan Tambahan (Add-ons) Tersedia</h6>
                                    <small class="text-muted">Anda dapat memilih dekorasi panggung, dokumentasi drone, musik akustik, hingga photobooth pada halaman pemesanan selanjutnya.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM HARGA & TOMBOL ORDER -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <span class="text-muted small">Harga Dasar Paket</span>
                        @php
                            $hargaPaket = $paket->harga ?? $paket->harga_dasar ?? 0;
                        @endphp
                        <h2 class="fw-extrabold text-teal mb-3">
                            Rp {{ number_format($hargaPaket, 0, ',', '.') }}
                        </h2>

                        <div class="p-3 rounded-3 mb-4" style="background-color: #F0F9F8; border: 1px dashed rgba(36, 157, 143, 0.35);">
                            <div class="d-flex align-items-center gap-2 text-teal small fw-semibold">
                                <i class="bi bi-shield-check fs-5"></i>
                                <span>DP 30% untuk mengunci tanggal jadwal acara</span>
                            </div>
                        </div>

                        <!-- TOMBOL LANJUT KE PEMESANAN -->
                        <div class="d-grid gap-2">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <div class="alert alert-warning small mb-0 rounded-3">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Anda login sebagai Admin. Pemesanan hanya untuk Customer.
                                    </div>
                                @else
                                    <a href="{{ route('pesanan.create', $paket->id) }}" class="btn btn-teal py-3 fw-bold fs-6">
                                        <i class="bi bi-calendar-check me-2"></i> Pesan Paket Ini Sekarang
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-teal py-3 fw-bold fs-6">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk untuk Memesan
                                </a>
                            @endauth
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">Konsultasi gratis via WhatsApp</small>
                            <br>
                            <a href="https://wa.me/6281234567890?text=Halo%20EventKu,%20saya%20tertarik%20dengan%20paket%20{{ urlencode($paket->nama_paket) }}" 
                               target="_blank" 
                               class="text-teal fw-semibold text-decoration-none small">
                                <i class="bi bi-whatsapp me-1 text-success"></i> Hubungi Customer Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection