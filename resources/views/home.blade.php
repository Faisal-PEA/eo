@extends('layouts.app')

@section('title', 'EventKu - Event Organizer Profesional & Terpercaya')

@section('styles')
<style>
    /* Styling Elegan untuk Hero Carousel */
    .hero-carousel {
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .hero-carousel .carousel-item {
        height: 440px;
        background-color: #000;
        transition: opacity 0.8s ease-in-out;
    }

    .hero-carousel .carousel-item img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        filter: brightness(0.92);
        transition: transform 6s ease;
    }

    /* Efek Zoom Pelan (Ken Burns Effect) saat slide aktif */
    .hero-carousel .carousel-item.active img {
        transform: scale(1.05);
    }

    /* Overlay Keterangan Slide */
    .carousel-caption-custom {
        position: absolute;
        bottom: 25px;
        right: 25px;
        background: rgba(31, 29, 54, 0.75);
        backdrop-filter: blur(8px);
        padding: 10px 18px;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        z-index: 10;
    }

    /* Floating Stat Box */
    .floating-stat-box {
        position: absolute;
        bottom: 25px;
        left: 25px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 12px 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    /* Custom Indicators */
    .hero-carousel .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #fff;
        opacity: 0.5;
        border: none;
        margin: 0 4px;
        transition: all 0.3s ease;
    }

    .hero-carousel .carousel-indicators .active {
        width: 25px;
        border-radius: 10px;
        background-color: var(--secondary);
        opacity: 1;
    }
</style>
@endsection

@section('content')
<!-- Hero Section dengan Slider Otomatis Elegan -->
<section class="py-5" style="background: linear-gradient(135deg, rgba(108, 99, 255, 0.08) 0%, rgba(245, 166, 35, 0.05) 100%);">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <!-- Kolom Teks Promosi Kiri -->
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
                    <i class="bi bi-award me-1"></i> #1 Jasa Event Organizer Pilihan
                </span>
                <h1 class="display-4 fw-bold text-dark mb-3">
                    Wujudkan Momen Spesial <span style="color: var(--primary);">Tanpa Repot</span>
                </h1>
                <p class="lead text-muted mb-4 fs-6" style="line-height: 1.8;">
                    Dari pernikahan impian yang sakral, pesta ulang tahun meriah, hingga konferensi bisnis profesional. Kami rancang setiap detail agar Anda tinggal menikmati harinya.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ url('/paket') }}" class="btn btn-primary-custom btn-lg shadow-sm">
                        <i class="bi bi-calendar2-check me-2"></i>Pilih Paket Event
                    </a>
                    <a href="#keunggulan" class="btn btn-outline-secondary btn-lg">
                        Pelajari Selengkapnya
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Slider Gambar Kategori Otomatis -->
            <div class="col-lg-6">
                <div class="position-relative">
                    <div id="heroEventSlider" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="3500">
                        <!-- Indikator Slide -->
                        <div class="carousel-indicators mb-2">
                            @foreach($pakets as $index => $p)
                                <button type="button" data-bs-target="#heroEventSlider" data-bs-slide-to="{{ $index }}" 
                                        class="{{ $index === 0 ? 'active' : '' }}" 
                                        aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>

                        <!-- Foto Slide -->
                        <div class="carousel-inner">
                            @foreach($pakets as $index => $p)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $p->gambar }}" alt="{{ $p->nama_paket }}">
                                    
                                    <!-- Label Kategori & Nama Paket di Sudut Kanan Bawah -->
                                    <div class="carousel-caption-custom d-none d-sm-flex align-items-center gap-2">
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small fw-bold">
                                            {{ $p->kategori }}
                                        </span>
                                        <span class="small fw-semibold text-white">{{ $p->nama_paket }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Floating Badge "500+ Acara Sukses" di Sudut Kiri Bawah -->
                    <div class="floating-stat-box d-none d-sm-block">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-success fs-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">500+ Acara</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Sukses Diselenggarakan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Paket Populer -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase small">Pilihan Terbaik</span>
            <h2 class="fw-bold mt-1">Paket Acara Populer</h2>
            <p class="text-muted">Pilih paket yang paling sesuai dengan impian dan anggaran Anda</p>
        </div>

        <div class="row g-4">
            @foreach($pakets as $paket)
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column" style="transition: transform 0.2s;">
                        <img src="{{ $paket->gambar }}" class="card-img-top" alt="{{ $paket->nama_paket }}" style="height: 180px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="mb-2">
                                <span class="badge bg-light text-primary border">{{ $paket->kategori }}</span>
                            </div>
                            <h5 class="card-title fw-bold text-dark">{{ $paket->nama_paket }}</h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($paket->deskripsi, 85) }}
                            </p>
                            <hr class="my-3 text-muted opacity-25">
                            <div class="mb-3">
                                <small class="text-muted d-block">Mulai dari</small>
                                <span class="fs-5 fw-bold text-primary">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ url('/paket/' . $paket->slug) }}" class="btn btn-outline-primary w-100 rounded-3 fw-bold">
                                Detail Paket <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ url('/paket') }}" class="btn btn-secondary-custom">
                Lihat Semua Paket <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Section Keunggulan -->
<section id="keunggulan" class="py-5" style="background-color: var(--light-bg);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih EventKu?</h2>
            <p class="text-muted">Kemudahan dan jaminan kepuasan di setiap tahapan acara</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Transparan & Terpercaya</h5>
                    <p class="text-muted small mb-0">Rincian harga jelas tanpa biaya tersembunyi. Pelunasan fleksibel dengan sistem DP 50%.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-palette fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Bisa Kustomisasi Add-on</h5>
                    <p class="text-muted small mb-0">Sesuaikan acara dengan add-on sesuka Anda, mulai dari fotografer, drone, live music, hingga lighting.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-chat-heart fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Tim Berpengalaman</h5>
                    <p class="text-muted small mb-0">Event organizer profesional yang siap mendampingi acara Anda dari konsep awal hingga selesai.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection