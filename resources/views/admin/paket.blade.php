@extends('layouts.admin')

@section('title', 'Kelola Paket Event - SB Admin')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Kelola Paket Event</h1>
        <p class="text-muted small mb-0">Tambah, perbarui harga, dan unggah foto paket acara yang tampil di website publik.</p>
    </div>
    <!-- Tombol Buka Modal Tambah Paket -->
    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
        <i class="bi bi-cloud-arrow-up me-1"></i> Tambah Paket Baru
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger py-2 small rounded-3 mb-4 shadow-sm">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Helper untuk menampilkan gambar (Mendukung file upload lokal & link eksternal) -->
@php
    function getGambarPaket($path) {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return asset('uploads/paket/' . $path);
    }
@endphp

<!-- Tabel Daftar Paket SB Admin -->
<div class="card card-sb mb-4">
    <div class="card-header py-3 bg-white border-0">
        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-boxes me-1"></i> Semua Paket Event Aktif</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-4">Foto</th>
                        <th>Nama Paket</th>
                        <th>Kategori</th>
                        <th>Harga Paket</th>
                        <th>Deskripsi</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pakets as $p)
                        <tr>
                            <td class="ps-4" style="width: 80px;">
                                <img src="{{ getGambarPaket($p->gambar) }}" class="rounded-2 shadow-sm" style="width: 65px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block">{{ $p->nama_paket }}</span>
                                <small class="text-muted">Slug: /{{ $p->slug }}</small>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ $p->kategori }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            </td>
                            <td style="max-width: 250px;">
                                <span class="small text-muted text-truncate d-block">{{ $p->deskripsi }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-outline-warning btn-sm py-1 px-2 text-dark" 
                                            data-bs-toggle="modal" data-bs-target="#modalEditPaket{{ $p->id }}" title="Edit Paket">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ url('/admin/paket/' . $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="Hapus Paket">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Paket -->
                        <div class="modal fade" id="modalEditPaket{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content rounded-3 border-0 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold text-dark">Edit Paket: {{ $p->nama_paket }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ url('/admin/paket/' . $p->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-bold">Nama Paket Event</label>
                                                    <input type="text" name="nama_paket" class="form-control" value="{{ $p->nama_paket }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold">Kategori Acara</label>
                                                    <select name="kategori" class="form-select" required>
                                                        <option value="Pernikahan" {{ $p->kategori == 'Pernikahan' ? 'selected' : '' }}>Pernikahan</option>
                                                        <option value="Ulang Tahun" {{ $p->kategori == 'Ulang Tahun' ? 'selected' : '' }}>Ulang Tahun</option>
                                                        <option value="Seminar" {{ $p->kategori == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Harga Paket (Rp)</label>
                                                    <input type="number" name="harga" class="form-control" value="{{ $p->harga }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Ganti Foto Paket (Opsional)</label>
                                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                                    <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti foto saat ini.</small>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-bold">Deskripsi Layanan Lengkap</label>
                                                    <textarea name="deskripsi" rows="3" class="form-control" required>{{ $p->deskripsi }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm px-3">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Paket Baru dengan Upload File -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cloud-arrow-up me-1 text-primary"></i> Tambah Paket Event Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/admin/paket') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Paket Event</label>
                            <input type="text" name="nama_paket" class="form-control" placeholder="Contoh: Luxury Intimate Wedding" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kategori Acara</label>
                            <select name="kategori" class="form-select" required>
                                <option value="Pernikahan">Pernikahan</option>
                                <option value="Ulang Tahun">Ulang Tahun</option>
                                <option value="Seminar">Seminar</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Paket (Rp)</label>
                            <input type="number" name="harga" class="form-control" placeholder="Contoh: 25000000" min="100000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Unggah File Foto Paket</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                            <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB).</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Deskripsi Layanan</label>
                            <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan apa saja rincian layanan dan fasilitas paket ini..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Upload & Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection