@extends('layouts.admin')

@section('title', 'Kelola Semua Pesanan - SB Admin EventKu')

@section('content')
<!-- Page Heading -->
<div class="mb-4">
    <h1 class="h3 mb-1 text-gray-800 fw-bold">Manajemen Semua Pesanan</h1>
    <p class="text-muted small mb-0">Kelola dan ubah tahapan pelaksanaan acara klien secara realtime.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Tabel Manajemen Pesanan SB Admin -->
<div class="card card-sb mb-4">
    <div class="card-header py-3 bg-white border-0">
        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-table me-1"></i> Daftar Lengkap Pesanan Masuk</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-4">Kode & Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Paket & Gedung</th>
                        <th>Total Biaya</th>
                        <th>Ubah Status Acara</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanans as $pesanan)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">#{{ $pesanan->kode_pesanan }}</span>
                                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ date('d M Y', strtotime($pesanan->tanggal_event)) }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $pesanan->user->name }}</span>
                                <small class="text-muted d-block">{{ $pesanan->user->telepon }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary">{{ $pesanan->paket->nama_paket }}</span>
                                <small class="text-muted d-block"><i class="bi bi-geo-alt me-1"></i>{{ $pesanan->lokasi }}</small>
                            </td>
                            <td class="fw-bold text-dark">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td style="min-width: 220px;">
                                <form action="{{ url('/admin/pesanan/' . $pesanan->id . '/status') }}" method="POST" class="d-flex gap-1 align-items-center">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm rounded-2 py-1">
                                        <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dp_paid" {{ $pesanan->status == 'dp_paid' ? 'selected' : '' }}>DP Paid</option>
                                        <option value="confirmed" {{ $pesanan->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="preparation" {{ $pesanan->status == 'preparation' ? 'selected' : '' }}>Preparation</option>
                                        <option value="done" {{ $pesanan->status == 'done' ? 'selected' : '' }}>Done</option>
                                        <option value="cancelled" {{ $pesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm py-1 px-2" title="Simpan Status">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ url('/admin/pesanan/' . $pesanan->kode_pesanan) }}" class="btn btn-outline-secondary btn-sm rounded-2">
                                    Rincian <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection