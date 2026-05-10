@extends('layout.admin')
@section('title', 'Beranda Admin')

@section('content')
@php
    $totalPenggunaPortal = max(1, $totalAnggota + $totalGuru);
    $persenAnggota = round(($totalAnggota / $totalPenggunaPortal) * 100);
    $persenGuru = round(($totalGuru / $totalPenggunaPortal) * 100);

    $adminStats = [
        ['label' => 'Total Buku', 'value' => $totalBuku, 'icon' => 'bi-journal-bookmark-fill', 'tone' => 'teal'],
        ['label' => 'Total Anggota', 'value' => $totalAnggota, 'icon' => 'bi-people-fill', 'tone' => 'ocean'],
        ['label' => 'Total Guru', 'value' => $totalGuru, 'icon' => 'bi-mortarboard-fill', 'tone' => 'indigo'],
        ['label' => 'Total Peminjaman', 'value' => $totalPinjam, 'icon' => 'bi-arrow-repeat', 'tone' => 'amber'],
        ['label' => 'Kategori Buku', 'value' => $totalKategori, 'icon' => 'bi-tags-fill', 'tone' => 'forest'],
        ['label' => 'Buku Rusak/Hilang', 'value' => $totalRusakHilang, 'icon' => 'bi-exclamation-triangle-fill', 'tone' => 'coral'],
    ];
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Unbounded:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .admin-shell {
        background:
            radial-gradient(circle at 8% -8%, rgba(24, 142, 122, 0.2), transparent 34%),
            radial-gradient(circle at 110% 12%, rgba(255, 122, 89, 0.18), transparent 36%),
            #f7f2e8;
    }

    .admin-sidebar {
        background: linear-gradient(170deg, #10172e 0%, #1d4f78 58%, #178f78 100%);
    }

    .admin-brand-title,
    .admin-topbar-title {
        font-family: 'Unbounded', sans-serif;
        letter-spacing: 0.02em;
    }

    .admin-brand-subtitle,
    .admin-topbar-subtitle,
    .admin-nav-link,
    .admin-user-chip,
    .admin-footer {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .admin-nav-link.active {
        background: linear-gradient(135deg, rgba(255, 201, 92, 0.34), rgba(255, 255, 255, 0.15));
    }

    .admin-topbar {
        border-bottom: 1px solid rgba(16, 23, 46, 0.12);
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(9px);
    }

    .admin-user-chip {
        color: #1d4f78;
        border-color: rgba(29, 79, 120, 0.2);
    }

    .admin-content {
        padding: 28px;
    }

    .shelf-admin-wrap {
        display: grid;
        gap: 18px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .shelf-admin-hero {
        border-radius: 26px;
        border: 1px solid rgba(255, 255, 255, 0.34);
        background:
            linear-gradient(124deg, rgba(16, 23, 46, 0.92) 0%, rgba(29, 79, 120, 0.88) 45%, rgba(23, 143, 120, 0.86) 100%);
        color: #f7f2e8;
        box-shadow: 0 28px 50px rgba(21, 55, 82, 0.27);
        padding: 28px;
        position: relative;
        overflow: hidden;
    }

    .shelf-admin-hero::before {
        content: '';
        position: absolute;
        width: 290px;
        height: 290px;
        border-radius: 999px;
        right: -120px;
        top: -120px;
        background: rgba(255, 201, 92, 0.28);
        filter: blur(4px);
    }

    .shelf-admin-hero::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        left: -70px;
        bottom: -90px;
        background: rgba(255, 122, 89, 0.28);
        filter: blur(5px);
    }

    .hero-bead {
        position: absolute;
        border-radius: 999px;
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.18);
        animation: beadPulse 3.5s ease-in-out infinite;
        z-index: 1;
    }

    .hero-bead.one {
        width: 16px;
        height: 16px;
        top: 28px;
        right: 150px;
        background: #ffc95c;
    }

    .hero-bead.two {
        width: 11px;
        height: 11px;
        top: 56px;
        right: 115px;
        background: #ff7a59;
        animation-delay: 0.9s;
    }

    .hero-bead.three {
        width: 12px;
        height: 12px;
        bottom: 36px;
        left: 42%;
        background: #7ad9c6;
        animation-delay: 1.6s;
    }

    .shelf-admin-hero > * {
        position: relative;
        z-index: 2;
    }

    .hero-kicker {
        margin: 0;
        font-size: 0.78rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: rgba(247, 242, 232, 0.82);
        font-weight: 700;
    }

    .hero-title {
        margin: 10px 0 8px;
        font-family: 'Unbounded', sans-serif;
        font-size: 1.7rem;
        line-height: 1.28;
    }

    .hero-subtitle {
        margin: 0;
        max-width: 760px;
        color: rgba(247, 242, 232, 0.9);
    }

    .hero-actions {
        margin-top: 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .hero-action-btn {
        text-decoration: none;
        border-radius: 999px;
        padding: 10px 16px;
        font-size: 0.86rem;
        font-weight: 700;
        color: #0f1d3a;
        background: #f7f2e8;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        box-shadow: 0 10px 18px rgba(0, 0, 0, 0.15);
    }

    .hero-action-btn:hover {
        transform: translateY(-2px);
        color: #0f1d3a;
    }

    .hero-action-btn.alt {
        color: #f7f2e8;
        background: rgba(247, 242, 232, 0.19);
        border: 1px solid rgba(247, 242, 232, 0.36);
        box-shadow: none;
    }

    .hero-action-btn.alt:hover {
        color: #f7f2e8;
    }

    .shelf-kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .shelf-kpi-card {
        border-radius: 18px;
        padding: 16px;
        border: 1px solid rgba(19, 33, 64, 0.13);
        background: rgba(255, 255, 255, 0.78);
        backdrop-filter: blur(7px);
        display: flex;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 14px 28px rgba(20, 47, 68, 0.08);
        transition: transform 0.24s ease;
    }

    .shelf-kpi-card:hover {
        transform: translateY(-3px);
    }

    .kpi-label {
        margin: 0;
        font-size: 0.8rem;
        color: #4e5d7f;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    .kpi-value {
        margin: 8px 0 0;
        font-family: 'Unbounded', sans-serif;
        color: #10172e;
        font-size: 1.48rem;
        line-height: 1;
    }

    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 1.05rem;
    }

    .kpi-icon.teal { background: linear-gradient(140deg, #178f78, #116557); }
    .kpi-icon.ocean { background: linear-gradient(140deg, #1d4f78, #133553); }
    .kpi-icon.indigo { background: linear-gradient(140deg, #4d5ea9, #303d7a); }
    .kpi-icon.amber { background: linear-gradient(140deg, #ffb33d, #e3891e); }
    .kpi-icon.forest { background: linear-gradient(140deg, #2a9a6f, #1d6c4f); }
    .kpi-icon.coral { background: linear-gradient(140deg, #ff7a59, #df5131); }

    .shelf-admin-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 14px;
    }

    .shelf-panel {
        border-radius: 18px;
        border: 1px solid rgba(19, 33, 64, 0.14);
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(7px);
        box-shadow: 0 14px 24px rgba(20, 47, 68, 0.08);
        padding: 18px;
    }

    .panel-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 800;
        color: #10172e;
    }

    .panel-subtitle {
        margin: 6px 0 14px;
        font-size: 0.86rem;
        color: #54607f;
    }

    .progress-item {
        margin-bottom: 12px;
    }

    .progress-item:last-child {
        margin-bottom: 0;
    }

    .progress-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        color: #455273;
        font-size: 0.87rem;
        font-weight: 700;
    }

    .progress-track {
        height: 10px;
        border-radius: 999px;
        background: #e4ebef;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: inherit;
    }

    .progress-fill.student {
        background: linear-gradient(90deg, #178f78, #2ab59a);
    }

    .progress-fill.teacher {
        background: linear-gradient(90deg, #ff7a59, #ffc95c);
    }

    .ops-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #d3dee6;
        font-size: 0.9rem;
    }

    .ops-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .ops-label {
        color: #556284;
        font-weight: 700;
    }

    .ops-value {
        color: #10172e;
        font-family: 'Unbounded', sans-serif;
        font-size: 0.9rem;
    }

    .status-pill {
        margin-top: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 0.79rem;
        font-weight: 700;
    }

    .status-pill.success {
        color: #125642;
        background: #d4f2e8;
    }

    .status-pill.warning {
        color: #7a4314;
        background: #ffe5cf;
    }

    @keyframes beadPulse {
        0%,
        100% {
            transform: translateY(0);
            opacity: 0.9;
        }
        50% {
            transform: translateY(-5px);
            opacity: 1;
        }
    }

    @media (max-width: 1199px) {
        .shelf-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991.98px) {
        .shelf-admin-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .admin-content {
            padding: 16px;
        }

        .shelf-admin-hero {
            padding: 20px;
        }

        .hero-title {
            font-size: 1.3rem;
        }

        .shelf-kpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="shelf-admin-wrap">
    <section class="shelf-admin-hero">
        <span class="hero-bead one"></span>
        <span class="hero-bead two"></span>
        <span class="hero-bead three"></span>

        <p class="hero-kicker">Ruang Kendali Perpustakaan</p>
        <h2 class="hero-title">Selamat datang, {{ Auth::user()->nama }}.</h2>
        <p class="hero-subtitle">
            Semua metrik utama perpustakaan ada di sini. Pantau koleksi, pengguna, dan transaksi harian dalam satu kanvas kerja dengan nuansa visual yang konsisten.
        </p>

        <div class="hero-actions">
            <a href="{{ route('admin.books.create') }}" class="hero-action-btn">
                <i class="bi bi-plus-circle"></i> Tambah Buku
            </a>
            <a href="{{ route('admin.anggota.create') }}" class="hero-action-btn alt">
                <i class="bi bi-person-plus"></i> Tambah Anggota
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="hero-action-btn alt">
                <i class="bi bi-arrow-repeat"></i> Kelola Peminjaman
            </a>
        </div>

    
    </section>

    <section class="shelf-kpi-grid">
        @foreach ($adminStats as $stat)
            <article class="shelf-kpi-card">
                <div>
                    <p class="kpi-label">{{ $stat['label'] }}</p>
                    <p class="kpi-value">{{ $stat['value'] }}</p>
                </div>
                <span class="kpi-icon {{ $stat['tone'] }}">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </span>
            </article>
        @endforeach
    </section>

    <section class="shelf-admin-grid">
        <article class="shelf-panel">
            <h3 class="panel-title">Distribusi Pengguna Portal</h3>
            <p class="panel-subtitle">Komposisi pengguna aktif antara murid dan guru saat ini.</p>

            <div class="progress-item">
                <div class="progress-row">
                    <span>Murid</span>
                    <span>{{ $persenAnggota }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill student" style="width: {{ $persenAnggota }}%"></div>
                </div>
            </div>

            <div class="progress-item">
                <div class="progress-row">
                    <span>Guru</span>
                    <span>{{ $persenGuru }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill teacher" style="width: {{ $persenGuru }}%"></div>
                </div>
            </div>

            <span class="status-pill success">
                <i class="bi bi-check-circle-fill"></i>
                Struktur akun tersinkron dengan baik
            </span>
        </article>

        <article class="shelf-panel">
            <h3 class="panel-title">Operasional Harian</h3>
            <p class="panel-subtitle">Snapshot kondisi inventaris dan transaksi.</p>

            <div class="ops-row">
                <span class="ops-label">Buku tersedia</span>
                <span class="ops-value">{{ $stokTersedia }}</span>
            </div>
            <div class="ops-row">
                <span class="ops-label">Sedang dipinjam</span>
                <span class="ops-value">{{ $dipinjam }}</span>
            </div>
            <div class="ops-row">
                <span class="ops-label">Koleksi perlu perhatian</span>
                <span class="ops-value">{{ $totalRusakHilang }}</span>
            </div>

            <span class="status-pill {{ $dipinjam > 0 ? 'warning' : 'success' }}">
                <i class="bi bi-activity"></i>
                {{ $dipinjam > 0 ? 'Ada peminjaman aktif yang harus dipantau' : 'Tidak ada peminjaman aktif saat ini' }}
            </span>
        </article>
    </section>
    @include('partials.leaderboard-peminjam')
</div>
@endsection