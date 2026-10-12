<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventKu - Wujudkan Event Impian Anda')</title>

    <!-- Google Fonts (Plus Jakarta Sans & Poppins - Standar Modern Luxury) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Luxury Styling -->
    <style>
        :root {
            /* Tema Warna Baru: Royal Indigo & Champagne Gold */
            --primary: #4F46E5;
            --primary-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --primary-hover: #4338CA;
            
            --gold: #F59E0B;
            --gold-gradient: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            --gold-hover: #D97706;

            --dark-color: #0F172A;
            --dark-card: #1E293B;
            --light-bg: #F8FAFC;
            --card-border: rgba(226, 232, 240, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
            letter-spacing: -0.02em;
        }

        /* Navbar Glassmorphism Elegan */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 16px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
        }

        .navbar-brand span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-brand small {
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 0.85rem;
            font-weight: 800;
        }

        .nav-link {
            font-weight: 600;
            color: #475569 !important;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 15%;
            width: 70%;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        /* Tombol Utama Mewah (Royal Gradient with Subtle Glow) */
        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 11px 26px;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.3);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .btn-primary-custom:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.45);
        }

        /* Tombol Aksen Emas (Champagne Gold) */
        .btn-secondary-custom {
            background: var(--gold-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 11px 26px;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .btn-secondary-custom:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.45);
        }

        /* Kartu Elegan dengan Border Halus */
        .card {
            border: 1px solid var(--card-border) !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Badge Mewah */
        .badge.bg-warning {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%) !important;
            color: #92400E !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
            font-weight: 700;
        }

        .badge.bg-light.text-primary {
            background: #EEF2FF !important;
            color: #4F46E5 !important;
            border: 1px solid #E0E7FF !important;
            font-weight: 700;
        }

        /* Footer Mewah Velvet Slate */
        footer {
            background: linear-gradient(180deg, #0F172A 0%, #090D16 100%);
            color: #94A3B8;
            margin-top: auto;
            padding: 70px 0 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        footer h5, footer h6 {
            color: #FFFFFF !important;
            font-weight: 700;
        }

        footer a {
            color: #94A3B8;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        footer a:hover {
            color: var(--gold);
            transform: translateX(3px);
            display: inline-block;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navbar Responsif Bersih & Mewah -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fs-4 d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-stars fs-3 me-2" style="color: var(--gold);"></i>
                <span>Event</span>Ku<small class="ms-1">EO</small>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('paket*') ? 'active' : '' }}" href="{{ url('/paket') }}">Paket Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('galeri*') ? 'active' : '' }}" href="{{ url('/galeri') }}">Galeri Portofolio</a>
                    </li>
                    
                    @auth
                        @if(Auth::user()->role === 'customer')
                            <!-- Menu Khusus Pelanggan -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                                    <i class="bi bi-clock-history me-1"></i>Pesanan Saya
                                </a>
                            </li>
                        @endif

                        <!-- Dropdown Profil Akun -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5" style="color: var(--primary);"></i>
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->role === 'admin')
                                    <span class="badge bg-warning ms-1" style="font-size: 0.65rem;">Admin</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2" style="min-width: 200px;">
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item py-2 rounded-2" href="{{ url('/admin/dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Panel Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 rounded-2" href="{{ url('/admin/pesanan') }}">
                                            <i class="bi bi-card-list me-2 text-primary"></i>Kelola Pesanan
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 rounded-2" href="{{ url('/admin/paket') }}">
                                            <i class="bi bi-box-seam me-2 text-primary"></i>Kelola Paket
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 rounded-2" href="{{ url('/admin/galeri') }}">
                                            <i class="bi bi-images me-2 text-primary"></i>Kelola Galeri
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item py-2 rounded-2" href="{{ url('/dashboard') }}">
                                            <i class="bi bi-clock-history me-2 text-primary"></i>Pesanan Saya
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger rounded-2">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary-custom btn-sm px-4" href="{{ url('/register') }}">Daftar Sekarang</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Halaman -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Mewah -->
    <footer>
        <div class="container">
            <div class="row g-4 pb-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3 d-flex align-items-center">
                        <i class="bi bi-stars me-2" style="color: var(--gold);"></i>EventKu
                    </h5>
                    <p class="small" style="line-height: 1.8;">Partner terbaik untuk mewujudkan momen berharga Anda. Mulai dari pernikahan impian, pesta ulang tahun megah, hingga pertemuan korporat profesional dengan standar mutu tertinggi.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="mb-3">Kategori Layanan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/paket?kategori=Pernikahan') }}">Pernikahan (Wedding)</a></li>
                        <li class="mb-2"><a href="{{ url('/paket?kategori=Ulang+Tahun') }}">Pesta Ulang Tahun</a></li>
                        <li class="mb-2"><a href="{{ url('/paket?kategori=Seminar') }}">Seminar & Bisnis</a></li>
                        <li class="mb-2"><a href="{{ url('/galeri') }}">Portofolio Acara</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2" style="color: var(--gold);"></i>Jl. Sudirman No. 123, Jakarta</li>
                        <li class="mb-2"><i class="bi bi-whatsapp me-2" style="color: var(--gold);"></i>+62 812-3456-7890</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2" style="color: var(--gold);"></i>halo@eventku.com</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Jaminan & Keamanan</h6>
                    <p class="small mb-2" style="line-height: 1.8;">Pembayaran aman terverifikasi mendukung transfer bank BCA, Mandiri, BNI, dan BRI.</p>
                    <span class="badge bg-light text-primary px-3 py-2 rounded-pill small">
                        <i class="bi bi-shield-check me-1"></i> Transaksi Terpercaya
                    </span>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center small pt-2" style="color: #64748B;">
                &copy; {{ date('Y') }} EventKu Organizer &middot; Karya Proyek Pemrograman Web.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>