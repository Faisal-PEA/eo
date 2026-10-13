<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - EventKu</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #249D8F;
            --primary-dark: #1A6B62;
            --sidebar-bg: #1B2A32;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            color: #fff;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .sidebar .brand {
            padding: 20px;
            font-weight: 800;
            font-size: 1.3rem;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .sidebar-menu li a:hover, 
        .sidebar-menu li a.active {
            color: #fff;
            background-color: rgba(36, 157, 143, 0.2);
            border-left: 4px solid var(--primary);
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-sb {
            border-left-width: 4px !important;
        }

        .border-left-primary { border-left-color: #249D8F !important; }
        .border-left-success { border-left-color: #10B981 !important; }
        .border-left-warning { border-left-color: #F59E0B !important; }
        .border-left-info { border-left-color: #3B82F6 !important; }

        /* FLOATING TOAST POPUP NOTIFICATION (AUTO DISMISS) */
        .toast-container-admin {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-popup-admin {
            pointer-events: auto;
            min-width: 320px;
            max-width: 420px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: #ffffff;
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
    </style>
    @stack('styles')
</head>
<body>

    <!-- ================= FLOATING AUTO-POPUP NOTIFICATIONS ADMIN ================= -->
    <div class="toast-container-admin">
        @if(session('success'))
            <div class="toast-popup-admin p-3 border d-flex align-items-center gap-3" style="border-left: 6px solid var(--primary) !important;">
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: #E6F5F3; width: 40px; height: 40px;">
                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <strong class="d-block text-dark small fw-bold">Berhasil!</strong>
                    <span class="small text-muted">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-popup-admin p-3 border d-flex align-items-center gap-3" style="border-left: 6px solid #EF4444 !important;">
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

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <i class="bi bi-stars text-warning"></i>
            <span>EventKu Admin</span>
        </a>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Manajemen Pesanan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.paket.index') }}" class="{{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Kelola Paket
                </a>
            </li>
            <li>
                <a href="{{ route('admin.galeri.index') }}" class="{{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i> Galeri Dokumentasi
                </a>
            </li>
            <li class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                <a href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Lihat Website Utama
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN WRAPPER -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <div>
                <span class="badge bg-light text-dark border">Panel Administrator</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold text-dark small"><i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- KONTEN DINAMIS -->
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hapus elemen toast dari DOM otomatis setelah 4.3 detik
        setTimeout(() => {
            const toasts = document.querySelectorAll('.toast-popup-admin');
            toasts.forEach(t => t.remove());
        }, 4300);
    </script>
    @stack('scripts')
</body>
</html>