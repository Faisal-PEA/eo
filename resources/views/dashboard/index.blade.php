@extends('layouts.app')

@section('title', 'Dashboard Pesanan Saya - EventKu')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-muted">Kelola dan pantau status pesanan acara Anda di sini.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ url('/paket') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle me-1"></i> Pesan Event Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pemesanan Acara</h5>
        </div>
        <div class="card-body p-0">
            @if($pesanans->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3.5rem;"></i>
                    <h5 class="fw-bold mt-3 text-secondary">Belum Ada Pesanan Acara</h5>
                    <p class="text-muted small">Anda belum melakukan pemesanan paket event organizer apa pun.</p>
                    <a href="{{ url('/paket') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        Jelajahi Paket Event
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Kode & Tanggal Event</th>
                                <th>Paket Dipilih</th>
                                <th>Lokasi & Tamu</th>
                                <th>Total Biaya</th>
                                <th>Status Pesanan</th>
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
                                        <span class="fw-semibold text-primary">{{ $pesanan->paket->nama_paket }}</span>
                                        <small class="text-muted d-block">{{ $pesanan->pesananAddons->count() }} Add-on Tambahan</small>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ $pesanan->lokasi }}</span>
                                        <small class="text-muted"><i class="bi bi-people me-1"></i>{{ $pesanan->jumlah_tamu }} Tamu</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if($pesanan->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Pembayaran</span>
                                        @elseif($pesanan->status == 'dp_paid')
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">DP Terbayar (50%)</span>
                                        @elseif($pesanan->status == 'confirmed')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Dikonfirmasi</span>
                                        @elseif($pesanan->status == 'preparation')
                                            <span class="badge bg-primary px-3 py-2 rounded-pill">Dalam Persiapan</span>
                                        @elseif($pesanan->status == 'done')
                                            <span class="badge bg-dark px-3 py-2 rounded-pill">Acara Selesai</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group gap-1">
                                            <!-- Tombol Detail / Bayar -->
                                            <a href="{{ url('/pesanan/' . $pesanan->kode_pesanan) }}" class="btn btn-outline-primary btn-sm rounded-3" title="Lihat Rincian & Bayar">
                                                <i class="bi bi-receipt"></i> Detail
                                            </a>

                                            <!-- Tombol Edit -->
                                            @if(!in_array($pesanan->status, ['preparation', 'done']))
                                                <a href="{{ url('/pesanan/' . $pesanan->kode_pesanan . '/edit') }}" class="btn btn-outline-warning btn-sm rounded-3 text-dark" title="Ubah Pesanan">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                            @endif

                                            <!-- Tombol Hapus -->
                                            <form action="{{ url('/pesanan/' . $pesanan->kode_pesanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3" title="Hapus Pesanan">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection