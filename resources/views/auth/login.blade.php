@extends('layouts.app')

@section('title', 'Masuk - EventKu')

@section('content')
<div class="container py-5 my-md-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark">Selamat Datang</h3>
                    <p class="text-muted small">Masuk ke akun EventKu Anda untuk mengelola acara</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 small rounded-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Email</label>
                        <input type="email" name="email" class="form-control rounded-3 py-2" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kata Sandi</label>
                        <input type="password" name="password" class="form-control rounded-3 py-2" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mt-2">
                        Masuk Sekarang <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4 pt-2 border-top small text-muted">
                    Belum punya akun? <a href="{{ url('/register') }}" class="text-primary fw-bold text-decoration-none">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection