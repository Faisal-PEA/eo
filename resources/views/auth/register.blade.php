@extends('layouts.app')

@section('title', 'Daftar Akun Baru - EventKu')

@section('content')
<div class="container py-5 my-md-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark">Buat Akun Baru</h3>
                    <p class="text-muted small">Daftar sekarang untuk memesan paket acara impian Anda</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 small rounded-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Email</label>
                        <input type="email" name="email" class="form-control rounded-3 py-2" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor WhatsApp / HP</label>
                        <input type="text" name="telepon" class="form-control rounded-3 py-2" value="{{ old('telepon') }}" placeholder="08123456789" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kata Sandi (Min. 6 Karakter)</label>
                        <input type="password" name="password" class="form-control rounded-3 py-2" placeholder="••••••••" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3 py-2" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">
                        Daftar Akun <i class="bi bi-person-check ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4 pt-2 border-top small text-muted">
                    Sudah punya akun? <a href="{{ url('/login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection