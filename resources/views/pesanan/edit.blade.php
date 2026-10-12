@extends('layouts.app')

@section('title', 'Ubah Pesanan #' . $pesanan->kode_pesanan . ' - EventKu')

@section('content')
<section class="py-5" style="background-color: var(--light-bg);">
    <div class="container">
        <div class="mb-4">
            <a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="row g-5">
            <!-- Form Edit Pesanan -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                    <h3 class="fw-bold text-dark mb-1">Ubah Detail Pesanan</h3>
                    <p class="text-muted small mb-4">Perbarui tanggal acara, lokasi gedung, atau layanan tambahan Anda.</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/pesanan/' . $pesanan->kode_pesanan) }}" method="POST" id="editBookingForm">
                        @csrf
                        @method('PUT')

                        <!-- Tanggal Acara -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tanggal Acara <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_event" class="form-control rounded-3 py-2" 
                                   value="{{ $pesanan->tanggal_event }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>

                        <!-- Lokasi Acara -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Lokasi / Gedung Acara <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control rounded-3 py-2" 
                                   value="{{ $pesanan->lokasi }}" required>
                        </div>

                        <!-- Estimasi Tamu -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Estimasi Jumlah Tamu Undangan <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_tamu" class="form-control rounded-3 py-2" 
                                   value="{{ $pesanan->jumlah_tamu }}" min="10" required>
                        </div>

                        <!-- Pilihan Add-on -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Layanan Tambahan (Add-on Opsional)</label>
                            <div class="p-3 p-md-4 bg-light rounded-4 border">
                                <div class="row g-3">
                                    @foreach($addons as $addon)
                                        <div class="col-12">
                                            <label class="card border rounded-3 p-3 bg-white shadow-sm d-flex flex-row align-items-center justify-content-between mb-0" 
                                                   for="addon_{{ $addon->id }}" style="cursor: pointer;">
                                                <div class="d-flex align-items-center gap-3">
                                                    <input class="form-check-input addon-checkbox m-0" 
                                                           type="checkbox" 
                                                           name="addons[]" 
                                                           value="{{ $addon->id }}" 
                                                           id="addon_{{ $addon->id }}"
                                                           data-harga="{{ $addon->harga }}"
                                                           {{ in_array($addon->id, $selectedAddonIds) ? 'checked' : '' }}
                                                           style="width: 20px; height: 20px;">
                                                    <div>
                                                        <div class="fw-bold text-dark fs-6">{{ $addon->nama_addon }}</div>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary small px-2 py-1 rounded-pill">
                                                            {{ $addon->kategori }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="fw-bold text-primary fs-6">+ Rp {{ number_format($addon->harga, 0, ',', '.') }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Khusus -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan Khusus</label>
                            <textarea name="catatan" rows="3" class="form-control rounded-3">{{ $pesanan->catatan }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-3 fs-6">
                            Simpan Perubahan Pesanan <i class="bi bi-check-lg ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Biaya Sticky -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold text-dark mb-3">Ringkasan Biaya Acara</h5>
                    <div class="d-flex align-items-center gap-3 pb-3 border-bottom mb-3">
                        <img src="{{ $pesanan->paket->gambar }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div>
                            <span class="badge bg-warning text-dark small mb-1">{{ $pesanan->paket->kategori }}</span>
                            <h6 class="fw-bold mb-0 text-dark">{{ $pesanan->paket->nama_paket }}</h6>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Biaya Paket Dasar</span>
                        <span class="fw-semibold">Rp {{ number_format($pesanan->paket->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Biaya Add-on Tambahan</span>
                        <span class="fw-semibold text-primary" id="addon-total-text">Rp 0</span>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark fs-6">Total Biaya Baru</span>
                        <span class="fw-bold text-primary fs-4" id="grand-total-text">
                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                        </span>
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
        const basePrice = {{ $pesanan->paket->harga }};
        const checkboxes = document.querySelectorAll('.addon-checkbox');
        const addonTotalText = document.getElementById('addon-total-text');
        const grandTotalText = document.getElementById('grand-total-text');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function calculate() {
            let addonTotal = 0;
            checkboxes.forEach(item => {
                if (item.checked) {
                    addonTotal += parseInt(item.getAttribute('data-harga'));
                }
            });
            const grandTotal = basePrice + addonTotal;
            addonTotalText.textContent = formatRupiah(addonTotal);
            grandTotalText.textContent = formatRupiah(grandTotal);
        }

        checkboxes.forEach(cb => cb.addEventListener('change', calculate));
        calculate();
    });
</script>
@endsection