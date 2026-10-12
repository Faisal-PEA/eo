@extends('layouts.admin')

@section('title', 'Kelola Galeri Acara - SB Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Kelola Galeri Portofolio</h1>
        <p class="text-muted small mb-0">Unggah foto dokumentasi acara sukses untuk dipajang di website depan.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahFoto">
        <i class="bi bi-cloud-arrow-up me-1"></i> Upload Foto Acara
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Grid Foto Galeri Admin -->
<div class="row g-4">
    @forelse($galeris as $g)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card card-sb h-100 overflow-hidden shadow-sm">
                <!-- Foto Portofolio -->
                <img src="{{ asset('uploads/galeri/' . $g->gambar) }}" class="card-img-top" alt="{{ $g->judul }}" 
                     style="height: 190px; object-fit: cover;">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark small fw-bold">{{ $g->kategori }}</span>
                    </div>
                    <h6 class="fw-bold text-dark text-truncate mb-3" title="{{ $g->judul }}">{{ $g->judul }}</h6>
                    
                    <form action="{{ url('/admin/galeri/' . $g->id) }}" method="POST" class="mt-auto" onsubmit="return confirm('Hapus foto ini dari portofolio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-1">
                            <i class="bi bi-trash me-1"></i> Hapus Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card card-sb p-5 text-center text-muted">
                <i class="bi bi-images fs-1 mb-2"></i>
                Belum ada foto yang diunggah ke galeri portofolio.
            </div>
        </div>
    @endforelse
</div>

<!-- Modal Upload Foto Baru -->
<div class="modal fade" id="modalTambahFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cloud-arrow-up text-primary me-1"></i> Upload Foto Portofolio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/admin/galeri') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul / Nama Acara</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Resepsi Pernikahan Anisa & Dimas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori Acara</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Pernikahan">Pernikahan</option>
                            <option value="Ulang Tahun">Ulang Tahun</option>
                            <option value="Seminar">Seminar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih File Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Upload Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection