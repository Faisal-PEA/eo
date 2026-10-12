@extends('layouts.admin')

@section('title', 'Rincian Pesanan #' . $pesanan->kode_pesanan . ' - SB Admin')

@section('content')
<!-- Page Heading (Bersih Tanpa Tombol Kanan) -->
<div class="mb-4">
    <h1 class="h3 mb-1 text-gray-800 fw-bold">Detail Pesanan #{{ $pesanan->kode_pesanan }}</h1>
    <p class="text-muted small mb-0">Rincian operasional acara, status pembayaran, dan data pemesan.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Kolom Kiri: Rincian Acara & Biaya (Gaya SB Admin) -->
    <div class="col-lg-8">
        <!-- Card 1: Informasi Klien & Acara -->
        <div class="card card-sb mb-4">
            <div class="card-header py-3 bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-info-circle me-1"></i> Informasi Agenda Acara</h6>
                <span class="badge bg-light text-dark border px-3 py-2 text-uppercase fw-bold">{{ $pesanan->status }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase text-xs fw-bold">Nama Klien / Pemesan</small>
                        <span class="fw-bold text-dark fs-6">{{ $pesanan->user->name }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase text-xs fw-bold">Kontak / WhatsApp</small>
                        <span class="fw-bold text-dark fs-6">{{ $pesanan->user->telepon }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase text-xs fw-bold">Email Klien</small>
                        <span class="text-dark">{{ $pesanan->user->email }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase text-xs fw-bold">Tanggal Pelaksanaan Acara</small>
                        <span class="fw-bold text-primary">{{ date('d F Y', strtotime($pesanan->tanggal_event)) }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase text-xs fw-bold">Paket Pilihan</small>
                        <span class="fw-bold text-dark">{{ $pesanan->paket->nama_paket }}</span>
                        <span class="badge bg-warning text-dark ms-1">{{ $pesanan->paket->kategori }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase text-xs fw-bold">Lokasi / Gedung Acara</small>
                        <span class="text-dark">{{ $pesanan->lokasi }} ({{ $pesanan->jumlah_tamu }} Tamu)</span>
                    </div>
                    @if($pesanan->catatan)
                        <div class="col-12 mt-3 pt-3 border-top">
                            <small class="text-muted d-block text-uppercase text-xs fw-bold">Catatan Khusus Klien:</small>
                            <p class="mb-0 text-dark small bg-light p-2 rounded">{{ $pesanan->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card 2: Rincian Anggaran / Biaya -->
        <div class="card card-sb mb-4">
            <div class="card-header py-3 bg-white border-0">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-receipt me-1"></i> Rincian Anggaran & Add-on</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light text-xs text-uppercase">
                        <tr>
                            <th class="ps-4">Deskripsi Layanan</th>
                            <th class="text-end pe-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 fw-bold">Biaya Paket Utama: {{ $pesanan->paket->nama_paket }}</td>
                            <td class="text-end pe-4 fw-bold">Rp {{ number_format($pesanan->paket->harga, 0, ',', '.') }}</td>
                        </tr>
                        @foreach($pesanan->pesananAddons as $item)
                            <tr>
                                <td class="ps-4 text-muted">+ {{ $item->addon->nama_addon }} ({{ $item->addon->kategori }})</td>
                                <td class="text-end pe-4 text-primary">+ Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th class="ps-4 fs-6">Total Biaya Keseluruhan</th>
                            <th class="text-end pe-4 fs-5 text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Card 3: Riwayat Bukti Transfer Masuk -->
        <div class="card card-sb mb-4">
            <div class="card-header py-3 bg-white border-0">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-cash-coin me-1"></i> Riwayat Pembayaran Masuk</h6>
            </div>
            <div class="card-body p-0">
                @if($pesanan->pembayarans->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        Klien belum mengunggah bukti pembayaran apa pun untuk pesanan ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light text-xs text-uppercase">
                                <tr>
                                    <th class="ps-4">Waktu</th>
                                    <th>Jenis & Bank</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->pembayarans as $bayar)
                                    <tr>
                                        <td class="ps-4">{{ $bayar->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-secondary text-uppercase">{{ $bayar->jenis }}</span>
                                            <div>{{ $bayar->metode }}</div>
                                        </td>
                                        <td class="fw-bold text-success">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                        <td>
                                            @if($bayar->status === 'verified')
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @elseif($bayar->status === 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ asset('uploads/bukti/' . $bayar->bukti_transfer) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2">
                                                <i class="bi bi-image"></i> Lihat Struk
                                            </a>
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($bayar->status === 'pending')
                                                <div class="d-flex justify-content-end gap-1">
                                                    <form action="{{ url('/admin/pembayaran/' . $bayar->id . '/verifikasi') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="verified">
                                                        <button type="submit" class="btn btn-success btn-sm py-0 px-2" title="Terima">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ url('/admin/pembayaran/' . $bayar->id . '/verifikasi') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-danger btn-sm py-0 px-2" title="Tolak">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted small">Selesai</span>
                                            @endif
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

    <!-- Kolom Kanan: Panel Kontrol Cepat Ubah Status Acara -->
    <div class="col-lg-4">
        <div class="card card-sb sticky-top" style="top: 90px;">
            <div class="card-header py-3 bg-white border-0">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-sliders me-1"></i> Kontrol Progres Acara</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Ubah status tahapan acara ini untuk memperbarui progres di dashboard klien.</p>

                <form action="{{ url('/admin/pesanan/' . $pesanan->id . '/status') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Status Saat Ini:</label>
                        <select name="status" class="form-select rounded-2">
                            <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                            <option value="dp_paid" {{ $pesanan->status == 'dp_paid' ? 'selected' : '' }}>DP Paid (DP Terverifikasi)</option>
                            <option value="confirmed" {{ $pesanan->status == 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi)</option>
                            <option value="preparation" {{ $pesanan->status == 'preparation' ? 'selected' : '' }}>Preparation (Dalam Persiapan)</option>
                            <option value="done" {{ $pesanan->status == 'done' ? 'selected' : '' }}>Done (Acara Sukses Selesai)</option>
                            <option value="cancelled" {{ $pesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Perbarui Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection