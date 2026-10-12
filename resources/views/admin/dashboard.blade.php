@extends('layouts.admin')

@section('title', 'Dashboard Administrator - SB Admin EventKu')

@section('content')
<!-- Page Heading -->
<div class="mb-4">
    <h1 class="h3 mb-1 text-gray-800 fw-bold">Dashboard Overview</h1>
    <p class="text-muted small mb-0">Ringkasan transaksi dan status operasional event organizer.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- 4 Kartu Statistik Bergaya SB Admin 2 -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-sb border-left-success h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Pendapatan Terverifikasi</div>
                        <div class="h5 mb-0 fw-bold text-dark">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cash-stack fs-2 text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-sb border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Pesanan Acara</div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $totalPesanan }} Acara</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar2-check fs-2 text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-sb border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Perlu Verifikasi Transfer</div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $pembayaranPending->count() }} Transaksi</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-hourglass-split fs-2 text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-sb border-left-info h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Katalog Paket Aktif</div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $totalPaket }} Paket</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box-seam fs-2 text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verifikasi Bukti Pembayaran -->
<div class="card card-sb mb-4">
    <div class="card-header py-3 bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">
            <i class="bi bi-patch-check me-1"></i> Verifikasi Bukti Struk Pembayaran Masuk
        </h6>
        <span class="badge bg-warning text-dark">{{ $pembayaranPending->count() }} Menunggu Validasi</span>
    </div>
    <div class="card-body p-0">
        @if($pembayaranPending->isEmpty())
            <div class="text-center py-5 text-muted small">
                <i class="bi bi-check2-all text-success fs-1 d-block mb-2"></i>
                Tidak ada pembayaran yang menunggu verifikasi saat ini.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-xs text-uppercase">
                        <tr>
                            <th class="ps-4">Pemesan</th>
                            <th>Pesanan & Acara</th>
                            <th>Jenis & Bank</th>
                            <th>Jumlah Transfer</th>
                            <th>Struk Bukti</th>
                            <th class="text-end pe-4">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaranPending as $pay)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">{{ $pay->pesanan->user->name }}</span>
                                    <small class="text-muted">{{ $pay->pesanan->user->telepon }}</small>
                                </td>
                                <td>
                                    <span class="text-primary fw-semibold">#{{ $pay->pesanan->kode_pesanan }}</span>
                                    <small class="text-muted d-block">{{ $pay->pesanan->paket->nama_paket }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-uppercase mb-1">{{ $pay->jenis }}</span>
                                    <div class="small fw-semibold">{{ $pay->metode }}</div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($pay->jumlah, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <a href="{{ asset('uploads/bukti/' . $pay->bukti_transfer) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-image me-1"></i> Periksa Struk
                                    </a>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ url('/admin/pembayaran/' . $pay->id . '/verifikasi') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="verified">
                                            <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm" onclick="return confirm('Apakah bukti transfer ini valid dan Anda ingin menerimanya?');">
                                                <i class="bi bi-check-lg me-1"></i> Terima
                                            </button>
                                        </form>
                                        <form action="{{ url('/admin/pembayaran/' . $pay->id . '/verifikasi') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?');">
                                                <i class="bi bi-x-lg me-1"></i> Tolak
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

<!-- 5 Pesanan Acara Terbaru -->
<div class="card card-sb mb-4">
    <div class="card-header py-3 bg-white border-0">
        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-clock-history me-1"></i> 5 Pesanan Acara Terbaru</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-4">Kode & Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Paket Acara</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesananTerbaru as $p)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">#{{ $p->kode_pesanan }}</span>
                                <small class="text-muted">{{ date('d M Y', strtotime($p->tanggal_event)) }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $p->user->name }}</span>
                                <small class="text-muted d-block">{{ $p->user->telepon }}</small>
                            </td>
                            <td>{{ $p->paket->nama_paket }}</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">{{ strtoupper($p->status) }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ url('/admin/pesanan/' . $p->kode_pesanan) }}" class="btn btn-outline-secondary btn-sm">
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