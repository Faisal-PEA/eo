@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->kode_pesanan . ' - EventKu')

@section('content')
<section class="py-5" style="background-color: var(--light-bg);">
    <div class="container">
        <!-- Tombol Kembali Pintar: Cek Role Admin vs Customer -->
        <div class="mb-4">
            @if(Auth::user()->role === 'admin')
                <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Panel Admin
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard Saya
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Kolom Kiri: Rincian Lengkap Pesanan Acara -->
            <div class="{{ Auth::user()->role === 'admin' ? 'col-lg-8' : 'col-lg-7' }}">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-4">
                        <div>
                            <span class="text-muted small">Nomor Invoice</span>
                            <h4 class="fw-bold mb-0 text-dark">#{{ $pesanan->kode_pesanan }}</h4>
                        </div>
                        <div>
                            @if($pesanan->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Menunggu Pembayaran</span>
                            @elseif($pesanan->status == 'dp_paid')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold">DP 50% Terverifikasi</span>
                            @elseif($pesanan->status == 'confirmed')
                                <span class="badge bg-success px-3 py-2 rounded-pill fw-bold">Pesanan Dikonfirmasi</span>
                            @elseif($pesanan->status == 'preparation')
                                <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold">Tahap Persiapan Acara</span>
                            @elseif($pesanan->status == 'done')
                                <span class="badge bg-dark px-3 py-2 rounded-pill fw-bold">Acara Selesai</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">Dibatalkan</span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Pemesan (Sangat berguna bagi Admin) -->
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-person-circle me-1 text-primary"></i> Data Pemesan:</h6>
                        <div class="row g-2 small">
                            <div class="col-sm-4"><strong>Nama:</strong> {{ $pesanan->user->name }}</div>
                            <div class="col-sm-4"><strong>Email:</strong> {{ $pesanan->user->email }}</div>
                            <div class="col-sm-4"><strong>Telepon:</strong> {{ $pesanan->user->telepon }}</div>
                        </div>
                    </div>

                    <!-- Informasi Acara -->
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Acara</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Paket Acara</small>
                            <span class="fw-bold text-dark">{{ $pesanan->paket->nama_paket }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Tanggal Pelaksanaan</small>
                            <span class="fw-bold text-dark">{{ date('d F Y', strtotime($pesanan->tanggal_event)) }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Lokasi Acara</small>
                            <span class="fw-bold text-dark">{{ $pesanan->lokasi }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Jumlah Tamu</small>
                            <span class="fw-bold text-dark">{{ $pesanan->jumlah_tamu }} Orang</span>
                        </div>
                        @if($pesanan->catatan)
                            <div class="col-12">
                                <small class="text-muted d-block">Catatan Khusus</small>
                                <span class="text-dark small">{{ $pesanan->catatan }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Rincian Biaya & Add-on -->
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Rincian Biaya</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless table-striped align-middle small rounded-3 overflow-hidden">
                            <tbody>
                                <tr>
                                    <td class="ps-3 fw-semibold">Biaya Paket Dasar ({{ $pesanan->paket->nama_paket }})</td>
                                    <td class="text-end pe-3 fw-bold">Rp {{ number_format($pesanan->paket->harga, 0, ',', '.') }}</td>
                                </tr>
                                @foreach($pesanan->pesananAddons as $item)
                                    <tr>
                                        <td class="ps-3">+ {{ $item->addon->nama_addon }} <span class="text-muted">({{ $item->addon->kategori }})</span></td>
                                        <td class="text-end pe-3 text-primary">+ Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <th class="ps-3 fs-6">Total Biaya Keseluruhan</th>
                                    <th class="text-end pe-3 fs-5 text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Minimal Uang Muka (DP 50%)</th>
                                    <th class="text-end pe-3 text-success">Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Riwayat Pembayaran yang Pernah Diunggah -->
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Bukti Pembayaran</h5>
                    @if($pesanan->pembayarans->isEmpty())
                        <div class="p-3 bg-light rounded-3 text-center text-muted small">
                            Belum ada bukti pembayaran yang diunggah untuk pesanan ini.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu Upload</th>
                                        <th>Jenis & Bank</th>
                                        <th>Jumlah Ditransfer</th>
                                        <th>Status Verifikasi</th>
                                        <th>Bukti Struk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesanan->pembayarans as $bayar)
                                        <tr>
                                            <td>{{ $bayar->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-secondary mb-1 text-uppercase">{{ $bayar->jenis }}</span>
                                                <div class="fw-semibold">{{ $bayar->metode }}</div>
                                            </td>
                                            <td class="fw-bold">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                            <td>
                                                @if($bayar->status == 'pending')
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Verifikasi</span>
                                                @elseif($bayar->status == 'verified')
                                                    <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i>Terverifikasi</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ asset('uploads/bukti/' . $bayar->bukti_transfer) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2">
                                                    <i class="bi bi-image"></i> Lihat Struk
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Rekening & Form Upload (Hanya Tampil Jika yang Membuka adalah Customer) -->
            @if(Auth::user()->role === 'customer')
                <div class="col-lg-5">
                    <!-- Info Rekening Bank Resmi -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card me-2 text-warning"></i>Rekening Pembayaran Resmi</h5>
                        <p class="text-muted small mb-3">Silakan transfer biaya DP atau pelunasan ke rekening resmi EventKu berikut:</p>

                        <div class="d-flex flex-column gap-2 mb-3">
                            <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary px-2 mb-1">Bank BCA</span>
                                    <div class="fw-bold fs-6 text-dark">8830-1234-5678</div>
                                    <small class="text-muted">a.n. PT EventKu Kreasi Nusantara</small>
                                </div>
                                <i class="bi bi-wallet2 fs-3 text-muted"></i>
                            </div>

                            <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-warning text-dark px-2 mb-1">Bank Mandiri</span>
                                    <div class="fw-bold fs-6 text-dark">1370-0098-7654</div>
                                    <small class="text-muted">a.n. PT EventKu Kreasi Nusantara</small>
                                </div>
                                <i class="bi bi-wallet2 fs-3 text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Form Upload Bukti Transfer -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-upload me-2 text-primary"></i>Konfirmasi Pembayaran</h5>
                        <p class="text-muted small mb-4">Unggah bukti struk atau tangkapan layar m-Banking setelah Anda melakukan transfer.</p>

                        <form action="{{ url('/pembayaran') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pilihan Pembayaran</label>
                                <select name="jenis" class="form-select rounded-3 py-2" id="jenisPembayaran" required>
                                    <option value="dp">Pembayaran Uang Muka (DP 50%) - Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</option>
                                    <option value="pelunasan">Langsung Pelunasan (100%) - Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Ditransfer ke Bank</label>
                                <select name="metode" class="form-select rounded-3 py-2" required>
                                    <option value="BCA">BCA (8830-1234-5678)</option>
                                    <option value="Mandiri">Mandiri (1370-0098-7654)</option>
                                    <option value="BNI">BNI (0234-567-890)</option>
                                    <option value="BRI">BRI (0123-01-000456-50-8)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Jumlah yang Ditransfer (Rp)</label>
                                <input type="number" name="jumlah" id="jumlahTransfer" class="form-control rounded-3 py-2" 
                                       value="{{ $pesanan->total_harga / 2 }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Foto / Tangkapan Layar Bukti Struk</label>
                                <input type="file" name="bukti_transfer" class="form-control rounded-3" accept="image/*" required>
                                <small class="text-muted" style="font-size: 0.75rem;">Format: JPG, JPEG, PNG (Maksimal 3MB).</small>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Kirim Bukti Transfer
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Jika yang Membuka adalah Admin: Tampilkan Panel Kontrol Cepat -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-sliders me-2 text-warning"></i>Kontrol Admin</h5>
                        <p class="text-muted small mb-3">Ubah status acara langsung dari sini:</p>

                        <form action="{{ url('/admin/pesanan/' . $pesanan->id . '/status') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Status Operasional Acara</label>
                                <select name="status" class="form-select rounded-3 py-2">
                                    <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                                    <option value="dp_paid" {{ $pesanan->status == 'dp_paid' ? 'selected' : '' }}>DP Paid (DP Terverifikasi)</option>
                                    <option value="confirmed" {{ $pesanan->status == 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi)</option>
                                    <option value="preparation" {{ $pesanan->status == 'preparation' ? 'selected' : '' }}>Preparation (Persiapan)</option>
                                    <option value="done" {{ $pesanan->status == 'done' ? 'selected' : '' }}>Done (Acara Selesai)</option>
                                    <option value="cancelled" {{ $pesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 py-2">
                                <i class="bi bi-save me-1"></i> Perbarui Status Acara
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenisPembayaran');
        const jumlahInput = document.getElementById('jumlahTransfer');
        if (jenisSelect && jumlahInput) {
            const totalHarga = {{ $pesanan->total_harga }};
            jenisSelect.addEventListener('change', function () {
                if (this.value === 'dp') {
                    jumlahInput.value = totalHarga / 2;
                } else {
                    jumlahInput.value = totalHarga;
                }
            });
        }
    });
</script>
@endsection