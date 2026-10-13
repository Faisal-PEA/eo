@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen Semua Pesanan</h1>
            <p class="text-muted small mb-0">Kelola dan ubah tahapan pelaksanaan acara klien secara realtime.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header py-3 bg-white border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-1"></i> Daftar Lengkap Pesanan Masuk
            </h6>

            <!-- Filter Status -->
            <form action="{{ route('admin.pesanan.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <label class="small text-muted mb-0">Filter:</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dp_paid" {{ request('status') == 'dp_paid' ? 'selected' : '' }}>DP Paid</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Lunas</option>
                    <option value="preparation" {{ request('status') == 'preparation' ? 'selected' : '' }}>Persiapan</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small font-weight-bold">
                        <tr>
                            <th class="ps-4 py-3">Kode & Tanggal</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Paket & Gedung</th>
                            <th class="py-3">Total Biaya</th>
                            <th class="py-3 text-center" style="width: 250px;">Ubah Status Acara</th>
                            <th class="pe-4 py-3 text-end" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanans as $pesanan)
                            @php
                                $tanggalEvent = $pesanan->tanggal_event ?? $pesanan->tanggal_acara ?? $pesanan->created_at;
                                $lokasiAcara = $pesanan->lokasi ?? $pesanan->lokasi_acara ?? '-';
                                $isLocked = in_array($pesanan->status, ['done', 'cancelled']);
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark d-block">#{{ $pesanan->kode_pesanan }}</strong>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ \Carbon\Carbon::parse($tanggalEvent)->format('d M Y') }}
                                    </small>
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold text-dark d-block">{{ $pesanan->user->name ?? '-' }}</span>
                                    <small class="text-muted">{{ $pesanan->user->email ?? '-' }}</small>
                                </td>
                                <td class="py-3">
                                    <a href="{{ route('paket.show', $pesanan->paket_id) }}" target="_blank" class="text-primary text-decoration-none fw-semibold d-block">
                                        {{ $pesanan->paket->nama_paket ?? '-' }}
                                    </a>
                                    <small class="text-muted text-truncate d-block" style="max-width: 180px;">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $lokasiAcara }}
                                    </small>
                                </td>
                                <td class="py-3">
                                    <strong class="text-dark">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="py-3 text-center">
                                    @if($isLocked)
                                        <div class="d-inline-flex align-items-center gap-1">
                                            @if($pesanan->status === 'done')
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-all me-1"></i> Selesai (Terkunci)
                                                </span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan (Terkunci)
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm" style="max-width: 160px;">
                                                <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="dp_paid" {{ $pesanan->status == 'dp_paid' ? 'selected' : '' }}>DP Paid</option>
                                                <option value="confirmed" {{ $pesanan->status == 'confirmed' ? 'selected' : '' }}>Lunas</option>
                                                <option value="preparation" {{ $pesanan->status == 'preparation' ? 'selected' : '' }}>Persiapan</option>
                                                <option value="done" {{ $pesanan->status == 'done' ? 'selected' : '' }}>Selesai</option>
                                                <option value="cancelled" {{ $pesanan->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary" title="Simpan Perubahan">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('admin.pesanan.detail', $pesanan->id) }}" class="btn btn-sm btn-outline-secondary px-3">
                                        Rincian <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Belum ada data pesanan yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pesanans->hasPages())
                <div class="p-3 border-top">
                    {{ $pesanans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection