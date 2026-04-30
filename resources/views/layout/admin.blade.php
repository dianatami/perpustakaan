<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                radial-gradient(circle at 5% -5%, rgba(15, 140, 128, 0.16), transparent 30%),
                radial-gradient(circle at 110% 10%, rgba(255, 138, 61, 0.14), transparent 35%),
                var(--admin-bg);
            color: var(--admin-ink);
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
            font-size: 0.75rem;
            font-weight: 600;
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
    @php
        $adminMenu = [
            ['route' => 'admin.beranda', 'active' => 'admin.beranda', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Ringkasan'],
            ['route' => 'admin.books.index', 'active' => 'admin.books.*', 'icon' => 'bi-journal-bookmark-fill', 'label' => 'Data Buku'],
            ['route' => 'admin.kategori.index', 'active' => 'admin.kategori.*', 'icon' => 'bi-tags-fill', 'label' => 'Kategori'],
            ['route' => 'admin.anggota.index', 'active' => 'admin.anggota.*', 'icon' => 'bi-people-fill', 'label' => 'Manajemen Anggota'],
            ['route' => 'admin.peminjaman.index', 'active' => 'admin.peminjaman.*', 'icon' => 'bi-arrow-repeat', 'label' => 'Peminjaman'],
        ];
    @endphp

    <div class="admin-shell">
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
                @foreach ($adminMenu as $menu)
                    <a
                        href="{{ route($menu['route']) }}"
                        class="admin-nav-link {{ request()->routeIs($menu['active']) ? 'active' : '' }}"
                    >
                        <i class="{{ $menu['icon'] }}"></i>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                @endforeach
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
                    <div class="live-clock-shell" aria-live="polite" aria-atomic="true">
                        <div class="live-clock-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <span class="live-clock-label">Waktu Real-time</span>
                            <span class="live-clock-time" data-live-clock>--:--:--</span>
                            <span class="live-clock-date" data-live-date>--</span>
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

        // Hindari prompt "resubmit form" saat user refresh setelah submit.
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, '', window.location.href);
        }

        if (toggleSidebarButton && adminSidebar) {
            toggleSidebarButton.addEventListener('click', () => {
                adminSidebar.classList.toggle('show');
            });

            document.querySelectorAll('.admin-nav-link').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 991) {
                        adminSidebar.classList.remove('show');
                    }
                });
            });
        }
    </script>
</body>
</html>