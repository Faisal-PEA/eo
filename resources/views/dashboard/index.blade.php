@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        
        <!-- HEADER DASHBOARD CUSTOMER -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <span class="badge px-3 py-2 rounded-pill fw-bold mb-2" 
                      style="background-color: #E6F5F3; color: #249D8F; border: 1px solid rgba(36, 157, 143, 0.25);">
                    <i class="bi bi-person-check-fill me-1"></i> Customer Area
                </span>
                <h2 class="fw-extrabold text-dark mb-0">Pesanan Saya</h2>
                <p class="text-muted small mb-0">Pantau status perencanaan dan riwayat pembayaran acara Anda</p>
            </div>
            <div>
                <a href="{{ route('paket.index') }}" class="btn btn-teal">
                    <i class="bi bi-plus-circle me-1"></i> Pesan Acara Baru
                </a>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #E6F5F3; width: 50px; height: 50px;">
                            <i class="bi bi-box-seam-fill text-teal fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Total Pesanan</span>
                            <h4 class="fw-bold text-dark mb-0">{{ $pesanans->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-warning bg-opacity-10" style="width: 50px; height: 50px;">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Menunggu Pembayaran</span>
                            <h4 class="fw-bold text-dark mb-0">{{ $pesanans->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-success bg-opacity-10" style="width: 50px; height: 50px;">
                            <i class="bi bi-check2-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Terkonfirmasi</span>
                            <h4 class="fw-bold text-dark mb-0">{{ $pesanans->whereIn('status', ['confirmed', 'preparation', 'done'])->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DAFTAR PESANAN -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #E6F5F3; color: #134B45;">
                            <tr>
                                <th class="ps-4 py-3">Kode & Tanggal</th>
                                <th class="py-3">Paket Acara</th>
                                <th class="py-3">Lokasi & Tamu</th>
                                <th class="py-3">Total Biaya</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="pe-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $pesanan)
                                @php
                                    // Mengambil koleksi addons secara aman tanpa error null
                                    $addonItems = $pesanan->addons ?? $pesanan->pesananAddons ?? collect();
                                    $tanggalEvent = $pesanan->tanggal_event ?? $pesanan->tanggal_acara ?? $pesanan->created_at;
                                    $lokasiAcara = $pesanan->lokasi ?? $pesanan->lokasi_acara ?? '-';
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
                                        <span class="fw-semibold text-dark d-block">{{ $pesanan->paket->nama_paket ?? '-' }}</span>
                                        <small class="text-teal">
                                            {{ $addonItems->count() }} Add-on Tambahan
                                        </small>
                                    </td>
                                    <td class="py-3">
                                        <span class="d-block text-dark small fw-semibold text-truncate" style="max-width: 200px;">
                                            <i class="bi bi-geo-alt text-danger me-1"></i>{{ $lokasiAcara }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-people me-1"></i>{{ $pesanan->jumlah_tamu }} Tamu
                                        </small>
                                    </td>
                                    <td class="py-3">
                                        <strong class="text-teal">
                                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($pesanan->status === 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill small">Menunggu Pembayaran</span>
                                        @elseif($pesanan->status === 'dp_paid')
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill small">DP Terbayar</span>
                                        @elseif($pesanan->status === 'confirmed')
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill small">Terkonfirmasi</span>
                                        @elseif($pesanan->status === 'preparation')
                                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill small">Dalam Persiapan</span>
                                        @elseif($pesanan->status === 'done')
                                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill small">Selesai</span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill small">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-teal px-3" title="Lihat Invoice & Bukti Bayar">
                                                <i class="bi bi-receipt me-1"></i> Invoice
                                            </a>
                                            @if($pesanan->status === 'pending')
                                                <form action="{{ route('pesanan.destroy', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Pesanan">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-journal-x display-3 text-muted opacity-25"></i>
                                            <h5 class="fw-bold text-dark mt-3 mb-1">Belum Ada Riwayat Pesanan</h5>
                                            <p class="text-muted small">Anda belum melakukan pemesanan paket acara apapun.</p>
                                            <a href="{{ route('paket.index') }}" class="btn btn-teal mt-2">
                                                Jelajahi Paket Acara Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection