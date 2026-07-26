<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Perpustakaan | @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700;800&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --admin-bg: #f3f7f6;
            --admin-surface: #ffffff;
            --admin-ink: #17353c;
            --admin-muted: #6b848a;
            --admin-primary: #0f8c80;
            --admin-primary-soft: #e6f6f3;
            --admin-accent: #ff8a3d;
            --admin-border: #d6e6e3;
            --admin-shadow: 0 22px 45px rgba(18, 55, 56, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Nunito Sans', sans-serif;
            background:
                radial-gradient(circle at 5% -5%, rgba(15, 140, 128, 0.14), transparent 32%),
                radial-gradient(circle at 110% 12%, rgba(255, 138, 61, 0.12), transparent 38%),
                linear-gradient(130deg, rgba(255, 255, 255, 0.8), rgba(245, 250, 249, 0.92)),
                var(--admin-bg);
            color: var(--admin-ink);
            letter-spacing: 0.1px;
        }

        .admin-shell {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            position: relative;
            overflow: hidden;
        }

        .admin-sidebar {
            width: 290px;
            flex-shrink: 0;
            background: linear-gradient(170deg, #0f3a41 0%, #136d65 100%);
            color: #d9f1ef;
            padding: 24px 18px 18px;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 3;
        }

        .admin-sidebar::before {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            right: -88px;
            top: -72px;
            background: rgba(255, 255, 255, 0.09);
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            text-decoration: none;
            color: #ffffff;
            position: relative;
            z-index: 1;
        }

        .admin-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.16);
            font-size: 1.2rem;
        }

        .admin-brand-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1.25;
        }

        .admin-brand-subtitle {
            font-size: 0.78rem;
            color: rgba(226, 245, 242, 0.72);
        }

        .admin-school-name {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(214, 245, 240, 0.25);
            font-size: 1.25rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.85);
            letter-spacing: 0.05em;
        }

        .admin-nav {
            margin-top: 14px;
            display: grid;
            gap: 9px;
            position: relative;
            z-index: 1;
        }

        .admin-nav-link {
            text-decoration: none;
            color: rgba(221, 241, 239, 0.87);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 11px;
            font-weight: 700;
            letter-spacing: 0.1px;
            transition: transform 0.22s ease, background 0.22s ease, color 0.22s ease;
        }

        .admin-nav-link:hover {
            transform: translateX(4px);
            color: #fff;
            background: rgba(255, 255, 255, 0.14);
        }

        .admin-nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0.09));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .admin-nav-link i {
            font-size: 1rem;
            width: 1.2rem;
            text-align: center;
        }

        .admin-nav-section {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.45);
            padding: 12px 14px 4px;
            font-weight: 800;
        }

        .admin-sidebar-footer {
            margin-top: auto;
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(214, 245, 240, 0.2);
            padding-top: 16px;
        }

        .logout-btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 11px 14px;
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.24);
        }

        .admin-main {
            min-width: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--admin-border);
            background: rgba(255, 255, 255, 0.68);
            backdrop-filter: blur(5px);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .admin-topbar-title {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--admin-ink);
        }

        .admin-topbar-subtitle {
            font-size: 0.82rem;
            color: var(--admin-muted);
            margin-top: 2px;
        }

        .admin-topbar-clock {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            padding: 14px 18px;
            border-radius: 22px;
            background: linear-gradient(145deg, rgba(16, 23, 46, 0.96), rgba(29, 79, 120, 0.92));
            color: #fff;
            box-shadow: 0 18px 34px rgba(16, 23, 46, 0.18);
        }

        .admin-topbar-clock-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .admin-topbar-clock-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 3px;
        }

        .admin-topbar-clock-time {
            display: block;
            font-family: 'Sora', sans-serif;
            font-size: 1.8rem;
            line-height: 1;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .admin-topbar-clock-date {
            display: block;
            margin-top: 6px;
            font-size: 0.86rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.82);
        }

        .admin-user-chip {
            border-radius: 99px;
            background: #ffffff;
            border: 1px solid var(--admin-border);
            padding: 8px 14px;
            font-weight: 700;
            color: var(--admin-primary);
            box-shadow: 0 5px 16px rgba(24, 69, 68, 0.08);
        }

        .mobile-toggle {
            border: 0;
            background: transparent;
            color: var(--admin-primary);
            font-size: 1.4rem;
            display: none;
            padding: 0;
        }

        .admin-content {
            padding: 28px;
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            flex: 1;
        }

        .admin-content > * {
            animation: riseIn 0.38s ease both;
        }

        .admin-footer {
            text-align: center;
            color: #789297;
            border-top: 1px solid var(--admin-border);
            padding: 14px;
            font-size: 0.86rem;
            background: rgba(255, 255, 255, 0.64);
        }

        .admin-content .card,
        .admin-content .table-responsive,
        .admin-content .alert,
        .admin-content .list-group {
            border-radius: 18px;
            border: 1px solid rgba(17, 50, 57, 0.12);
            box-shadow: 0 16px 36px rgba(16, 23, 46, 0.08);
            background: #ffffff;
        }

        .admin-content .table {
            margin-bottom: 0;
        }

        .admin-content .table thead th {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #6b7f85;
            background: #f2f7f7;
            border-bottom: 1px solid rgba(17, 50, 57, 0.1);
        }

        .admin-content .form-control,
        .admin-content .form-select,
        .admin-content textarea {
            border-radius: 12px;
            border: 1px solid rgba(17, 50, 57, 0.14);
            padding: 10px 14px;
            box-shadow: none;
        }

        .admin-content .btn-primary {
            border: 0;
            border-radius: 12px;
            font-weight: 700;
            background: linear-gradient(135deg, #0f8c80, #116b64);
            box-shadow: 0 14px 28px rgba(17, 56, 55, 0.16);
        }

        .admin-content .btn-outline-primary {
            border-radius: 12px;
            font-weight: 700;
            border-color: rgba(15, 140, 128, 0.35);
            color: #0f8c80;
        }

        .admin-content .btn-outline-secondary {
            border-radius: 12px;
            font-weight: 700;
        }

        .pagination {
            margin: 0;
            gap: 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 0;
            list-style: none;
        }

        .pagination .page-item {
            border-radius: 14px;
        }

        .pagination .page-link {
            min-width: 42px;
            min-height: 42px;
            border: 1px solid rgba(17, 50, 57, 0.12);
            border-radius: 14px;
            color: #415055;
            background-color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            padding: 0.6rem 0.85rem;
        }

        .pagination .page-link:hover,
        .pagination .page-link:focus {
            background-color: #f3faf8;
            color: #0f8c80;
            border-color: rgba(15, 140, 128, 0.18);
        }

        .pagination .page-item.active .page-link {
            background-color: #0f8c80;
            border-color: #0f8c80;
            color: #ffffff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15);
        }

        .pagination .page-item.disabled .page-link {
            color: rgba(65, 80, 85, 0.35);
            background-color: #f8faf9;
            border-color: rgba(17, 50, 57, 0.08);
        }

        /* Sidebar Overlay Styling */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2; /* Sidebar has z-index: 3 */
            backdrop-filter: blur(2px);
            visibility: hidden;
            opacity: 0;
            transition: visibility 0.25s, opacity 0.25s ease;
        }

        .sidebar-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        @media (min-width: 992px) {
            .sidebar-overlay {
                display: none !important;
            }
        }

        @keyframes riseIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                left: -310px;
                top: 0;
                bottom: 0;
                transition: left 0.25s ease;
            }

            .admin-sidebar.show {
                left: 0;
            }

            .mobile-toggle {
                display: inline-block;
            }

            .admin-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    @include('partials.particles')
    
    <div class="admin-shell">
        <!-- Overlay for Sidebar -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="admin-sidebar" id="adminSidebar">
            <a href="{{ route('admin.beranda') }}" class="admin-brand">
                <span class="admin-brand-icon"><i class="bi bi-building-fill"></i></span>
                <span>
                    <span class="admin-brand-title">Control Room</span>
                    <span class="admin-brand-subtitle d-block">Perpustakaan Sekolah</span>
                    <span class="admin-school-name d-block">Perpustakaan SMKN 1 Tirtamulya</span>
                </span>
            </a>

            <nav class="admin-nav">
                <!-- Dashboard Section -->
                <a href="{{ route('admin.beranda') }}" class="admin-nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Master Data Section -->
                <div class="admin-nav-section">Master Data</div>
                <a href="{{ route('admin.books.index') }}" class="admin-nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Data Buku</span>
                </a>
                <a href="{{ route('admin.racks.index') }}" class="admin-nav-link {{ request()->routeIs('admin.racks.*') ? 'active' : '' }}">
                    <i class="bi bi-archive-fill"></i>
                    <span>Rak Buku</span>
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="admin-nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="admin-nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen Anggota</span>
                </a>

                <!-- Transaksi Section -->
                <div class="admin-nav-section">Transaksi</div>
                <a href="{{ route('admin.transaksi.pengajuan') }}" class="admin-nav-link {{ request()->routeIs('admin.transaksi.pengajuan') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Pengajuan Peminjaman</span>
                </a>
                <a href="{{ route('admin.transaksi.pengembalian') }}" class="admin-nav-link {{ request()->routeIs('admin.transaksi.pengembalian') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Pengembalian</span>
                </a>
                <a href="{{ route('admin.transaksi.riwayat') }}" class="admin-nav-link {{ request()->routeIs('admin.transaksi.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <!-- Laporan Section -->
                <div class="admin-nav-section">Laporan</div>
                <a href="{{ route('admin.laporan.utama') }}" class="admin-nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan Perpustakaan</span>
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <button type="button" class="logout-btn" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Keluar Aplikasi</span>
                </button>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="mobile-toggle" id="toggleSidebar" aria-label="Buka menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h1 class="admin-topbar-title">@yield('title', 'Dashboard Admin')</h1>
                        <div class="admin-topbar-subtitle">Kelola inventaris, anggota, dan transaksi perpustakaan.</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap justify-content-end">
                            <div class="admin-topbar-clock" aria-live="polite" aria-atomic="true">
                                <div class="admin-topbar-clock-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                                    <span class="admin-topbar-clock-label"></span>
                                    <span class="admin-topbar-clock-time" data-live-clock>--:--:--</span>
                                    <span class="admin-topbar-clock-date" data-live-date>--</span>
                        </div>
                    </div>
                    <div class="admin-user-chip">
                        <i class="bi bi-person-check-fill"></i> {{ Auth::user()->nama }}
                    </div>
                </div>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>

            <footer class="admin-footer">
                &copy; {{ date('Y') }} Perpustakaan Sekolah. Panel manajemen internal.
            </footer>
        </div>
    </div>

    <form id="keluar-app" action="{{ route('tampilan.logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleSidebarButton = document.getElementById('toggleSidebar');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const modalBackdrops = document.querySelectorAll('.modal-backdrop');
        modalBackdrops.forEach(el => el.remove());

        // Hindari prompt "resubmit form" saat user refresh setelah submit.
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, '', window.location.href);
        }

        function closeSidebar() {
            if (adminSidebar) adminSidebar.classList.remove('show');
            if (sidebarOverlay) sidebarOverlay.classList.remove('show');
        }

        if (toggleSidebarButton && adminSidebar) {
            toggleSidebarButton.addEventListener('click', (e) => {
                e.stopPropagation(); // Mencegah klik dari event document lainnya
                adminSidebar.classList.toggle('show');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
            });
        
            // Menutup sidebar jika overlay diklik
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Menutup sidebar dengan tombol ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && adminSidebar.classList.contains('show')) {
                    closeSidebar();
                }
            });

            document.querySelectorAll('.admin-nav-link').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 991) {
                        closeSidebar();
                    }
                });
            });
        }
    </script>
</body>
</html>