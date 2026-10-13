<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'EventKu' }} - Modern Event Organizer</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --teal-primary: #249D8F;
            --teal-dark: #1A6B62;
            --teal-light: #E6F5F3;
            --accent-warm: #F4A261;
            --accent-coral: #E76F51;
            --dark-navy: #1B2A32;
            --grad-teal: linear-gradient(135deg, #249D8F 0%, #1A6B62 100%);
            --grad-dark: linear-gradient(135deg, #1B2A32 0%, #264653 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(36, 157, 143, 0.08);
            border-bottom: 1px solid rgba(36, 157, 143, 0.15);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.45rem;
            color: var(--dark-navy) !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-badge {
            background: var(--grad-teal);
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .nav-link {
            font-weight: 600;
            color: #475569 !important;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.25s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--teal-primary) !important;
            background-color: var(--teal-light);
        }

        .btn-teal {
            background: var(--grad-teal);
            color: white !important;
            font-weight: 600;
            border: none;
            padding: 10px 22px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(36, 157, 143, 0.3);
            transition: all 0.3s ease;
        }

        .btn-teal:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(36, 157, 143, 0.45);
        }

        .btn-teal-outline {
            border: 2px solid var(--teal-primary);
            color: var(--teal-primary) !important;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 10px;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-teal-outline:hover {
            background: var(--teal-primary);
            color: white !important;
        }

        .badge-teal {
            background-color: var(--teal-light);
            color: var(--teal-dark);
            border: 1px solid rgba(36, 157, 143, 0.3);
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 50rem;
        }

        .text-teal { color: var(--teal-primary) !important; }
        .bg-teal-gradient { background: var(--grad-teal) !important; }

        /* FLOATING TOAST POPUP NOTIFICATION (AUTO DISMISS) */
        .toast-container-custom {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-popup {
            pointer-events: auto;
            min-width: 320px;
            max-width: 420px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1), fadeOut 0.5s 3.8s forwards;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }

        /* FOOTER */
        .footer-custom {
            background: var(--grad-dark);
            color: #94A3B8;
            margin-top: auto;
            border-top: 4px solid var(--teal-primary);
        }

        .footer-custom h5 { color: #FFFFFF; font-weight: 700; margin-bottom: 20px; }
        .footer-custom a { color: #CBD5E1; text-decoration: none; }
        .footer-custom a:hover { color: var(--teal-primary); }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ================= FLOATING AUTO-POPUP NOTIFICATIONS ================= -->
    <div class="toast-container-custom">
        @if(session('success'))
            <div class="toast-popup p-3 bg-white border d-flex align-items-center gap-3" style="border-left: 6px solid var(--teal-primary) !important;">
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: #E6F5F3; width: 40px; height: 40px;">
                    <i class="bi bi-check-circle-fill text-teal fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <strong class="d-block text-dark small fw-bold">Berhasil!</strong>
                    <span class="small text-muted">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-popup p-3 bg-white border d-flex align-items-center gap-3" style="border-left: 6px solid #E76F51 !important;">
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0 bg-danger bg-opacity-10" style="width: 40px; height: 40px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <strong class="d-block text-danger small fw-bold">Pemberitahuan</strong>
                    <span class="small text-muted">{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-badge"><i class="bi bi-stars"></i></span>
                <span>EventKu<span class="text-teal">.</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('paket.*') ? 'active' : '' }}" href="{{ route('paket.index') }}">
                            <i class="bi bi-box-seam me-1"></i> Paket Acara
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('galeri.*') ? 'active' : '' }}" href="{{ route('galeri.index') }}">
                            <i class="bi bi-images me-1"></i> Galeri
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-teal dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                                @if(auth()->user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold text-teal" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Panel Admin
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold" href="{{ route('dashboard') }}">
                                            <i class="bi bi-journal-text me-2"></i> Pesanan Saya
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger fw-semibold">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-teal-outline">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-teal">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer-custom">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="brand-badge"><i class="bi bi-stars"></i></span>
                        <h4 class="text-white fw-bold mb-0">EventKu<span class="text-teal">.</span></h4>
                    </div>
                    <p class="text-secondary pe-lg-4">
                        Wujudkan setiap momen perayaan spesial Anda menjadi memori tak terlupakan dengan pelayanan profesional dari kami.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>Navigasi</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right text-teal me-1"></i> Beranda</a></li>
                        <li><a href="{{ route('paket.index') }}"><i class="bi bi-chevron-right text-teal me-1"></i> Paket Acara</a></li>
                        <li><a href="{{ route('galeri.index') }}"><i class="bi bi-chevron-right text-teal me-1"></i> Galeri Foto</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <h5>Layanan</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('paket.index') }}?kategori=Pernikahan">Wedding & Lamaran</a></li>
                        <li><a href="{{ route('paket.index') }}?kategori=Ulang Tahun">Birthday Party</a></li>
                        <li><a href="{{ route('paket.index') }}?kategori=Seminar">Corporate Gathering</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Kontak</h5>
                    <p class="text-secondary small mb-1"><i class="bi bi-geo-alt-fill text-teal me-1"></i> Jakarta, Indonesia</p>
                    <p class="text-secondary small mb-1"><i class="bi bi-telephone-fill text-teal me-1"></i> +62 812-3456-7890</p>
                    <p class="text-secondary small"><i class="bi bi-envelope-fill text-teal me-1"></i> halo@eventku.com</p>
                </div>
            </div>
        </div>
        <div class="py-3 text-center text-secondary border-top border-white border-opacity-10 small">
            © {{ date('Y') }} EventKu. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hapus elemen toast dari DOM otomatis setelah 4.3 detik
        setTimeout(() => {
            const toasts = document.querySelectorAll('.toast-popup');
            toasts.forEach(t => t.remove());
        }, 4300);
    </script>
    @stack('scripts')
</body>
</html>