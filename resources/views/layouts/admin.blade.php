<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - EventKu')</title>

    <!-- Google Fonts Nunito (Font Khas SB Admin) -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SB Admin 2 Custom Styling -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Khas SB Admin 2 */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            min-height: 100vh;
            color: #fff;
            transition: all 0.3s;
        }

        .sidebar .sidebar-brand {
            height: 70px;
            text-decoration: none;
            font-size: 1.15rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: .05rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar hr.sidebar-divider {
            margin: 0 1rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, .15);
        }

        .sidebar .sidebar-heading {
            color: rgba(255, 255, 255, .4);
            font-size: .65rem;
            font-weight: 800;
            text-transform: uppercase;
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .sidebar .nav-item .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, .8);
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            border-radius: 0.35rem;
            margin: 0 0.5rem 0.2rem;
            transition: all 0.2s;
        }

        .sidebar .nav-item .nav-link:hover,
        .sidebar .nav-item .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .15);
        }

        .sidebar .nav-item .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        /* Tombol Logout Elegan Senada Sidebar */
        .btn-sb-logout {
            background-color: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
            border-radius: 0.35rem;
            transition: all 0.25s ease;
        }

        .btn-sb-logout:hover {
            background-color: #e74a3b;
            color: #ffffff;
            border-color: #e74a3b;
            box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);
        }

        /* Content Wrapper */
        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Topbar Navbar */
        .topbar {
            height: 70px;
            background-color: #fff;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        /* Card Khas SB Admin */
        .card-sb {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .1);
            background-color: #fff;
        }

        .border-left-primary { border-left: .25rem solid #4e73df !important; }
        .border-left-success { border-left: .25rem solid #1cc88a !important; }
        .border-left-info    { border-left: .25rem solid #36b9cc !important; }
        .border-left-warning { border-left: .25rem solid #f6c23e !important; }

        .text-xs {
            font-size: .7rem;
            letter-spacing: .05rem;
        }
    </style>
    @yield('styles')
</head>
<body>

<div id="wrapper">
    <!-- Sidebar SB Admin -->
    <ul class="sidebar navbar-nav p-0 m-0 list-unstyled">
        <!-- Sidebar Brand -->
        <a class="sidebar-brand" href="{{ url('/admin/dashboard') }}">
            <i class="bi bi-stars text-warning fs-3 me-2"></i>
            <span>EVENTKU ADMIN</span>
        </a>

        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">Menu Utama</div>

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Nav Item - Kelola Pesanan -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/pesanan*') ? 'active' : '' }}" href="{{ url('/admin/pesanan') }}">
                <i class="bi bi-calendar2-event"></i>
                <span>Kelola Pesanan</span>
            </a>
        </li>

        <!-- Nav Item - Kelola Paket Event -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/paket*') ? 'active' : '' }}" href="{{ url('/admin/paket') }}">
                <i class="bi bi-box-seam"></i>
                <span>Kelola Paket Event</span>
            </a>
        </li>

        <!-- Nav Item - Kelola Galeri Portofolio -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/galeri*') ? 'active' : '' }}" href="{{ url('/admin/galeri') }}">
                <i class="bi bi-images"></i>
                <span>Kelola Galeri Foto</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Website Publik</div>

        <!-- Nav Item - Lihat Website Depan -->
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Lihat Website Depan</span>
            </a>
        </li>

        <!-- Tombol Logout Senada SB Admin -->
        <li class="nav-item mt-auto p-3">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sb-logout w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </li>
    </ul>

    <!-- Content Wrapper -->
    <div id="content-wrapper">
        <!-- Topbar -->
        <nav class="topbar mb-4 justify-content-between">
            <div class="d-none d-sm-inline-block">
                <span class="text-secondary small fw-bold">Sistem Manajemen Event Organizer & Pembayaran Online</span>
            </div>

            <!-- Topbar User Information -->
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <span class="d-block text-dark fw-bold small">{{ Auth::user()->name }}</span>
                    <span class="badge bg-danger text-uppercase" style="font-size: 0.65rem;">Super Administrator</span>
                </div>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: 700;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </nav>

        <!-- Main Page Content -->
        <div class="container-fluid px-4 pb-5">
            @yield('content')
        </div>

        <!-- Footer SB Admin -->
        <footer class="bg-white py-3 mt-auto text-center text-muted small border-top">
            <span>Copyright &copy; EventKu Organizer {{ date('Y') }} &middot; SB Admin Style</span>
        </footer>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>