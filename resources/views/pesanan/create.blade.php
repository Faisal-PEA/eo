@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        
        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-teal text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('paket.index') }}" class="text-teal text-decoration-none">Paket</a></li>
                <li class="breadcrumb-item"><a href="{{ route('paket.show', $paket->id) }}" class="text-teal text-decoration-none">{{ $paket->nama_paket }}</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Form Pemesanan</li>
            </ol>
        </nav>

        <form action="{{ route('pesanan.store') }}" method="POST" id="formPemesanan">
            @csrf
            <input type="hidden" name="paket_id" value="{{ $paket->id }}">

            <div class="row g-4">
                
                <!-- KOLOM FORM DATA ACARA & PILIHAN ADD-ONS -->
                <div class="col-lg-8">
                    
                    <!-- KARTU DATA ACARA -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <span class="badge rounded-circle p-2 bg-teal-gradient text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    1
                                </span>
                                <h4 class="fw-bold text-dark mb-0">Informasi Jadwal & Acara</h4>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Tanggal Acara <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_acara" class="form-control @error('tanggal_acara') is-invalid @enderror" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('tanggal_acara') }}" required>
                                    @error('tanggal_acara')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Estimasi Jumlah Tamu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="jumlah_tamu" class="form-control @error('jumlah_tamu') is-invalid @enderror" 
                                               placeholder="Contoh: 300" min="1" value="{{ old('jumlah_tamu', 100) }}" required>
                                        <span class="input-group-text bg-white text-muted">Orang</span>
                                    </div>
                                    @error('jumlah_tamu')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-dark">Lokasi / Alamat Lengkap Acara <span class="text-danger">*</span></label>
                                    <textarea name="lokasi_acara" rows="2" class="form-control @error('lokasi_acara') is-invalid @enderror" 
                                              placeholder="Nama Gedung / Hotel / Rumah beserta alamat lengkap..." required>{{ old('lokasi_acara') }}</textarea>
                                    @error('lokasi_acara')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-dark">Catatan Khusus atau Request Tambahan (Opsional)</label>
                                    <textarea name="catatan_khusus" rows="2" class="form-control" 
                                              placeholder="Tema warna yang diinginkan, susunan acara khusus, dll...">{{ old('catatan_khusus') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU PILIHAN ADD-ONS -->
                    @if(isset($addons) && $addons->count() > 0)
                        <div class="card border-0 rounded-4 shadow-sm mb-4">
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-circle p-2 bg-teal-gradient text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            2
                                        </span>
                                        <h4 class="fw-bold text-dark mb-0">Pilihan Layanan Tambahan (Add-ons)</h4>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-hand-index-thumb me-1"></i>Pilih sesuai kebutuhan</small>
                                </div>

                                <div class="row g-3">
                                    @foreach($addons as $addon)
                                        <div class="col-md-6">
                                            <label class="addon-card p-3 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer w-100" 
                                                   for="addon_{{ $addon->id }}">
                                                <div class="d-flex align-items-center gap-3">
                                                    <input type="checkbox" 
                                                           name="addons[]" 
                                                           value="{{ $addon->id }}" 
                                                           id="addon_{{ $addon->id }}" 
                                                           class="form-check-input addon-checkbox mt-0" 
                                                           data-harga="{{ $addon->harga }}"
                                                           style="width: 20px; height: 20px; cursor: pointer;">
                                                    <div>
                                                        <h6 class="fw-bold mb-1 text-dark">{{ $addon->nama_addon }}</h6>
                                                        <small class="text-muted d-block">{{ $addon->deskripsi }}</small>
                                                    </div>
                                                </div>
                                                <span class="badge badge-teal ms-2 flex-shrink-0">
                                                    + Rp {{ number_format($addon->harga, 0, ',', '.') }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- KOLOM SIDEBAR RINGKASAN & PEMBAYARAN DP -->
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            @php
                                $hargaDasar = $paket->harga ?? $paket->harga_dasar ?? 0;
                            @endphp

                            <h5 class="fw-bold text-dark mb-3">Ringkasan Pesanan</h5>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                                <div class="flex-grow-1">
                                    <span class="badge category-pill-teal mb-1">{{ $paket->kategori }}</span>
                                    <h6 class="fw-bold text-dark mb-0">{{ $paket->nama_paket }}</h6>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Harga Dasar Paket</span>
                                <span class="fw-bold text-dark">Rp {{ number_format($hargaDasar, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Tambahan Add-ons (<span id="addonCount">0</span>)</span>
                                <span class="fw-bold text-teal" id="addonTotalLabel">Rp 0</span>
                            </div>

                            <hr class="opacity-25">

                            <div class="mb-3">
                                <span class="text-muted small d-block">Total Estimasi Biaya:</span>
                                <h3 class="fw-extrabold text-teal mb-0" id="totalPriceLabel">
                                    Rp {{ number_format($hargaDasar, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="p-3 rounded-3 mb-4" style="background-color: #F0F9F8; border: 1px dashed rgba(36, 157, 143, 0.35);">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-bold text-teal">Wajib DP (30%):</span>
                                    <span class="fw-bold text-teal" id="dpAmountLabel">
                                        Rp {{ number_format($hargaDasar * 0.3, 0, ',', '.') }}
                                    </span>
                                </div>
                                <small class="text-muted" style="font-size: 0.78rem;">Sisa 70% dilunasi H-7 sebelum hari acara.</small>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-teal py-3 fw-bold fs-6">
                                    Konfirmasi & Buat Pesanan <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>

                            <p class="text-center text-muted small mb-0">
                                <i class="bi bi-shield-check text-success me-1"></i> Data pemesanan Anda aman dan terenkripsi.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .addon-card {
        background-color: #ffffff;
        border: 1.5px solid #E2E8F0 !important;
        transition: all 0.25s ease;
        user-select: none;
    }

    .addon-card:hover {
        border-color: rgba(36, 157, 143, 0.5) !important;
        background-color: #F8FAFC;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(36, 157, 143, 0.08);
    }

    .addon-card.selected {
        border-color: #249D8F !important;
        background-color: #E6F5F3 !important;
        box-shadow: 0 4px 15px rgba(36, 157, 143, 0.15);
    }

    .addon-checkbox:checked {
        background-color: #249D8F;
        border-color: #249D8F;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const basePrice = {{ $hargaDasar }};
        const checkboxes = document.querySelectorAll('.addon-checkbox');
        const addonCountLabel = document.getElementById('addonCount');
        const addonTotalLabel = document.getElementById('addonTotalLabel');
        const totalPriceLabel = document.getElementById('totalPriceLabel');
        const dpAmountLabel = document.getElementById('dpAmountLabel');

        function formatRupiah(number) {
            return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateTotal() {
            let totalAddon = 0;
            let count = 0;

            checkboxes.forEach(cb => {
                const card = cb.closest('.addon-card');
                if (cb.checked) {
                    totalAddon += parseInt(cb.getAttribute('data-harga')) || 0;
                    count++;
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            const grandTotal = basePrice + totalAddon;
            const dpAmount = grandTotal * 0.3;

            addonCountLabel.textContent = count;
            addonTotalLabel.textContent = formatRupiah(totalAddon);
            totalPriceLabel.textContent = formatRupiah(grandTotal);
            dpAmountLabel.textContent = formatRupiah(dpAmount);
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', calculateTotal);
        });

        calculateTotal();
    });
</script>
@endpush