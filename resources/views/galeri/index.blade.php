@extends('layouts.app')

@section('title', 'Galeri Portofolio Dokumentasi - EventKu')

@section('styles')
<style>
    /* Styling Filter Bar Kapsul Elegan */
    .filter-gallery-wrapper {
        background: #ffffff;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        display: inline-flex;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .gallery-filter-btn {
        border: none;
        background: transparent;
        color: #666;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 9px 22px;
        border-radius: 40px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
    }

    .gallery-filter-btn:hover {
        color: var(--primary);
    }

    .gallery-filter-btn.active {
        background: linear-gradient(135deg, var(--primary) 0%, #8E54E9 100%);
        color: #ffffff !important;
        box-shadow: 0 6px 15px rgba(108, 99, 255, 0.35);
        transform: scale(1.02);
    }

    /* Wadah Bingkai Kartu Galeri */
    .gallery-card {
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        background: linear-gradient(145deg, #18172b 0%, #0d0c18 100%); /* Latar panggung gelap mewah */
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(108, 99, 255, 0.25);
    }

    /* Wadah Foto: Foto Asli Utuh 100% Tanpa Terpotong (object-fit: contain) */
    .gallery-img-box {
        width: 100%;
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 10px;
    }

    .gallery-img-box img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain; /* Foto asli utuh tidak akan pernah terpotong! */
        border-radius: 0.75rem;
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 0.4s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    /* Efek Zoom Halus Saat Hover */
    .gallery-card:hover .gallery-img-box img {
        transform: scale(1.06);
    }

    /* Overlay Kaca Slide-Up dari Bawah */
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 22px;
        background: linear-gradient(180deg, rgba(13, 12, 24, 0.2) 0%, rgba(13, 12, 24, 0.9) 40%, rgba(13, 12, 24, 0.98) 100%);
        backdrop-filter: blur(6px);
        transform: translateY(12px);
        opacity: 0.95;
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .gallery-card:hover .gallery-overlay {
        transform: translateY(0);
        opacity: 1;
    }

    /* Tombol Preview Expand */
    .gallery-icon-preview {
        position: absolute;
        top: 18px;
        right: 18px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.6);
        transition: all 0.3s ease;
        z-index: 5;
    }

    .gallery-card:hover .gallery-icon-preview {
        opacity: 1;
        transform: scale(1);
    }

    /* Transisi Animasi Filter Kartu */
    .gallery-item {
        transition: opacity 0.35s ease, transform 0.35s ease;
    }

    .gallery-item.fade-out {
        opacity: 0;
        transform: scale(0.95);
        pointer-events: none;
    }

    .gallery-item.fade-in {
        opacity: 1;
        transform: scale(1);
    }
</style>
@endsection

@section('content')
<!-- Header Banner Portofolio -->
<section class="py-5 bg-white border-bottom">
    <div class="container text-center">
        <span class="badge bg-warning bg-opacity-25 text-dark px-3 py-2 rounded-pill fw-bold mb-2">
            <i class="bi bi-camera-fill me-1 text-warning"></i> Portofolio Dokumentasi
        </span>
        <h1 class="fw-bold mb-2 text-dark">Galeri Karya & Acara Kami</h1>
        <p class="text-muted">Dokumentasi momen-momen indah yang sukses diselenggarakan oleh tim profesional EventKu</p>

        <!-- Filter Tab Kategori Dinamis -->
        <div class="mt-4">
            <div class="filter-gallery-wrapper flex-wrap justify-content-center">
                <button type="button" class="gallery-filter-btn active" data-filter="all">
                    <i class="bi bi-grid-fill me-1"></i> Semua Acara
                </button>
                <button type="button" class="gallery-filter-btn" data-filter="Pernikahan">
                    <i class="bi bi-heart-fill me-1 text-danger"></i> Pernikahan
                </button>
                <button type="button" class="gallery-filter-btn" data-filter="Ulang Tahun">
                    <i class="bi bi-balloon-fill me-1 text-warning"></i> Ulang Tahun
                </button>
                <button type="button" class="gallery-filter-btn" data-filter="Seminar">
                    <i class="bi bi-briefcase-fill me-1 text-info"></i> Seminar
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Helper URL Gambar Galeri -->
@php
    function getFotoPath($path) {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return asset('uploads/galeri/' . $path);
    }
@endphp

<!-- Grid Galeri Foto Proporsi Utuh -->
<section class="py-5" style="background-color: var(--light-bg);">
    <div class="container">
        <!-- Notifikasi jika kategori kosong -->
        <div id="no-gallery-alert" class="text-center py-5 d-none">
            <i class="bi bi-images text-muted" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold mt-3 text-secondary">Belum Ada Dokumentasi di Kategori Ini</h5>
            <p class="text-muted small">Silakan pilih kategori acara lainnya.</p>
        </div>

        @if($galeris->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-images text-muted" style="font-size: 3.5rem;"></i>
                <h5 class="fw-bold mt-3 text-secondary">Belum Ada Dokumentasi</h5>
                <p class="text-muted small">Foto dokumentasi acara sedang dipersiapkan oleh tim admin.</p>
            </div>
        @else
            <div class="row g-4" id="gallery-container">
                @foreach($galeris as $g)
                    <div class="col-lg-4 col-md-6 gallery-item fade-in" data-kategori="{{ $g->kategori }}">
                        <div class="gallery-card" onclick="previewImage('{{ getFotoPath($g->gambar) }}', '{{ $g->judul }}', '{{ $g->kategori }}')">
                            <!-- Wadah Foto yang Menjaga Foto Asli Utuh 100% -->
                            <div class="gallery-img-box">
                                <img src="{{ getFotoPath($g->gambar) }}" alt="{{ $g->judul }}">
                            </div>

                            <!-- Tombol Ikon Zoom -->
                            <div class="gallery-icon-preview">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </div>

                            <!-- Keterangan Slide-Up Sinematik -->
                            <div class="gallery-overlay">
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small fw-bold mb-1">
                                    {{ $g->kategori }}
                                </span>
                                <h5 class="fw-bold text-white mb-0 text-truncate" title="{{ $g->judul }}" style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">
                                    {{ $g->judul }}
                                </h5>
                                <small class="text-white-50 d-block mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-zoom-in me-1"></i> Klik untuk memperbesar foto penuh
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Modal Preview Foto Besar (Lightroom Style) -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark text-white border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div>
                    <span id="modalKategoriBadge" class="badge bg-warning text-dark px-3 py-1 rounded-pill small fw-bold mb-1"></span>
                    <h5 class="modal-title fw-bold" id="modalJudul"></h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modalImage" src="" class="img-fluid rounded-3 shadow" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.gallery-filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const noAlert = document.getElementById('no-gallery-alert');

        // Filter Kategori Interaktif
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                let count = 0;

                galleryItems.forEach(item => {
                    const itemKat = item.getAttribute('data-kategori');
                    const match = (filter === 'all' || itemKat === filter);

                    if (match) {
                        item.classList.remove('d-none');
                        setTimeout(() => {
                            item.classList.remove('fade-out');
                            item.classList.add('fade-in');
                        }, 20);
                        count++;
                    } else {
                        item.classList.remove('fade-in');
                        item.classList.add('fade-out');
                        setTimeout(() => {
                            item.classList.add('d-none');
                        }, 300);
                    }
                });

                setTimeout(() => {
                    if (count === 0) {
                        noAlert.classList.remove('d-none');
                    } else {
                        noAlert.classList.add('d-none');
                    }
                }, 300);
            });
        });
    });

    // Preview Foto Besar Modal
    function previewImage(imageSrc, judul, kategori) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalJudul').textContent = judul;
        document.getElementById('modalKategoriBadge').textContent = kategori;
        
        const previewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        previewModal.show();
    }
</script>
@endsection