@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">

        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-teal text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-teal text-decoration-none">Pesanan Saya</a></li>
                <li class="breadcrumb-item active text-muted">Invoice #{{ $pesanan->kode_pesanan }}</li>
            </ol>
        </nav>

        <!-- ALERT NOTIFIKASI -->
        @if(session('success'))
            <div class="alert border-0 shadow-sm rounded-3 py-3 px-4 mb-4 d-flex align-items-center gap-3"
                 style="background:#E6F5F3; color:#1A6B62;">
                <i class="bi bi-check-circle-fill fs-4"></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 px-4 mb-4">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 px-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">

            <!-- KOLOM KIRI: DETAIL INVOICE -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">

                    <!-- HEADER INVOICE -->
                    <div class="p-4 p-md-5 text-white d-flex flex-wrap justify-content-between align-items-center gap-3"
                         style="background: linear-gradient(135deg, #249D8F, #1A6B62);">
                        <div>
                            <span class="badge bg-white px-3 py-2 rounded-pill fw-bold mb-2 shadow-sm"
                                  style="color:#249D8F;">Invoice Resmi EventKu</span>
                            <h2 class="fw-bold mb-1">#{{ $pesanan->kode_pesanan }}</h2>
                            <p class="mb-0 text-white-50 small">
                                Dipesan pada {{ $pesanan->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div class="text-md-end">
                            <span class="text-white-50 small d-block mb-1">Status Pesanan</span>
                            @if($pesanan->status === 'confirmed')
                                <span class="badge bg-success px-3 py-2 rounded-pill fs-6 fw-bold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Lunas
                                </span>
                            @elseif($pesanan->status === 'dp_paid')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill fs-6 fw-bold">
                                    <i class="bi bi-wallet2 me-1"></i> DP Paid
                                </span>
                            @elseif($pesanan->status === 'preparation')
                                <span class="badge bg-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                                    <i class="bi bi-gear-fill me-1"></i> Dalam Persiapan
                                </span>
                            @elseif($pesanan->status === 'done')
                                <span class="badge bg-success px-3 py-2 rounded-pill fs-6 fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Selesai
                                </span>
                            @elseif($pesanan->status === 'cancelled')
                                <span class="badge bg-danger px-3 py-2 rounded-pill fs-6 fw-bold">
                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6 fw-bold">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Pembayaran
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        <!-- DETAIL ACARA -->
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-calendar-event me-2" style="color:#249D8F;"></i>Detail Acara
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <small class="text-muted d-block mb-1">Paket Acara</small>
                                    <span class="fw-bold text-dark">{{ $pesanan->paket->nama_paket ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <small class="text-muted d-block mb-1">Harga Paket</small>
                                    <span class="fw-bold fs-6" style="color:#249D8F;">
                                        Rp {{ number_format($pesanan->paket->harga ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <small class="text-muted d-block mb-1">Tanggal Pelaksanaan</small>
                                    <span class="fw-bold text-dark">
                                        {{ \Carbon\Carbon::parse($pesanan->tanggal_event ?? $pesanan->tanggal_acara)->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <small class="text-muted d-block mb-1">Lokasi Acara</small>
                                    <span class="fw-bold text-dark">{{ $pesanan->lokasi ?? $pesanan->lokasi_acara ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <small class="text-muted d-block mb-1">Estimasi Tamu</small>
                                    <span class="fw-bold text-dark">{{ $pesanan->jumlah_tamu ?? 0 }} Orang</span>
                                </div>
                            </div>
                            @if($pesanan->catatan)
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-light border">
                                    <small class="text-muted d-block mb-1">Catatan Khusus</small>
                                    <span class="fw-semibold text-dark">{{ $pesanan->catatan }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- LAYANAN TAMBAHAN (ADD-ONS) -->
                        @php
                            $hargaPaket  = (float) ($pesanan->paket->harga ?? 0);
                            $totalAddon  = 0;
                        @endphp

                        @if($pesanan->addons && $pesanan->addons->count() > 0)
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-plus-circle me-2" style="color:#249D8F;"></i>Layanan Tambahan (Add-On)
                        </h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle">
                                <thead style="background-color:#E6F5F3; color:#1A6B62;">
                                    <tr>
                                        <th>Nama Layanan</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesanan->addons as $addon)
                                    @php
                                        // qty dari pivot, fallback 1
                                        $qty = (int) ($addon->pivot->qty ?? 1);
                                        // harga satuan DARI TABEL addons (bukan pivot, karena pivot hanya simpan subtotal)
                                        $hargaSatuan = (float) ($addon->harga ?? 0);
                                        // subtotal: ambil dari pivot jika ada, atau hitung ulang
                                        $subtotal = (float) ($addon->pivot->subtotal ?? ($hargaSatuan * $qty));
                                        $totalAddon += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $addon->nama_addon }}</td>
                                        <td class="text-center">{{ $qty }}</td>
                                        <td class="text-end">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold" style="color:#249D8F;">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold text-muted">Total Layanan Tambahan:</td>
                                        <td class="text-end fw-bold" style="color:#249D8F;">
                                            Rp {{ number_format($totalAddon, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold text-muted">Harga Paket:</td>
                                        <td class="text-end fw-bold" style="color:#249D8F;">
                                            Rp {{ number_format($hargaPaket, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-bold text-dark">Total Keseluruhan:</td>
                                        <td class="text-end fw-bold fs-5" style="color:#249D8F;">
                                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endif

                        <!-- TRANSPARANSI PEMBAYARAN -->
                        @php
                            $totalBiaya   = (float) ($pesanan->total_harga ?? 0);
                            $sudahDibayar = (float) $pesanan->pembayarans->where('status', 'verified')->sum(function($p) {
                                return $p->jumlah ?? $p->jumlah_bayar ?? 0;
                            });
                            $sisaTagihan  = max(0, $totalBiaya - $sudahDibayar);
                            $nominalDp    = round($totalBiaya * 0.3);
                        @endphp

                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-cash-stack me-2" style="color:#249D8F;"></i>Transparansi Pembayaran
                        </h5>
                        <div class="rounded-3 p-4 mb-4 border" style="background: linear-gradient(135deg, #F0FDF9, #E6F5F3);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-semibold">Total Nilai Pesanan:</span>
                                <span class="fw-bold fs-5 text-dark">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-success fw-semibold">
                                    <i class="bi bi-check2-circle me-1"></i>Total Telah Dibayar (Sah):
                                </span>
                                <span class="fw-bold fs-5 text-success">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-2" style="border-color: rgba(36,157,143,0.3);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Sisa Tagihan:</span>
                                @if($sisaTagihan <= 0)
                                    <span class="badge bg-success px-3 py-2 rounded-pill fs-6 fw-bold">✅ LUNAS SEPENUHNYA</span>
                                @else
                                    <span class="fw-extrabold fs-4 text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- RIWAYAT PEMBAYARAN -->
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-clock-history me-2" style="color:#249D8F;"></i>Riwayat Pembayaran
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Tipe</th>
                                        <th>Metode</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pesanan->pembayarans as $bayar)
                                    @php
                                        $jenisBayar = strtolower($bayar->jenis ?? $bayar->tipe ?? 'dp');
                                        $nominalBayar = $bayar->jumlah ?? $bayar->jumlah_bayar ?? 0;
                                        $isGateway = $bayar->bukti_transfer === 'midtrans_auto';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-bold small">{{ $bayar->created_at->translatedFormat('d M Y') }}</div>
                                            <small class="text-muted">{{ $bayar->created_at->format('H:i') }} WIB</small>
                                        </td>
                                        <td>
                                            @if($jenisBayar === 'dp')
                                                <span class="badge bg-info text-dark rounded-pill px-2">DP (30%)</span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-2">Pelunasan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted fw-semibold">
                                                {{ $bayar->metode ?? ($isGateway ? 'Midtrans' : 'Transfer Bank') }}
                                            </small>
                                        </td>
                                        <td class="text-end fw-bold" style="color:#249D8F;">
                                            Rp {{ number_format($nominalBayar, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($bayar->status === 'verified')
                                                <span class="badge bg-success rounded-pill px-2">
                                                    <i class="bi bi-check2"></i> Terverifikasi
                                                </span>
                                            @elseif($bayar->status === 'rejected')
                                                <span class="badge bg-danger rounded-pill px-2">
                                                    <i class="bi bi-x"></i> Ditolak
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-2">
                                                    <i class="bi bi-clock"></i> Menunggu Verifikasi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isGateway)
                                                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                                                    <i class="bi bi-shield-check" style="color:#249D8F;"></i> Midtrans
                                                </span>
                                            @elseif($bayar->bukti_transfer)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBukti{{ $bayar->id }}">
                                                    <i class="bi bi-image me-1"></i> Lihat
                                                </button>
                                                <!-- MODAL BUKTI -->
                                                <div class="modal fade" id="modalBukti{{ $bayar->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0 shadow">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h6 class="modal-title fw-bold">Bukti Transfer</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-center p-4">
                                                                <img src="{{ asset('uploads/bukti/' . $bayar->bukti_transfer) }}"
                                                                     class="img-fluid rounded-3 shadow-sm border mb-3"
                                                                     style="max-height: 400px;"
                                                                     alt="Bukti Transfer">
                                                                <div class="fw-bold fs-5" style="color:#249D8F;">
                                                                    Rp {{ number_format($nominalBayar, 0, ',', '.') }}
                                                                </div>
                                                                <small class="text-muted">
                                                                    {{ $bayar->created_at->translatedFormat('d F Y, H:i') }} WIB
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pembayaran.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: FORM PEMBAYARAN -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-credit-card-2-front me-2" style="color:#249D8F;"></i>Form Pembayaran
                    </h5>

                    @if($sisaTagihan <= 0)
                        <div class="text-center py-4">
                            <div class="display-1 mb-3 text-success"><i class="bi bi-patch-check-fill"></i></div>
                            <h4 class="fw-bold text-dark">Pesanan Lunas!</h4>
                            <p class="text-muted small">Semua tagihan sudah terbayar. Tim kami siap melaksanakan acara Anda!</p>
                        </div>
                    @else
                        <!-- PILIH TIPE PEMBAYARAN -->
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Tipe Pembayaran:</label>
                        <div class="d-flex flex-column gap-2 mb-4">
                            @if($pesanan->status === 'pending')
                                <div class="form-check p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <input class="form-check-input ms-0 me-2" type="radio" name="tipe_pembayaran" id="tipe_dp" value="dp" checked>
                                        <label class="form-check-label fw-bold text-dark" for="tipe_dp">Bayar DP (30%)</label>
                                    </div>
                                    <span class="fw-bold" style="color:#249D8F;">Rp {{ number_format($nominalDp, 0, ',', '.') }}</span>
                                </div>
                                <div class="form-check p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <input class="form-check-input ms-0 me-2" type="radio" name="tipe_pembayaran" id="tipe_lunas" value="lunas">
                                        <label class="form-check-label fw-bold text-dark" for="tipe_lunas">Bayar Lunas (100%)</label>
                                    </div>
                                    <span class="fw-bold" style="color:#249D8F;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <div class="form-check p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <input class="form-check-input ms-0 me-2" type="radio" name="tipe_pembayaran" id="tipe_pelunasan" value="pelunasan" checked>
                                        <label class="form-check-label fw-bold text-dark" for="tipe_pelunasan">Pelunasan Sisa</label>
                                    </div>
                                    <span class="fw-bold text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- TOMBOL MIDTRANS -->
                        <div class="mb-4 text-center">
                            <button type="button" id="btnPayMidtrans"
                                    class="btn w-100 py-3 rounded-pill fw-bold text-white shadow-sm mb-2"
                                    style="background: linear-gradient(135deg, #249D8F, #1A6B62);">
                                <i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang via Midtrans
                            </button>
                            <small class="text-muted">
                                <i class="bi bi-lightning-charge-fill text-warning"></i>
                                Otomatis & Cepat (QRIS, VA BCA, Mandiri, BNI, BRI)
                            </small>
                        </div>

                        <div class="position-relative text-center my-3">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small fw-bold">ATAU</span>
                        </div>

                        <!-- FORM TRANSFER MANUAL -->
                        <div class="accordion accordion-flush" id="accordionManual">
                            <div class="accordion-item border rounded-3 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold text-dark bg-light py-2"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseManual">
                                        <i class="bi bi-upload me-2" style="color:#249D8F;"></i> Transfer Bank Manual
                                    </button>
                                </h2>
                                <div id="collapseManual" class="accordion-collapse collapse" data-bs-parent="#accordionManual">
                                    <div class="accordion-body p-3">
                                        <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                                            <strong>Rekening Resmi EventKu:</strong><br>
                                            • BCA: <code>123-456-7890</code> a.n EventKu<br>
                                            • Mandiri: <code>987-654-3210</code> a.n EventKu
                                        </div>
                                        <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Tipe Pembayaran</label>
                                                <select id="tipeManual" name="tipe_pembayaran" class="form-select rounded-3" required>
                                                    @if($pesanan->status === 'pending')
                                                        <option value="dp">Pembayaran DP (30%)</option>
                                                        <option value="pelunasan">Pelunasan Penuh (100%)</option>
                                                    @else
                                                        <option value="pelunasan">Pelunasan Sisa</option>
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Jumlah Transfer (Rp)</label>
                                                <input type="number" id="jumlahManual" name="jumlah_bayar"
                                                       class="form-control rounded-3 bg-light"
                                                       readonly
                                                       value="{{ $pesanan->status === 'pending' ? $nominalDp : $sisaTagihan }}"
                                                       required>
                                                <small id="previewRupiah" class="text-success fw-semibold mt-1 d-block">
                                                    Rp {{ number_format($pesanan->status === 'pending' ? $nominalDp : $sisaTagihan, 0, ',', '.') }}
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Metode</label>
                                                <select name="metode" class="form-select rounded-3" required>
                                                    <option value="Transfer BCA">Transfer BCA</option>
                                                    <option value="Transfer Mandiri">Transfer Mandiri</option>
                                                    <option value="Transfer BNI">Transfer BNI</option>
                                                    <option value="Transfer BRI">Transfer BRI</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Foto Bukti Transfer</label>
                                                <input type="file" name="bukti_transfer" class="form-control rounded-3" accept="image/*" required>
                                            </div>

                                            <button type="submit" class="btn w-100 py-2 rounded-pill fw-bold text-white"
                                                    style="background: linear-gradient(135deg, #249D8F, #1A6B62);">
                                                <i class="bi bi-send-fill me-1"></i> Kirim Bukti Pembayaran
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Auto-hitung nominal form transfer manual
    const totalHarga = {{ $totalBiaya }};
    const sisaTagihan = {{ $sisaTagihan }};
    const nominalDp = {{ $nominalDp }};
    const statusPesanan = "{{ $pesanan->status }}";

    function formatRupiah(n) {
        return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    const tipeManual = document.getElementById('tipeManual');
    const jumlahManual = document.getElementById('jumlahManual');
    const previewRupiah = document.getElementById('previewRupiah');

    function updateNominal() {
        if (!tipeManual || !jumlahManual) return;
        let nominal = 0;
        if (statusPesanan === 'pending') {
            nominal = tipeManual.value === 'dp' ? nominalDp : totalHarga;
        } else {
            nominal = sisaTagihan;
        }
        jumlahManual.value = nominal;
        if (previewRupiah) previewRupiah.textContent = formatRupiah(nominal);
    }

    if (tipeManual) {
        tipeManual.addEventListener('change', updateNominal);
        updateNominal();
    }

    // Midtrans Snap
    const btnPay = document.getElementById('btnPayMidtrans');
    if (btnPay) {
        btnPay.addEventListener('click', function () {
            if (typeof window.snap === 'undefined') {
                alert("Library Midtrans belum termuat.\nPastikan MIDTRANS_CLIENT_KEY sudah diisi di .env lalu jalankan:\nphp artisan config:clear");
                return;
            }

            const radioTipe = document.querySelector('input[name="tipe_pembayaran"]:checked');
            const tipe = radioTipe ? radioTipe.value : 'dp';

            btnPay.disabled = true;
            btnPay.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menghubungi Midtrans...';

            fetch("{{ route('midtrans.token', $pesanan->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ tipe: tipe })
            })
            .then(async res => {
                const data = await res.json().catch(() => null);
                if (!res.ok || !data) throw new Error("Server error: " + res.status);
                return data;
            })
            .then(data => {
                btnPay.disabled = false;
                btnPay.innerHTML = '<i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang via Midtrans';

                if (data.status === 'success' && data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            fetch("{{ route('midtrans.finish', $pesanan->id) }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    tipe: tipe,
                                    gross_amount: result.gross_amount,
                                    payment_type: result.payment_type
                                })
                            }).then(() => window.location.reload());
                        },
                        onPending: function() { window.location.reload(); },
                        onError: function(result) {
                            alert("Pembayaran gagal: " + (result.status_message || 'Error'));
                        }
                    });
                } else {
                    alert("Gagal: " + (data.message || 'Periksa kunci Midtrans di .env'));
                }
            })
            .catch(err => {
                btnPay.disabled = false;
                btnPay.innerHTML = '<i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang via Midtrans';
                alert("Kendala: " + err.message);
            });
        });
    }

});
</script>
@endsection