@extends('layouts.app')

@section('content')

<!-- ================= HERO CAROUSEL SECTION ================= -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active" style="min-height: 580px; max-height: 640px;">
            <div class="hero-overlay"></div>
            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1920&q=80" class="d-block w-100 h-100 object-fit-cover position-absolute top-0 start-0 z-0" alt="Wedding">
            <div class="container h-100 position-relative z-2 d-flex align-items-center" style="min-height: 580px;">
                <div class="col-lg-8 text-white py-5">
                    <span class="badge rounded-pill px-3 py-2 fw-semibold mb-3 hero-pill">
                        <i class="bi bi-stars text-warning me-1"></i> #1 Wedding & Event Planner
                    </span>
                    <h1 class="display-4 fw-extrabold mb-3 line-height-tight">
                        Wujudkan Pesta Pernikahan Impian Anda
                    </h1>
                    <p class="fs-5 text-light mb-4 opacity-90 col-md-10">
                        Dari konsep klasik hingga modern glamor, kami siap merancang hari bahagia Anda dengan kesempurnaan detail dan kenangan abadi.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('paket.index') }}?kategori=Pernikahan" class="btn btn-teal-hero px-4 py-3">
                            <i class="bi bi-box-seam me-2"></i> Jelajahi Paket
                        </a>
                        <a href="{{ route('galeri.index') }}" class="btn btn-glass-hero px-4 py-3">
                            <i class="bi bi-images me-2"></i> Lihat Dokumentasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item" style="min-height: 580px; max-height: 640px;">
            <div class="hero-overlay"></div>
            <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1920&q=80" class="d-block w-100 h-100 object-fit-cover position-absolute top-0 start-0 z-0" alt="Birthday">
            <div class="container h-100 position-relative z-2 d-flex align-items-center" style="min-height: 580px;">
                <div class="col-lg-8 text-white py-5">
                    <span class="badge rounded-pill px-3 py-2 fw-semibold mb-3 hero-pill">
                        <i class="bi bi-balloon-fill text-warning me-1"></i> Birthday Celebration
                    </span>
                    <h1 class="display-4 fw-extrabold mb-3 line-height-tight">
                        Pesta Ulang Tahun Seru & Berkesan
                    </h1>
                    <p class="fs-5 text-light mb-4 opacity-90 col-md-10">
                        Dekorasi tematik, hiburan interaktif, dan kejutan istimewa untuk momen pertambahan usia yang penuh tawa bersama sahabat & keluarga.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('paket.index') }}?kategori=Ulang Tahun" class="btn btn-teal-hero px-4 py-3">
                            <i class="bi bi-gift me-2"></i> Paket Ulang Tahun
                        </a>
                        <a href="{{ route('galeri.index') }}" class="btn btn-glass-hero px-4 py-3">
                            <i class="bi bi-play-circle me-2"></i> Galeri Acara
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item" style="min-height: 580px; max-height: 640px;">
            <div class="hero-overlay"></div>
            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1920&q=80" class="d-block w-100 h-100 object-fit-cover position-absolute top-0 start-0 z-0" alt="Corporate">
            <div class="container h-100 position-relative z-2 d-flex align-items-center" style="min-height: 580px;">
                <div class="col-lg-8 text-white py-5">
                    <span class="badge rounded-pill px-3 py-2 fw-semibold mb-3 hero-pill">
                        <i class="bi bi-briefcase-fill text-info me-1"></i> Corporate Gathering
                    </span>
                    <h1 class="display-4 fw-extrabold mb-3 line-height-tight">
                        Event Bisnis Profesional & Skala Besar
                    </h1>
                    <p class="fs-5 text-light mb-4 opacity-90 col-md-10">
                        Manajemen panggung, audio visual mutakhir, dan koordinasi terstruktur untuk kelancaran konferensi dan gathering perusahaan Anda.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('paket.index') }}?kategori=Seminar" class="btn btn-teal-hero px-4 py-3">
                            <i class="bi bi-clipboard2-check me-2"></i> Solusi Perusahaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon rounded-circle p-3" style="background-color: rgba(36,157,143,0.35);"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon rounded-circle p-3" style="background-color: rgba(36,157,143,0.35);"></span>
    </button>
</div>


<!-- ================= STATS COUNTER STRIP ================= -->
<div class="py-4 stats-strip shadow-sm">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <h2 class="fw-extrabold text-teal mb-0">500+</h2>
                    <span class="text-muted small fw-semibold">Acara Sukses</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <h2 class="fw-extrabold text-teal mb-0">98%</h2>
                    <span class="text-muted small fw-semibold">Klien Puas</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <h2 class="fw-extrabold text-teal mb-0">50+</h2>
                    <span class="text-muted small fw-semibold">Vendor Terpilih</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <h2 class="fw-extrabold text-teal mb-0">7+</h2>
                    <span class="text-muted small fw-semibold">Tahun Pengalaman</span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ================= SECTION PAKET ACARA POPULER ================= -->
<section class="py-5 section-popular-packages position-relative overflow-hidden">
    <div class="bg-glow-teal position-absolute top-0 start-50 translate-middle-x"></div>

    <div class="container position-relative z-2 py-4">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm badge-teal-section">
                <i class="bi bi-fire text-warning me-1"></i> Rekomendasi Pilihan
            </span>
            <h2 class="fw-extrabold text-dark display-6 mb-2">Paket Acara Populer</h2>
            <p class="text-muted fs-6 max-w-600 mx-auto">
                Pilih paket yang paling sesuai dengan impian dan anggaran Anda
            </p>
        </div>

        <div class="row g-4">
            @forelse($pakets as $paket)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm package-popular-card">
                        
                        <!-- Gambar Cover Paket -->
                        <div class="position-relative package-img-box">
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

                            @if($imgSrc)
                                <img src="{{ $imgSrc }}" 
                                     class="w-100 h-100 package-cover-img" 
                                     alt="{{ $paket->nama_paket }}"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80';">
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark text-white-50">
                                    <i class="bi bi-image fs-2 mb-1"></i>
                                    <small>EventKu</small>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="badge align-self-start mb-2 px-3 py-1 rounded-pill category-pill-teal">
                                {{ $paket->kategori }}
                            </span>
                            <h5 class="fw-bold text-dark mb-2 text-truncate" title="{{ $paket->nama_paket }}">
                                {{ $paket->nama_paket }}
                            </h5>
                            <p class="card-text text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                                {{ Str::limit($paket->deskripsi, 90) }}
                            </p>

                            <!-- Harga (Mendukung kolom 'harga' maupun 'harga_dasar') -->
                            @php
                                $hargaPaket = $paket->harga ?? $paket->harga_dasar ?? 0;
                            @endphp
                            <div class="pt-3 border-top border-light-subtle mb-3">
                                <span class="text-muted d-block small mb-1">Mulai dari</span>
                                <h4 class="fw-extrabold text-teal mb-0">
                                    Rp {{ number_format($hargaPaket, 0, ',', '.') }}
                                </h4>
                            </div>

                            <!-- Tombol Detail Paket -->
                            <div class="d-grid">
                                <a href="{{ route('paket.show', $paket->id) }}" class="btn btn-detail-teal py-2">
                                    Detail Paket <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada paket yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('paket.index') }}" class="btn btn-teal-outline px-4 py-2 fw-semibold">
                Lihat Semua Paket Lainnya <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>


<!-- ================= MENGAPA MEMILIH KAMI ================= -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm badge-teal-section">
                Keunggulan Kami
            </span>
            <h2 class="fw-extrabold text-dark display-6 mb-2">Mengapa Memilih EventKu?</h2>
            <p class="text-muted fs-6 max-w-600 mx-auto">Kami mengedepankan kualitas, transparansi, dan kenyamanan Anda</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 rounded-4 feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-3 mx-auto">
                        <i class="bi bi-patch-check-fill text-teal fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Tim Berpengalaman</h5>
                    <p class="text-muted small mb-0">Didukung oleh kru dan stage manager berdedikasi tinggi yang telah mengelola ratusan event besar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-3 mx-auto">
                        <i class="bi bi-wallet2 text-teal fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Transparan & Fleksibel</h5>
                    <p class="text-muted small mb-0">Estimasi biaya detail tanpa biaya tersembunyi. Anda bebas memilih add-on pelengkap sesuai bujet.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-3 mx-auto">
                        <i class="bi bi-headset text-teal fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Konsultasi 24/7</h5>
                    <p class="text-muted small mb-0">Konsultasikan konsep ide acara Anda kapan saja bersama tim event strategist kami secara gratis.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(15, 23, 42, 0.90) 0%, rgba(15, 23, 42, 0.55) 60%, rgba(15, 23, 42, 0.3) 100%);
        z-index: 1;
    }

    .hero-pill {
        background-color: rgba(36, 157, 143, 0.35);
        border: 1px solid rgba(36, 157, 143, 0.6);
        backdrop-filter: blur(8px);
        color: #E6F5F3;
        font-size: 0.88rem;
    }

    .btn-teal-hero {
        background: linear-gradient(135deg, #249D8F 0%, #1A6B62 100%);
        color: #ffffff !important;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(36, 157, 143, 0.4);
        transition: all 0.3s ease;
    }

    .btn-teal-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(36, 157, 143, 0.6);
    }

    .btn-glass-hero {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-glass-hero:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
    }

    .stats-strip {
        background: #ffffff;
        border-bottom: 1px solid rgba(36, 157, 143, 0.15);
    }

    .section-popular-packages {
        background: linear-gradient(180deg, #EBF6F5 0%, #F4FAF9 60%, #FFFFFF 100%);
    }

    .bg-glow-teal {
        width: 700px;
        height: 350px;
        background: radial-gradient(circle, rgba(36, 157, 143, 0.15) 0%, rgba(36, 157, 143, 0) 70%);
        filter: blur(40px);
        pointer-events: none;
    }

    .badge-teal-section {
        background-color: #D3ECE9;
        color: #134B45;
        border: 1px solid rgba(36, 157, 143, 0.3);
    }

    .package-popular-card {
        background: #ffffff;
        border: 1px solid rgba(36, 157, 143, 0.15) !important;
        transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .package-popular-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 36px rgba(36, 157, 143, 0.18) !important;
        border-color: rgba(36, 157, 143, 0.4) !important;
    }

    .package-img-box {
        height: 190px;
        width: 100%;
        overflow: hidden;
        background-color: #1E293B;
    }

    .package-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .package-popular-card:hover .package-cover-img {
        transform: scale(1.08);
    }

    .category-pill-teal {
        background-color: #E6F5F3;
        color: #1A6B62;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .btn-detail-teal {
        background: #ffffff;
        color: #249D8F !important;
        border: 1.5px solid #249D8F;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.28s ease;
    }

    .btn-detail-teal:hover {
        background: linear-gradient(135deg, #249D8F 0%, #1A6B62 100%);
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 6px 18px rgba(36, 157, 143, 0.35);
        transform: translateY(-2px);
    }

    .feature-card {
        background: #F8FAFC;
        border: 1px solid rgba(36, 157, 143, 0.12);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(36, 157, 143, 0.12);
        transform: translateY(-5px);
        border-color: rgba(36, 157, 143, 0.35);
    }

    .feature-icon-wrapper {
        width: 60px;
        height: 60px;
        background: #E6F5F3;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush