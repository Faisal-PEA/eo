@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- BREADCRUMB & HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none" style="color:#249D8F;">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pesanan.index') }}" class="text-decoration-none" style="color:#249D8F;">Kelola Pesanan</a></li>
                    <li class="breadcrumb-item active">Detail Pesanan</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 fw-bold text-dark">
                Detail Pesanan <span style="color:#249D8F;">#{{ $pesanan->kode_pesanan }}</span>
            </h1>
        </div>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-4">
            <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-4">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">

        <!-- ==================== KOLOM KIRI: DETAIL PESANAN ==================== -->
        <div class="col-lg-8">

            <!-- INFO PESANAN -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header py-3 px-4 text-white d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #249D8F, #1A6B62);">
                    <div>
                        <span class="fw-bold fs-6"><i class="bi bi-file-earmark-text me-2"></i>Invoice Pesanan</span>
                        <div class="small text-white-50">Dibuat: {{ $pesanan->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                    @php
                        $statusConfig = [
                            'pending'     => ['bg-warning text-dark', 'hourglass-split', 'Menunggu Pembayaran'],
                            'dp_paid'     => ['bg-info text-dark',    'wallet2',          'DP Paid'],
                            'confirmed'   => ['bg-success',           'check-circle-fill','Lunas / Terkonfirmasi'],
                            'preparation' => ['bg-primary',           'gear-fill',        'Dalam Persiapan'],
                            'done'        => ['bg-success',           'patch-check-fill', 'Selesai'],
                            'cancelled'   => ['bg-danger',            'x-circle-fill',    'Dibatalkan'],
                        ];
                        $sc = $statusConfig[$pesanan->status] ?? ['bg-secondary', 'question-circle', $pesanan->status];
                    @endphp
                    <span class="badge {{ $sc[0] }} px-3 py-2 rounded-pill fs-6">
                        <i class="bi bi-{{ $sc[1] }} me-1"></i> {{ $sc[2] }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <!-- INFO CUSTOMER & PAKET -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-person-fill me-1" style="color:#249D8F;"></i> Nama Customer</small>
                                <span class="fw-bold text-dark">{{ $pesanan->user->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-envelope-fill me-1" style="color:#249D8F;"></i> Email</small>
                                <span class="fw-bold text-dark">{{ $pesanan->user->email ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-box-seam me-1" style="color:#249D8F;"></i> Paket Acara</small>
                                <span class="fw-bold text-dark">{{ $pesanan->paket->nama_paket ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-calendar-event me-1" style="color:#249D8F;"></i> Tanggal Acara</small>
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-geo-alt-fill me-1" style="color:#249D8F;"></i> Lokasi Acara</small>
                                <span class="fw-bold text-dark">{{ $pesanan->lokasi_acara ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-people-fill me-1" style="color:#249D8F;"></i> Estimasi Tamu</small>
                                <span class="fw-bold text-dark">{{ $pesanan->jumlah_tamu ?? 0 }} Orang</span>
                            </div>
                        </div>
                    </div>

                    <!-- ADD-ONS -->
                    @if($pesanan->addons && $pesanan->addons->count() > 0)
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle me-2" style="color:#249D8F;"></i>Layanan Tambahan (Add-On)</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered align-middle rounded-3 overflow-hidden">
                            <thead style="background-color:#E6F5F3; color:#1A6B62;">
                                <tr>
                                    <th>Nama Layanan</th>
                                    <th class="text-center">Jml</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->addons as $addon)
                                <tr>
                                    <td class="fw-semibold">{{ $addon->nama_addon }}</td>
                                    <td class="text-center">{{ $addon->pivot->jumlah ?? 1 }}</td>
                                    <td class="text-end">Rp {{ number_format($addon->pivot->harga ?? $addon->harga, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold" style="color:#249D8F;">
                                        Rp {{ number_format(($addon->pivot->harga ?? $addon->harga) * ($addon->pivot->jumlah ?? 1), 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- RINGKASAN PEMBAYARAN -->
                    @php
                        $totalBiaya   = (float) ($pesanan->total_biaya ?? $pesanan->total_harga ?? 0);
                        $sudahDibayar = (float) $pesanan->pembayarans->where('status', 'verified')->sum(fn($p) => $p->jumlah ?? $p->jumlah_bayar ?? 0);
                        $sisaTagihan  = max(0, $totalBiaya - $sudahDibayar);
                    @endphp
                    <div class="p-4 rounded-3 border" style="background: linear-gradient(135deg, #F0FDF9, #E6F5F3);">
                        <h6 class="fw-bold mb-3" style="color:#1A6B62;"><i class="bi bi-cash-stack me-2"></i>Ringkasan Keuangan</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fw-semibold">Total Nilai Pesanan:</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success fw-semibold"><i class="bi bi-check2-circle me-1"></i>Total Sudah Dibayar (Sah):</span>
                            <span class="fw-bold text-success">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2 border-0" style="border-top: 1px dashed #249D8F !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Sisa Tagihan:</span>
                            @if($sisaTagihan <= 0)
                                <span class="badge bg-success px-3 py-2 rounded-pill fs-6">✅ LUNAS SEPENUHNYA</span>
                            @else
                                <span class="fw-bold fs-5 text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== RIWAYAT PEMBAYARAN ==================== -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-clock-history me-2" style="color:#249D8F;"></i>Riwayat Pembayaran Customer
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($pesanan->pembayarans as $bayar)
                    @php
                        $isManual   = $bayar->bukti_transfer && $bayar->bukti_transfer !== 'midtrans_auto';
                        $isMidtrans = $bayar->bukti_transfer === 'midtrans_auto';
                        $jenis = strtolower($bayar->jenis ?? $bayar->tipe ?? $bayar->tipe_pembayaran ?? 'dp');
                        $nominal = $bayar->jumlah ?? $bayar->jumlah_bayar ?? 0;
                    @endphp
                    <div class="p-4 border-bottom">
                        <div class="row align-items-start g-3">

                            <!-- KOLOM KIRI: INFO TRANSAKSI -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($jenis === 'dp')
                                        <span class="badge bg-info text-dark rounded-pill px-3">DP (30%)</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3">Pelunasan</span>
                                    @endif

                                    @if($bayar->status === 'verified')
                                        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2">
                                            <i class="bi bi-check2"></i> Terverifikasi
                                        </span>
                                    @elseif($bayar->status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-2">
                                            <i class="bi bi-x"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-2">
                                            <i class="bi bi-clock"></i> Menunggu Verifikasi
                                        </span>
                                    @endif
                                </div>

                                <div class="fw-bold fs-5 mb-1" style="color:#249D8F;">
                                    Rp {{ number_format($nominal, 0, ',', '.') }}
                                </div>

                                <small class="text-muted d-block">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $bayar->created_at ? $bayar->created_at->translatedFormat('d F Y') : '-' }}
                                    &nbsp;|&nbsp;
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $bayar->created_at ? $bayar->created_at->format('H:i') : '-' }} WIB
                                </small>

                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-credit-card me-1"></i>
                                    {{ $bayar->metode ?? ($isMidtrans ? 'Payment Gateway (Midtrans)' : 'Transfer Bank Manual') }}
                                </small>
                            </div>

                            <!-- KOLOM KANAN: BUKTI PEMBAYARAN -->
                            <div class="col-md-6 text-md-end">

                                @if($isMidtrans)
                                    {{-- ========= PAYMENT GATEWAY (MIDTRANS) ========= --}}
                                    <div class="d-inline-flex flex-column align-items-center p-3 rounded-3 border"
                                         style="background: linear-gradient(135deg, #E6F5F3, #F0FDF9); border-color: rgba(36,157,143,0.3) !important; min-width: 180px;">
                                        <div class="mb-2">
                                            <img src="https://assets.midtrans.com/assets/images/midtrans-logo-white.svg"
                                                 onerror="this.style.display='none'"
                                                 height="20" alt="Midtrans" class="me-1">
                                            <span class="fw-bold small" style="color:#249D8F;">Midtrans Snap</span>
                                        </div>
                                        <div class="badge bg-success px-3 py-2 rounded-pill mb-1">
                                            <i class="bi bi-shield-check-fill me-1"></i> Otomatis Terverifikasi
                                        </div>
                                        <small class="text-muted" style="font-size:0.75rem;">
                                            Pembayaran diproses &amp; dikonfirmasi<br>secara otomatis via Payment Gateway
                                        </small>
                                    </div>

                                @elseif($isManual)
                                    {{-- ========= TRANSFER MANUAL (ADA FOTO BUKTI) ========= --}}
                                    <div class="d-inline-flex flex-column align-items-center gap-2">
                                        {{-- Preview Thumbnail --}}
                                        <div class="position-relative" style="cursor:pointer;"
                                             data-bs-toggle="modal" data-bs-target="#modalBukti{{ $bayar->id }}"
                                             title="Klik untuk perbesar">
                                            <img src="{{ asset('uploads/bukti/' . $bayar->bukti_transfer) }}"
                                                 alt="Bukti Transfer"
                                                 class="rounded-3 border shadow-sm"
                                                 style="width:120px; height:90px; object-fit:cover; transition: transform 0.2s;"
                                                 onmouseover="this.style.transform='scale(1.05)'"
                                                 onmouseout="this.style.transform='scale(1)'">
                                            <span class="position-absolute top-50 start-50 translate-middle
                                                         badge rounded-circle bg-dark bg-opacity-50 p-2"
                                                  style="pointer-events:none;">
                                                <i class="bi bi-zoom-in text-white fs-6"></i>
                                            </span>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-teal rounded-pill px-3"
                                                data-bs-toggle="modal" data-bs-target="#modalBukti{{ $bayar->id }}">
                                            <i class="bi bi-image me-1"></i> Lihat Bukti Transfer
                                        </button>
                                    </div>

                                    {{-- MODAL PREVIEW FULL --}}
                                    <div class="modal fade" id="modalBukti{{ $bayar->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md">
                                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                                <div class="modal-header border-0 pb-0 px-4 pt-4">
                                                    <div>
                                                        <h6 class="modal-title fw-bold text-dark mb-1">
                                                            <i class="bi bi-image me-2" style="color:#249D8F;"></i>Bukti Transfer Manual
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ $bayar->created_at ? $bayar->created_at->translatedFormat('d F Y, H:i') : '' }} WIB
                                                        </small>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center px-4 pb-2">
                                                    <img src="{{ asset('uploads/bukti/' . $bayar->bukti_transfer) }}"
                                                         alt="Bukti Transfer"
                                                         class="img-fluid rounded-3 border shadow-sm mb-3"
                                                         style="max-height: 420px;">
                                                    <div class="fw-bold fs-5 mb-1" style="color:#249D8F;">
                                                        Rp {{ number_format($nominal, 0, ',', '.') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $bayar->metode ?? 'Transfer Bank' }}
                                                        &nbsp;•&nbsp;
                                                        {{ $jenis === 'dp' ? 'DP (30%)' : 'Pelunasan' }}
                                                    </small>
                                                </div>
                                                <div class="modal-footer border-0 justify-content-between px-4 pb-4">
                                                    @if($bayar->status === 'pending')
                                                    <div class="d-flex gap-2 w-100">
                                                        <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST" class="flex-fill">
                                                            @csrf
                                                            <input type="hidden" name="status" value="verified">
                                                            <button type="submit" class="btn btn-success w-100 rounded-pill">
                                                                <i class="bi bi-check-circle me-1"></i> Terima
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST" class="flex-fill">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                                                                <i class="bi bi-x-circle me-1"></i> Tolak
                                                            </button>
                                                        </form>
                                                    </div>
                                                    @else
                                                    <button class="btn btn-secondary rounded-pill w-100" data-bs-dismiss="modal">Tutup</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    {{-- TIDAK ADA BUKTI --}}
                                    <div class="p-3 rounded-3 bg-light border text-muted small text-center">
                                        <i class="bi bi-image-alt d-block fs-3 mb-1 opacity-25"></i>
                                        Belum ada bukti pembayaran
                                    </div>
                                @endif

                            </div>
                        </div>

                        {{-- TOMBOL VERIFIKASI (HANYA UNTUK TRANSFER MANUAL & STATUS PENDING) --}}
                        @if($isManual && $bayar->status === 'pending')
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex gap-2 flex-wrap">
                                <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="btn btn-success rounded-pill px-4">
                                        <i class="bi bi-check-circle-fill me-1"></i> Terima Bukti Transfer
                                    </button>
                                </form>
                                <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                        <i class="bi bi-x-circle me-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-receipt display-3 opacity-25 d-block mb-3"></i>
                        <p class="mb-0">Klien belum melakukan pembayaran apapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ==================== KOLOM KANAN: KONTROL ADMIN ==================== -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-gear-fill me-2" style="color:#249D8F;"></i>Kontrol Admin
                    </h6>
                </div>
                <div class="card-body p-4">

                    <!-- UBAH STATUS PESANAN -->
                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Ubah Status Acara</label>
                    <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select rounded-3 mb-3" required>
                            <option value="pending"     {{ $pesanan->status === 'pending'     ? 'selected' : '' }}>⏳ Menunggu Pembayaran</option>
                            <option value="dp_paid"     {{ $pesanan->status === 'dp_paid'     ? 'selected' : '' }}>💳 DP Paid</option>
                            <option value="confirmed"   {{ $pesanan->status === 'confirmed'   ? 'selected' : '' }}>✅ Lunas / Terkonfirmasi</option>
                            <option value="preparation" {{ $pesanan->status === 'preparation' ? 'selected' : '' }}>⚙️ Dalam Persiapan</option>
                            <option value="done"        {{ $pesanan->status === 'done'        ? 'selected' : '' }}>🎉 Selesai / Done</option>
                            <option value="cancelled"   {{ $pesanan->status === 'cancelled'   ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn w-100 rounded-pill fw-bold text-white py-2"
                                style="background: linear-gradient(135deg, #249D8F, #1A6B62);">
                            <i class="bi bi-arrow-repeat me-1"></i> Simpan Perubahan Status
                        </button>
                    </form>

                    <hr class="my-4">

                    <!-- RINGKASAN STATUS PEMBAYARAN -->
                    <label class="form-label fw-bold small text-muted text-uppercase mb-3">Ringkasan Pembayaran</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                            <span class="small text-muted">Total Tagihan</span>
                            <span class="fw-bold text-dark small">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background:#E6F5F3;">
                            <span class="small text-muted">Sudah Terbayar</span>
                            <span class="fw-bold text-success small">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                            <span class="small fw-bold text-dark">Sisa Tagihan</span>
                            @if($sisaTagihan <= 0)
                                <span class="badge bg-success rounded-pill px-2 py-1 small">LUNAS</span>
                            @else
                                <span class="fw-bold text-danger small">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- STATISTIK PEMBAYARAN -->
                    <label class="form-label fw-bold small text-muted text-uppercase mb-3">Statistik Pembayaran</label>
                    @php
                        $countManual   = $pesanan->pembayarans->filter(fn($p) => $p->bukti_transfer && $p->bukti_transfer !== 'midtrans_auto')->count();
                        $countMidtrans = $pesanan->pembayarans->filter(fn($p) => $p->bukti_transfer === 'midtrans_auto')->count();
                        $countPending  = $pesanan->pembayarans->where('status', 'pending')->count();
                    @endphp
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                            <span class="small"><i class="bi bi-upload me-2 text-primary"></i>Transfer Manual</span>
                            <span class="badge bg-primary rounded-pill">{{ $countManual }} transaksi</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                            <span class="small"><i class="bi bi-shield-check me-2 text-success"></i>Payment Gateway</span>
                            <span class="badge bg-success rounded-pill">{{ $countMidtrans }} transaksi</span>
                        </div>
                        @if($countPending > 0)
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background:#FFF8E7;">
                            <span class="small"><i class="bi bi-clock-history me-2 text-warning"></i>Menunggu Verifikasi</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $countPending }} pending</span>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection