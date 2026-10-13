@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- HEADER SECTION -->
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm" 
                  style="background-color: var(--teal-light, #E6F5F3); color: var(--teal-primary, #249D8F); border: 1px solid rgba(36, 157, 143, 0.25); font-size: 0.9rem;">
                <i class="bi bi-camera-fill me-1"></i> Portofolio Dokumentasi
            </span>
            <h1 class="fw-extrabold text-dark display-6 mb-2">Galeri Karya & Acara Kami</h1>
            <p class="text-muted fs-6 max-w-600 mx-auto">
                Dokumentasi momen-momen indah yang sukses diselenggarakan oleh tim profesional EventKu
            </p>

            <!-- MODERN PILL FILTER BUTTONS -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4 filter-container">
                <button type="button" class="btn btn-filter active" data-filter="all">
                    <i class="bi bi-grid-fill me-1"></i> Semua Acara
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

        <!-- GALLERY GRID -->
        <div class="row g-4" id="galleryGrid">
            @forelse($galeris as $item)
                <div class="col-lg-4 col-md-6 gallery-item" data-category="{{ $item->kategori }}">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 gallery-card">
                        <!-- Image Container / Slide Frame -->
                        <div class="gallery-img-wrapper position-relative">
                            <img src="{{ asset('uploads/galeri/' . $item->gambar) }}" 
                                 alt="{{ $item->judul }}" 
                                 class="gallery-img w-100 h-100"
                                 loading="lazy">

                            <!-- Hover Overlay with Slide Transition -->
                            <div class="gallery-overlay d-flex flex-column justify-content-end p-4">
                                <span class="badge align-self-start mb-2 px-3 py-1 rounded-pill category-tag">
                                    {{ $item->kategori }}
                                </span>
                                <h5 class="text-white fw-bold mb-1 title-reveal">{{ $item->judul }}</h5>
                                <p class="text-white-50 small mb-3 desc-reveal">Klik untuk memperbesar foto</p>
                                
                                <button type="button" 
                                        class="btn btn-light btn-sm rounded-circle shadow-sm zoom-trigger align-self-end" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#imageModal"
                                        data-bs-img="{{ asset('uploads/galeri/' . $item->gambar) }}"
                                        data-bs-title="{{ $item->judul }}"
                                        data-bs-cat="{{ $item->kategori }}">
                                    <i class="bi bi-zoom-in text-teal"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5">
                        <i class="bi bi-images display-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3 fs-5">Belum ada foto dokumentasi yang diunggah.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- MODAL PREVIEW FOTO -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header border-0 py-3 px-4 bg-white">
                <div>
                    <span class="badge px-3 py-1 rounded-pill mb-1" id="modalCategory" style="background-color: #E6F5F3; color: #249D8F;"></span>
                    <h5 class="modal-title fw-bold text-dark" id="modalTitle"></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-dark text-center" style="max-height: 80vh; overflow: hidden;">
                <img src="" id="modalImg" class="img-fluid" style="max-height: 75vh; width: auto; object-fit: contain;" alt="">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ===================================================
       STYLING TOMBOL FILTER ELEGAN (TEAL #249D8F)
       =================================================== */
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

    /* Status Tombol Aktif */
    .btn-filter.active {
        background: linear-gradient(135deg, #249D8F 0%, #1A6B62 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(36, 157, 143, 0.35);
        transform: translateY(-1px);
    }

    .btn-filter.active i {
        color: #ffffff !important;
    }

    /* Ikon Aksen Lembut */
    .text-danger-soft { color: #E76F51; }
    .text-warning-soft { color: #F4A261; }
    .text-info-soft { color: #2A9D8F; }

    /* ===================================================
       STYLING FRAME GALERI & HOVER EFFECTS
       =================================================== */
    .gallery-card {
        background: #ffffff;
        transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 36px rgba(36, 157, 143, 0.16) !important;
    }

    .gallery-img-wrapper {
        height: 280px;
        background-color: #0F172A; /* Frame background elegan gelap */
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Foto ASLI tanpa terpotong (contain) + zoom halus */
    .gallery-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .gallery-card:hover .gallery-img {
        transform: scale(1.08);
    }

    /* Sliding Glass Overlay */
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.3) 60%, transparent 100%);
        opacity: 0;
        transform: translateY(15px);
        transition: all 0.4s ease;
    }

    .gallery-card:hover .gallery-overlay {
        opacity: 1;
        transform: translateY(0);
    }

    .category-tag {
        background-color: rgba(36, 157, 143, 0.85);
        color: white;
        font-size: 0.75rem;
        backdrop-filter: blur(4px);
    }

    .zoom-trigger {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .zoom-trigger:hover {
        transform: scale(1.15);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // FILTER KATEGORI ELEGAN
        const filterBtns = document.querySelectorAll('.btn-filter');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter');

                galleryItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    if (filterValue === 'all' || itemCategory === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // MODAL PREVIEW DETAIL
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const imgSrc = button.getAttribute('data-bs-img');
                const title = button.getAttribute('data-bs-title');
                const cat = button.getAttribute('data-bs-cat');

                document.getElementById('modalImg').src = imgSrc;
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('modalCategory').textContent = cat;
            });
        }
    });
</script>
@endpush