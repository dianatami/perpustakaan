<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perpustakaan | @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --kepala-bg: #f8f6f2;
            --kepala-ink: #1f2e2e;
            --kepala-muted: #687a79;
            --kepala-primary: #0e6b69;
            --kepala-primary-dark: #0b4f4d;
            --kepala-gold: #c79a3f;
            --kepala-border: rgba(15, 68, 66, 0.16);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--kepala-ink);
            background:
                radial-gradient(circle at 12% -5%, rgba(199, 154, 63, 0.22), transparent 28%),
                radial-gradient(circle at 100% 10%, rgba(14, 107, 105, 0.16), transparent 35%),
                var(--kepala-bg);
        }

        .kepala-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .kepala-header {
            position: sticky;
            top: 0;
            z-index: 40;
            padding: 16px 26px;
            border-bottom: 1px solid var(--kepala-border);
            background: rgba(255, 255, 255, 0.76);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .kepala-brand {
            text-decoration: none;
            color: var(--kepala-ink);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .kepala-brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            background: linear-gradient(150deg, var(--kepala-primary) 0%, var(--kepala-primary-dark) 100%);
            box-shadow: 0 16px 32px rgba(14, 74, 73, 0.2);
        }

        .kepala-brand-title {
            margin: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0.2px;
        }

        .kepala-brand-subtitle {
            margin: 2px 0 0;
            font-size: 0.84rem;
            color: var(--kepala-muted);
        }

        .kepala-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kepala-chip {
            border-radius: 999px;
            border: 1px solid var(--kepala-border);
            padding: 8px 14px;
            background: #fff;
            color: var(--kepala-primary-dark);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .kepala-logout {
            border: 0;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            padding: 9px 14px;
            background: linear-gradient(150deg, #ba8840 0%, #9f6f28 100%);
            transition: transform 0.2s ease;
        }

        .kepala-logout:hover {
            transform: translateY(-2px);
        }

        .kepala-main {
            flex: 1;
            width: min(1280px, calc(100% - 32px));
            margin: 20px auto;
        }

        .kepala-main > * {
            animation: fadeGrow 0.35s ease both;
        }

        .kepala-footer {
            text-align: center;
            padding: 14px;
            border-top: 1px solid var(--kepala-border);
            color: var(--kepala-muted);
            font-size: 0.85rem;
            background: rgba(255, 255, 255, 0.7);
        }

        @keyframes fadeGrow {
            from {
                opacity: 0;
                transform: translateY(7px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .kepala-header {
                padding: 12px 14px;
                flex-wrap: wrap;
            }

            .kepala-brand-title {
                font-size: 1.38rem;
            }

            .kepala-chip {
                display: none;
            }

            .kepala-main {
                width: calc(100% - 12px);
                margin-top: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="kepala-shell">
        <header class="kepala-header">
            <a href="{{ route('kepala.beranda') }}" class="kepala-brand">
                <span class="kepala-brand-icon"><i class="bi bi-bank2"></i></span>
                <span>
                    <h1 class="kepala-brand-title">Dashboard Kepala Sekolah</h1>
                    <p class="kepala-brand-subtitle">Executive access for literacy governance</p>
                </span>
            </a>

            <div class="kepala-header-actions">
                <span class="kepala-chip"><i class="bi bi-person-badge-fill"></i> {{ Auth::user()->nama }}</span>
                <button type="button" class="kepala-logout" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </div>
        </header>

        <main class="kepala-main">
            @yield('content')
        </main>

        <footer class="kepala-footer">
            &copy; {{ date('Y') }} Perpustakaan Sekolah. Executive analytics panel.
        </footer>
    </div>

    <form id="keluar-app" action="{{ route('tampilan.logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hindari prompt "resubmit form" saat user refresh setelah submit.
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, '', window.location.href);
        }
    </script>
</body>
</html>
