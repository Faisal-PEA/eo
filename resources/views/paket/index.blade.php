@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- HEADER SECTION -->
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm" 
                  style="background-color: var(--teal-light, #E6F5F3); color: var(--teal-primary, #249D8F); border: 1px solid rgba(36, 157, 143, 0.25); font-size: 0.9rem;">
                <i class="bi bi-stars me-1"></i> Penawaran Terbaik
            </span>
            <h1 class="fw-extrabold text-dark display-6 mb-2">Pilihan Paket Acara Kami</h1>
            <p class="text-muted fs-6 max-w-600 mx-auto">
                Temukan paket perencanaan acara yang dirancang khusus sesuai dengan kebutuhan dan anggaran Anda
            </p>

            <!-- MODERN FLOATING PILL FILTER BUTTONS -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4 filter-container">
                <button type="button" class="btn btn-filter active" data-filter="all">
                    <i class="bi bi-grid-fill me-1"></i> Semua Paket
                </button>
                <button type="button" class="btn btn-filter" data-filter="Pernikahan">
                    <i class="bi bi-heart-fill me-1 text-danger-soft"></i> Pernikahan
                </button>
                <button type="button" class="btn btn-filter" data-filter="Ulang Tahun">
                    <i class="bi bi-balloon-fill me-1 text-warning-soft"></i> Ulang Tahun
                </button>
                <button type="button" class="btn btn-filter" data-filter="Seminar">
                    <i class="bi bi-briefcase-fill me-1 text-info-soft"></i> Seminar
                </button>
            </div>
        </div>

        <!-- PACKAGE CARDS GRID -->
        <div class="row g-4" id="paketGrid">
            @forelse($pakets as $paket)
                <div class="col-lg-4 col-md-6 paket-item" data-category="{{ $paket->kategori }}">
                    <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm paket-card">
                        
                        <!-- Gambar Cover Paket dengan deteksi URL aman -->
                        <div class="position-relative paket-img-wrapper">
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
                                     class="w-100 h-100 paket-img" 
                                     alt="{{ $paket->nama_paket }}"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80';">
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark text-white-50">
                                    <i class="bi bi-image fs-1 mb-1"></i>
                                    <small>Belum ada foto</small>
                                </div>
                            @endif

                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill category-badge shadow-sm">
                                {{ $paket->kategori }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title fw-bold text-dark mb-2">{{ $paket->nama_paket }}</h4>
                            <p class="card-text text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                                {{ Str::limit($paket->deskripsi, 110) }}
                            </p>

                            <!-- Harga & Estimasi -->
                            <div class="p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px dashed rgba(36, 157, 143, 0.3);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Harga Mulai Dari:</span>
                                    <span class="fw-extrabold text-teal fs-5">
                                        Rp {{ number_format($paket->harga_dasar, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('paket.show', $paket->id) }}" class="btn btn-teal py-2">
                                    Lihat Detail & Pesan <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5">
                        <i class="bi bi-box-seam display-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3 fs-5">Belum ada paket acara yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* FLOATING PILL FILTER BUTTONS (#249D8F) */
    .filter-container {
        background: #ffffff;
        padding: 8px 12px;
        border-radius: 50rem;
        display: inline-flex !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(36, 157, 143, 0.15);
    }

    .btn-filter {
        border: none;
        outline: none;
        background: transparent;
        color: #475569;
        font-weight: 600;
        font-size: 0.92rem;
        padding: 9px 20px;
        border-radius: 50rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .btn-filter:hover {
        color: #249D8F;
        background-color: #E6F5F3;
        transform: translateY(-1px);
    }

    .btn-filter.active {
        background: linear-gradient(135deg, #249D8F 0%, #1A6B62 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(36, 157, 143, 0.35);
        transform: translateY(-1px);
    }

    .btn-filter.active i {
        color: #ffffff !important;
    }

    .text-danger-soft { color: #E76F51; }
    .text-warning-soft { color: #F4A261; }
    .text-info-soft { color: #2A9D8F; }

    /* PAKET CARD & IMAGE FIX */
    .paket-card {
        background: #ffffff;
        border: 1px solid rgba(36, 157, 143, 0.1) !important;
        transition: all 0.35s ease;
    }

    .paket-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 36px rgba(36, 157, 143, 0.16) !important;
        border-color: rgba(36, 157, 143, 0.35) !important;
    }

    .paket-img-wrapper {
        height: 230px;
        width: 100%;
        position: relative;
        overflow: hidden;
        background-color: #1E293B;
    }

    .paket-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .paket-card:hover .paket-img {
        transform: scale(1.08);
    }

    .category-badge {
        background: rgba(27, 42, 50, 0.85);
        backdrop-filter: blur(6px);
        color: #ffffff;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        z-index: 2;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.btn-filter');
        const paketItems = document.querySelectorAll('.paket-item');

        const urlParams = new URLSearchParams(window.location.search);
        const queryKategori = urlParams.get('kategori');

        if (queryKategori) {
            filterBtns.forEach(btn => {
                if (btn.getAttribute('data-filter') === queryKategori) {
                    btn.click();
                }
            });
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter');

                paketItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    if (filterValue === 'all' || itemCategory === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush