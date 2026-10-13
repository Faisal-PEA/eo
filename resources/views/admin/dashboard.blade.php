@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <!-- PAGE TITLE -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard Admin EventKu</h1>
            <p class="text-muted small mb-0">Pantau performa bisnis, verifikasi transfer, dan kelola pesanan masuk.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ================= ROW STATISTIK CARDS ================= -->
    <div class="row g-3 mb-4">
        <!-- Pendapatan Sah -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-sb border-left-primary h-100 py-2 shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Pendapatan Sah</div>
                            <div class="h5 mb-0 fw-bold text-dark">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-sb border-left-success h-100 py-2 shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Semua Pesanan</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $totalPesanan }} Pesanan</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard2-check fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Pending -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-sb border-left-warning h-100 py-2 shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Menunggu Pembayaran / DP</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pesananPending }} Pesanan</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-history fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Katalog Paket Aktif -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-sb border-left-info h-100 py-2 shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Katalog Paket Aktif</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $totalPaket }} Paket</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= ROW TABEL UTAMA ================= -->
    <div class="row g-4">
        
        <!-- 1. BUKTI TRANSFER YANG PERLU DIVERIFIKASI ADMIN -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-credit-card me-1"></i> Verifikasi Bukti Pembayaran Masuk
                    </h6>
                    <span class="badge bg-warning text-dark">{{ $pembayaranPending->count() }} Menunggu</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-secondary">
                                <tr>
                                    <th class="ps-3">Pesanan & Klien</th>
                                    <th>Nominal</th>
                                    <th>Bukti</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaranPending as $pay)
                                    @php
                                        $nominal = $pay->jumlah ?? $pay->jumlah_bayar ?? 0;
                                        $tipePay = $pay->jenis ?? $pay->tipe ?? $pay->tipe_pembayaran ?? 'DP';
                                        $fileBukti = $pay->bukti_transfer ?? $pay->bukti ?? $pay->foto ?? null;
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <strong class="text-dark d-block">#{{ $pay->pesanan->kode_pesanan ?? '-' }}</strong>
                                            <small class="text-muted">{{ $pay->pesanan->user->name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                                            <span class="badge bg-secondary-subtle text-secondary small">{{ strtoupper($tipePay) }}</span>
                                        </td>
                                        <td>
                                            @if($fileBukti)
                                                <!-- TOMBOL MODAL NATIVE BOOTSTRAP (100% PASTI BERFUNGSI) -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalBukti_{{ $pay->id }}">
                                                    <i class="bi bi-image me-1"></i> Cek Foto
                                                </button>

                                                <!-- MODAL POPUP UNIK KHUSUS ITEM INI -->
                                                <div class="modal fade" id="modalBukti_{{ $pay->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                                                            <div class="modal-header border-0 bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-1">
                                                                        {{ strtoupper($tipePay) }} - Rp {{ number_format($nominal, 0, ',', '.') }}
                                                                    </span>
                                                                    <h6 class="modal-title fw-bold text-dark mb-0">
                                                                        Bukti Pembayaran Pesanan #{{ $pay->pesanan->kode_pesanan ?? '' }}
                                                                    </h6>
                                                                </div>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-3 bg-dark text-center" style="max-height: 75vh; overflow-y: auto;">
                                                                <img src="{{ asset('uploads/bukti/' . $fileBukti) }}" 
                                                                     class="img-fluid rounded shadow" 
                                                                     style="max-height: 70vh; width: auto; object-fit: contain;" 
                                                                     alt="Bukti Transfer">
                                                            </div>
                                                            <div class="modal-footer border-0 bg-white py-2 px-4 justify-content-between">
                                                                <small class="text-muted">Klien: {{ $pay->pesanan->user->name ?? '-' }}</small>
                                                                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small">Tidak ada file</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1">
                                                <form action="{{ route('admin.pembayaran.verifikasi', $pay->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="verified">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Terima Pembayaran">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.pembayaran.verifikasi', $pay->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            <i class="bi bi-check-circle fs-3 text-success d-block mb-1"></i>
                                            Tidak ada pembayaran baru yang menunggu verifikasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. PESANAN TERBARU MASUK -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history me-1"></i> Pesanan Terbaru Masuk
                    </h6>
                    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-link text-decoration-none">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-secondary">
                                <tr>
                                    <th class="ps-3">Kode & Klien</th>
                                    <th>Paket</th>
                                    <th>Status</th>
                                    <th class="text-end pe-3">Rincian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesananTerbaru as $p)
                                    <tr>
                                        <td class="ps-3">
                                            <strong class="text-dark d-block">#{{ $p->kode_pesanan }}</strong>
                                            <small class="text-muted">{{ $p->user->name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="text-dark small fw-semibold">{{ $p->paket->nama_paket ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($p->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($p->status === 'dp_paid')
                                                <span class="badge bg-info text-dark">DP Terbayar</span>
                                            @elseif($p->status === 'confirmed')
                                                <span class="badge bg-success">Dikonfirmasi</span>
                                            @elseif($p->status === 'preparation')
                                                <span class="badge bg-primary">Persiapan</span>
                                            @elseif($p->status === 'done')
                                                <span class="badge bg-secondary">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">Batal</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.pesanan.detail', $p->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            Belum ada pesanan masuk.
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

</div>
@endsection