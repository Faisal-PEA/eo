@extends('layouts.app')

@section('title', 'Form Pemesanan Acara - EventKu')

@section('content')
<section class="py-5" style="background-color: var(--light-bg);">
    <div class="container">
        <!-- Breadcrumb / Tombol Kembali -->
        <div class="mb-4">
            <a href="{{ url('/paket/' . $paket->slug) }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Rincian Paket
            </a>
        </div>

        <div class="row g-5">
            <!-- Kolom Kiri: Formulir Data Acara -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                    <h3 class="fw-bold text-dark mb-1">Form Pemesanan Acara</h3>
                    <p class="text-muted small mb-4">Lengkapi detail agenda acara Anda untuk mempermudah persiapan tim kami.</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/pesanan') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="paket_id" value="{{ $paket->id }}">

                        <!-- Tanggal Acara -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tanggal Acara <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_event" class="form-control rounded-3 py-2" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            <small class="text-muted">Pilih tanggal minimal H+1 dari hari ini.</small>
                        </div>

                        <!-- Lokasi Acara -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Lokasi / Gedung Acara <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control rounded-3 py-2" 
                                   placeholder="Contoh: Grand Ballroom Hotel Mulia, Jakarta" required>
                        </div>

                        <!-- Estimasi Tamu -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Estimasi Jumlah Tamu Undangan <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_tamu" class="form-control rounded-3 py-2" 
                                   placeholder="Contoh: 300" min="10" required>
                        </div>

                        <!-- Pilihan Add-on Interaktif -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Layanan Tambahan (Add-on Opsional)</label>
                            <div class="p-3 p-md-4 bg-light rounded-4 border">
                                <small class="text-muted d-block mb-3">Centang layanan tambahan yang ingin Anda sertakan pada acara:</small>
                                
                                <div class="row g-3">
                                    @foreach($addons as $addon)
                                        <div class="col-12">
                                            <label class="card border rounded-3 p-3 bg-white shadow-sm d-flex flex-row align-items-center justify-content-between mb-0" 
                                                   for="addon_{{ $addon->id }}" 
                                                   style="cursor: pointer; transition: all 0.2s ease;">
                                                <div class="d-flex align-items-center gap-3">
                                                    <input class="form-check-input addon-checkbox m-0" 
                                                           type="checkbox" 
                                                           name="addons[]" 
                                                           value="{{ $addon->id }}" 
                                                           id="addon_{{ $addon->id }}"
                                                           data-harga="{{ $addon->harga }}"
                                                           style="width: 20px; height: 20px;">
                                                    <div>
                                                        <div class="fw-bold text-dark fs-6">{{ $addon->nama_addon }}</div>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary small px-2 py-1 rounded-pill">
                                                            <i class="bi bi-tag me-1"></i>{{ $addon->kategori }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fw-bold text-primary fs-6">+ Rp {{ number_format($addon->harga, 0, ',', '.') }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Khusus -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan Khusus / Permintaan Tambahan</label>
                            <textarea name="catatan" rows="3" class="form-control rounded-3" 
                                      placeholder="Tuliskan tema warna, konsep dekorasi, atau permintaan khusus Anda di sini..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-3 fs-6">
                            Lanjut ke Konfirmasi & Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan Biaya Real-time (Sticky) -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold text-dark mb-3">Ringkasan Estimasi Biaya</h5>

                    <!-- Info Paket yang Dipilih -->
                    <div class="d-flex align-items-center gap-3 pb-3 border-bottom mb-3">
                        <img src="{{ $paket->gambar }}" alt="{{ $paket->nama_paket }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div>
                            <span class="badge bg-warning text-dark small mb-1">{{ $paket->kategori }}</span>
                            <h6 class="fw-bold mb-0 text-dark">{{ $paket->nama_paket }}</h6>
                        </div>
                    </div>

                    <!-- Perhitungan Harga Real-time -->
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Biaya Paket Dasar</span>
                        <span class="fw-semibold">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Biaya Add-on Tambahan</span>
                        <span class="fw-semibold text-primary" id="addon-total-text">Rp 0</span>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark fs-6">Total Biaya Acara</span>
                        <span class="fw-bold text-primary fs-4" id="grand-total-text">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Rincian Pembayaran Bertahap (DP 50%) -->
                    <div class="p-3 rounded-3 bg-light border mb-2">
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="text-muted fw-bold">Uang Muka (DP 50%)</span>
                            <span class="fw-bold text-success fs-6" id="dp-total-text">
                                Rp {{ number_format($paket->harga / 2, 0, ',', '.') }}
                            </span>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i>Anda cukup membayar DP 50% untuk mengunci tanggal acara. Sisa 50% dilunasi H-7 sebelum acara.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const basePrice = {{ $paket->harga }};
        const checkboxes = document.querySelectorAll('.addon-checkbox');
        const addonTotalText = document.getElementById('addon-total-text');
        const grandTotalText = document.getElementById('grand-total-text');
        const dpTotalText = document.getElementById('dp-total-text');
        const bookingForm = document.getElementById('bookingForm');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        // Kalkulasi real-time saat checkbox dicentang / dilepas
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                let addonTotal = 0;
                checkboxes.forEach(item => {
                    if (item.checked) {
                        addonTotal += parseInt(item.getAttribute('data-harga'));
                    }
                });

                const grandTotal = basePrice + addonTotal;
                const dpTotal = grandTotal / 2;

                addonTotalText.textContent = formatRupiah(addonTotal);
                grandTotalText.textContent = formatRupiah(grandTotal);
                dpTotalText.textContent = formatRupiah(dpTotal);
            });
        });

        // Proteksi klik ganda: tombol otomatis disabled & memunculkan teks loading
        bookingForm.addEventListener('submit', function () {
            const submitBtn = bookingForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan Pesanan...';
        });
    });
</script>
@endsection