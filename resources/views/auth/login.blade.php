@extends('layouts.app')

@section('content')
<div class="py-5 bg-light d-flex align-items-center" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden auth-card">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="text-center mb-4">
                            <span class="badge px-3 py-2 rounded-pill fw-bold mb-2" 
                                  style="background-color: #E6F5F3; color: #249D8F; border: 1px solid rgba(36, 157, 143, 0.25);">
                                <i class="bi bi-shield-lock-fill me-1"></i> Customer Access
                            </span>
                            <h3 class="fw-extrabold text-dark mt-2">Selamat Datang</h3>
                            <p class="text-muted small">Silakan masuk ke akun EventKu Anda</p>
                        </div>

                        <!-- TOMBOL LOGIN GOOGLE ELEGAN -->
                        <div class="d-grid mb-3">
                            <a href="{{ route('auth.google') }}" class="btn btn-google-auth py-2 px-3 d-flex align-items-center justify-content-center gap-3">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span class="fw-semibold">Masuk dengan Google</span>
                            </a>
                        </div>

                        <!-- PEMBATAS (OR) -->
                        <div class="position-relative my-4 text-center">
                            <hr class="text-muted opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted small fw-semibold">
                                atau gunakan email
                            </span>
                        </div>

                        <!-- FORM LOGIN MANUAL -->
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                           placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-dark">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                           placeholder="••••••••" required>
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-teal py-2 fw-bold">
                                    Masuk ke Akun <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}" class="text-teal fw-bold text-decoration-none">Daftar Sekarang</a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .auth-card {
        background: #ffffff;
        border: 1px solid rgba(36, 157, 143, 0.15) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .btn-google-auth {
        background-color: #ffffff;
        color: #334155;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .btn-google-auth:hover {
        background-color: #F8FAFC;
        border-color: rgba(36, 157, 143, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(36, 157, 143, 0.12);
    }

    .input-group-text {
        border-color: #CBD5E1;
    }

    .form-control {
        border-color: #CBD5E1;
    }

    .form-control:focus {
        border-color: #249D8F;
        box-shadow: 0 0 0 0.2rem rgba(36, 157, 143, 0.15);
    }
</style>
@endpush