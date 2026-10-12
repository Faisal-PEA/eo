@extends('layouts.app')

@section('title', 'Katalog Paket Event - EventKu')

@section('styles')
<style>
    /* Kontainer Filter Modern Pills */
    .filter-wrapper {
        background: #ffffff;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        display: inline-flex;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .filter-btn-luxury {
        border: none;
        background: transparent;
        color: #666;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 10px 24px;
        border-radius: 40px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
    }

    .filter-btn-luxury:hover {
        color: var(--primary);
    }

    /* Efek Saat Aktif */
    .filter-btn-luxury.active {
        background: linear-gradient(135deg, var(--primary) 0%, #8E54E9 100%);
        color: #ffffff !important;
        box-shadow: 0 6px 15px rgba(108, 99, 255, 0.35);
        transform: scale(1.02);
    }

    /* Transisi Animasi Kartu Paket */
    .paket-card-box {
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .paket-card-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(108, 99, 255, 0.12) !important;
    }

    /* Efek Fade In Halus Saat Filter Dipilih */
    .paket-item {
        transition: opacity 0.4s ease, transform 0.4s ease;
    }

    .paket-item.fade-out {
        opacity: 0;
        transform: scale(0.96) translateY(10px);
        pointer-events: none;
    }

    .paket-item.fade-in {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
</style>
@endsection

@section('content')
<!-- Header Banner & Filter Kategori Interaktif -->
<section class="py-5 bg-white border-bottom">
    <div class="container text-center">
        <span class="badge bg-warning bg-opacity-25 text-dark px-3 py-2 rounded-pill fw-bold mb-2">
            <i class="bi bi-gem me-1 text-warning"></i> Kurasi Paket Spesial
        </span>
        <h1 class="fw-bold mb-2 text-dark">Eksplorasi Paket Event</h1>
        <p class="text-muted">Pilih kategori acara dan temukan konsep terbaik untuk momen istimewa Anda</p>
        
        <!-- Filter Bar Modern & Elegan -->
        <div class="mt-4">
            <div class="filter-wrapper flex-wrap justify-content-center">
                <button type="button" class="filter-btn-luxury active" data-kategori="all">
                    <i class="bi bi-grid-fill me-1"></i> Semua Acara
                </button>
                <button type="button" class="filter-btn-luxury" data-kategori="Pernikahan">
                    <i class="bi bi-heart-fill me-1 text-danger"></i> Pernikahan
                </button>
                <button type="button" class="filter-btn-luxury" data-kategori="Ulang Tahun">
                    <i class="bi bi-balloon-fill me-1 text-warning"></i> Ulang Tahun
                </button>
                <button type="button" class="filter-btn-luxury" data-kategori="Seminar">
                    <i class="bi bi-briefcase-fill me-1 text-info"></i> Seminar
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Grid Daftar Paket -->
<section class="py-5" style="background-color: var(--light-bg);">
    <div class="container">
        <!-- Notifikasi jika tidak ada paket -->
        <div id="no-paket-alert" class="text-center py-5 d-none">
            <i class="bi bi-calendar-x text-muted" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold mt-3">Belum Ada Paket Tersedia</h5>
            <p class="text-muted">Paket untuk kategori ini sedang disiapkan.</p>
        </div>

        <div class="row g-4" id="paket-container">
            @foreach($pakets as $paket)
                <div class="col-lg-4 col-md-6 paket-item fade-in" data-kategori="{{ $paket->kategori }}">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column bg-white paket-card-box">
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $paket->gambar }}" class="card-img-top" alt="{{ $paket->nama_paket }}" 
                                 style="height: 230px; object-fit: cover; transition: transform 0.5s ease;">
                            <span class="badge bg-white text-dark shadow-sm position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill fw-bold border">
                                <i class="bi bi-stars text-warning me-1"></i> {{ $paket->kategori }}
                            </span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title fw-bold text-dark">{{ $paket->nama_paket }}</h4>
                            <p class="card-text text-muted small flex-grow-1" style="line-height: 1.6;">
                                {{ $paket->deskripsi }}
                            </p>
                            <hr class="text-muted my-3 opacity-25">
                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.8rem;">Investasi Mulai</small>
                                    <span class="fs-5 fw-bold text-primary">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ url('/paket/' . $paket->slug) }}" class="btn btn-primary-custom btn-sm rounded-pill px-3 shadow-sm">
                                    Rincian Paket <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.filter-btn-luxury');
        const items = document.querySelectorAll('.paket-item');
        const noAlert = document.getElementById('no-paket-alert');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                // 1. Ganti status aktif tombol
                buttons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const selectedKategori = this.getAttribute('data-kategori');
                let countVisible = 0;

                // 2. Animasi fade transisi
                items.forEach(item => {
                    const itemKategori = item.getAttribute('data-kategori');
                    const match = (selectedKategori === 'all' || itemKategori === selectedKategori);

                    if (match) {
                        item.classList.remove('d-none');
                        // Berikan sedikit delay untuk memicu animasi masuk
                        setTimeout(() => {
                            item.classList.remove('fade-out');
                            item.classList.add('fade-in');
                        }, 20);
                        countVisible++;
                    } else {
                        item.classList.remove('fade-in');
                        item.classList.add('fade-out');
                        setTimeout(() => {
                            item.classList.add('d-none');
                        }, 300);
                    }
                });

                // 3. Notifikasi jika kosong
                setTimeout(() => {
                    if (countVisible === 0) {
                        noAlert.classList.remove('d-none');
                    } else {
                        noAlert.classList.add('d-none');
                    }
                }, 300);
            });
        });
    });
</script>
@endsection