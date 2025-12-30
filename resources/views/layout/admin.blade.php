<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perpustakaan | @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
        /* Navbar */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: white !important;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            color: #2c3e50 !important;
        }
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding-top: 20px;
            position: fixed;
            height: calc(100vh - 56px);
            left: 0;
            overflow-y: auto;
            top: 56px;
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8) !important;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        .sidebar .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.2);
            border-left-color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 30px;
            background: #f8f9fa;
        }
        /* Toggle Button */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #667eea;
            font-size: 1.5rem;
            cursor: pointer;
        }
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
                box-shadow: none;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s;
            }
            .sidebar.show {
                max-height: 500px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .sidebar-toggle {
                display: block;
            }
        }
        footer {
            margin-left: 280px;
        }
        @media (max-width: 768px) {
            footer {
                margin-left: 0;
            }
        }
        .profile-section {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 20px;
        }
        .profile-item {
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            padding: 10px 20px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        .profile-item:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .profile-item i {
            margin-right: 10px;
            width: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('admin.beranda')}}">
                <i class="bi bi-book"></i> Perpustakaan | Admin
            </a>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{route('admin.beranda')}}">
                    <i class="bi bi-house"></i> Beranda
                </a>
                <a class="nav-link" href="#">
                    <i class="bi bi-book"></i> Buku
                </a>
                <a class="nav-link" href="#">
                    <i class="bi bi-tag"></i> Kategori
                </a>
                <a class="nav-link" href="#">
                    <i class="bi bi-people"></i> Anggota
                </a>
                <a class="nav-link" href="#">
                    <i class="bi bi-arrow-left-right"></i> Peminjaman
                </a>
            </nav>

            <!-- Logout -->
            <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px;">
                <div style="color: rgba(255,255,255,0.8); cursor: pointer; padding: 10px 20px; transition: all 0.3s; display: flex; align-items: center;" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();" onmouseover="this.style.color='white'; this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateX(5px)';" onmouseout="this.style.color='rgba(255,255,255,0.8)'; this.style.background='transparent'; this.style.transform='translateX(0)';">
                    <i class="bi bi-box-arrow-right" style="margin-right: 10px; width: 20px;"></i> Keluar
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!--yieldawal-->
            @yield('content')
            <!--yieldakhir-->
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-5">
        <div class="text-center">
            <p class="text-muted mb-0">&copy; 2025 Perpustakaan. All rights reserved.</p>
        </div>
    </footer>

    <!--keluar-->
    <form id="keluar-app" action="{{ route('tampilan.logout')}}" method="POST" class="d-none">
        @csrf
    </form>
    <!--keluarend-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar on Mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when link is clicked on mobile
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>