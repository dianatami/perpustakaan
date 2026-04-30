<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perpustakaan | @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @php
        $loggedInUser = Auth::user();
        $portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota';
        $isGuru = (int) ($loggedInUser->role ?? 0) === \App\Models\User::ROLE_GURU;
        $portalTitle = $isGuru ? 'Portal Guru' : 'Portal Murid';
        $portalSubtitle = $isGuru ? 'Ruang literasi untuk pengajar' : 'Ruang belajar dan eksplorasi buku';

        $theme = $isGuru
            ? [
                'primary' => '#1f7a46',
                'primaryDark' => '#145d34',
                'secondary' => '#f3a530',
                'surface' => '#f2f8f3',
                'ink' => '#163824',
                'muted' => '#5f7868',
            ]
            : [
                'primary' => '#0b7ca6',
                'primaryDark' => '#0d5f80',
                'secondary' => '#ff6b6b',
                'surface' => '#f1f8fc',
                'ink' => '#123340',
                'muted' => '#617886',
            ];

        $portalMenus = [
            ['route' => $portalPrefix . '.beranda', 'active' => $portalPrefix . '.beranda', 'icon' => 'bi-house-door-fill', 'label' => 'Beranda'],
            ['route' => $portalPrefix . '.buku.index', 'active' => $portalPrefix . '.buku.*', 'icon' => 'bi-journal-text', 'label' => 'Buku'],
            ['route' => $portalPrefix . '.kategori.index', 'active' => $portalPrefix . '.kategori.*', 'icon' => 'bi-collection-fill', 'label' => 'Kategori'],
            ['route' => $portalPrefix . '.profil.detail', 'active' => $portalPrefix . '.profil*', 'icon' => 'bi-person-vcard-fill', 'label' => 'Profil'],
        ];
    @endphp

    <style>
        :root {
            --portal-primary: {{ $theme['primary'] }};
            --portal-primary-dark: {{ $theme['primaryDark'] }};
            --portal-secondary: {{ $theme['secondary'] }};
            --portal-surface: {{ $theme['surface'] }};
            --portal-ink: {{ $theme['ink'] }};
            --portal-muted: {{ $theme['muted'] }};
            --portal-border: rgba(16, 72, 70, 0.12);
            --portal-shadow: 0 20px 40px rgba(17, 56, 55, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            color: var(--portal-ink);
            background:
                radial-gradient(circle at 8% -5%, color-mix(in srgb, var(--portal-primary) 26%, transparent), transparent 30%),
                radial-gradient(circle at 98% 8%, color-mix(in srgb, var(--portal-secondary) 18%, transparent), transparent 28%),
                var(--portal-surface);
        }

        .portal-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .portal-header {
            position: sticky;
            top: 0;
            z-index: 30;
            background: rgba(255, 255, 255, 0.78);
            border-bottom: 1px solid var(--portal-border);
            backdrop-filter: blur(8px);
            padding: 14px 22px;
        }

        .portal-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--portal-ink);
        }

        .portal-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(150deg, var(--portal-primary), var(--portal-primary-dark));
            color: #fff;
            box-shadow: var(--portal-shadow);
            font-size: 1.2rem;
        }

        .portal-brand-title {
            margin: 0;
            font-family: 'Libre Baskerville', serif;
            font-size: 1.03rem;
            line-height: 1.15;
            letter-spacing: 0.1px;
        }

        .portal-brand-subtitle {
            margin: 2px 0 0;
            color: var(--portal-muted);
            font-size: 0.81rem;
        }

        .portal-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .portal-user-chip {
            border-radius: 99px;
            border: 1px solid var(--portal-border);
            padding: 7px 12px;
            font-weight: 700;
            color: var(--portal-primary-dark);
            background: #fff;
            font-size: 0.92rem;
        }

        .portal-logout {
            border: 0;
            border-radius: 12px;
            padding: 8px 13px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--portal-secondary), color-mix(in srgb, var(--portal-secondary) 72%, #ffffff));
            transition: transform 0.2s ease;
        }

        .portal-logout:hover {
            transform: translateY(-2px);
        }

        .portal-nav-toggle {
            display: none;
            border: 0;
            padding: 0;
            background: transparent;
            font-size: 1.4rem;
            color: var(--portal-primary);
        }

        .portal-nav-wrap {
            margin-top: 14px;
        }

        .portal-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .portal-nav-link {
            text-decoration: none;
            color: var(--portal-muted);
            font-weight: 700;
            border-radius: 999px;
            padding: 8px 14px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .portal-nav-link:hover {
            color: var(--portal-primary-dark);
            background: #fff;
            border-color: var(--portal-border);
        }

        .portal-nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--portal-primary), var(--portal-primary-dark));
            box-shadow: 0 10px 20px rgba(19, 72, 72, 0.16);
        }

        .portal-main {
            flex: 1;
            width: min(1220px, calc(100% - 28px));
            margin: 18px auto 24px;
        }

        .portal-main > * {
            animation: showUp 0.36s ease both;
        }

        .portal-footer {
            text-align: center;
            color: var(--portal-muted);
            font-size: 0.86rem;
            padding: 12px;
            border-top: 1px solid var(--portal-border);
            background: rgba(255, 255, 255, 0.7);
        }

        @keyframes showUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 992px) {
            .portal-user-chip {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .portal-header {
                padding: 12px 14px;
            }

            .portal-nav-toggle {
                display: inline-block;
            }

            .portal-nav-wrap {
                display: none;
            }

            .portal-nav-wrap.show {
                display: block;
            }

            .portal-nav {
                flex-direction: column;
                align-items: stretch;
            }

            .portal-nav-link {
                border-radius: 12px;
            }

            .portal-main {
                width: calc(100% - 14px);
                margin-top: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="portal-shell">
        <header class="portal-header">
            <div class="portal-header-top">
                <a href="{{ route($portalPrefix . '.beranda') }}" class="portal-brand">
                    <span class="portal-brand-icon">
                        <i class="bi {{ $isGuru ? 'bi-mortarboard-fill' : 'bi-stars' }}"></i>
                    </span>
                    <span>
                        <h1 class="portal-brand-title">{{ $portalTitle }}</h1>
                        <p class="portal-brand-subtitle">{{ $portalSubtitle }}</p>
                    </span>
                </a>

                <div class="portal-actions">
                    <button type="button" class="portal-nav-toggle" id="portalNavToggle" aria-label="Buka menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="portal-user-chip">
                        <i class="bi bi-person-circle"></i> {{ $loggedInUser->nama }}
                    </span>
                    <button type="button" class="portal-logout" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="portal-nav-wrap" id="portalNavWrap">
                <nav class="portal-nav">
                    @foreach ($portalMenus as $menu)
                        <a
                            href="{{ route($menu['route']) }}"
                            class="portal-nav-link {{ request()->routeIs($menu['active']) ? 'active' : '' }}"
                        >
                            <i class="{{ $menu['icon'] }}"></i>
                            <span>{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </header>

        <main class="portal-main">
            @yield('content')
        </main>

        <footer class="portal-footer">
            &copy; {{ date('Y') }} Perpustakaan Sekolah. {{ $portalTitle }}.
        </footer>
    </div>

    <form id="keluar-app" action="{{ route('tampilan.logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const portalNavToggle = document.getElementById('portalNavToggle');
        const portalNavWrap = document.getElementById('portalNavWrap');

        // Hindari prompt "resubmit form" saat user refresh halaman setelah submit.
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, '', window.location.href);
        }

        if (portalNavToggle && portalNavWrap) {
            portalNavToggle.addEventListener('click', () => {
                portalNavWrap.classList.toggle('show');
            });

            document.querySelectorAll('.portal-nav-link').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        portalNavWrap.classList.remove('show');
                    }
                });
            });
        }
    </script>
</body>
</html>
