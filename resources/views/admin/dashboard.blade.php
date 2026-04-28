@extends('layout.admin')
@section('title', 'Beranda Admin')

@section('content')
<style>
    .admin-hero {
        border-radius: 22px;
        padding: 28px;
        color: #fff;
        background: linear-gradient(135deg, #0f8c80 0%, #0f6a63 55%, #ff8a3d 135%);
        box-shadow: 0 24px 42px rgba(15, 103, 96, 0.25);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .admin-hero::before {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        right: -110px;
        top: -120px;
        background: rgba(255, 255, 255, 0.15);
    }

    .admin-hero > * {
        position: relative;
        z-index: 1;
    }

    .admin-hero-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
    }

    .admin-hero-subtitle {
        margin: 8px 0 16px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .admin-quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .admin-quick-btn {
        text-decoration: none;
        border-radius: 999px;
        padding: 9px 16px;
        font-weight: 700;
        font-size: 0.9rem;
        color: #0f6d64;
        background: #fff;
        transition: transform 0.2s ease;
    }

    .admin-quick-btn:hover {
        transform: translateY(-2px);
        color: #0f6d64;
    }

    .metric-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #d6e7e4;
        padding: 18px;
        box-shadow: 0 10px 20px rgba(20, 59, 58, 0.07);
        height: 100%;
    }

    .metric-label {
        color: #6c8489;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .metric-value {
        margin: 7px 0 0;
        color: #17353c;
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1;
    }

    .metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
    }

    .metric-icon.books { background: linear-gradient(135deg, #0f8c80, #0f6a63); }
    .metric-icon.members { background: linear-gradient(135deg, #ff8a3d, #dd6b20); }
    .metric-icon.teachers { background: linear-gradient(135deg, #2d90d8, #1f6aa6); }
    .metric-icon.borrow { background: linear-gradient(135deg, #8968cc, #5c43a1); }
    .metric-icon.category { background: linear-gradient(135deg, #3eac61, #2d7f45); }
    .metric-icon.warning { background: linear-gradient(135deg, #d86045, #a33e2b); }

    .insight-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #d6e7e4;
        box-shadow: 0 10px 20px rgba(20, 59, 58, 0.06);
        padding: 20px;
        height: 100%;
    }

    .insight-title {
        margin: 0 0 14px;
        font-size: 1.08rem;
        font-weight: 800;
        color: #17353c;
    }

    .overview-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #d3e4e2;
        font-size: 0.92rem;
    }

    .overview-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .overview-label {
        color: #678186;
        font-weight: 700;
    }

    .overview-value {
        color: #16343b;
        font-weight: 800;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-top: 6px;
    }

    .status-pill.success {
        color: #155a43;
        background: #d8f5ea;
    }

    .status-pill.warning {
        color: #8a4f13;
        background: #ffedd4;
    }

    .bar-wrap {
        margin-top: 16px;
    }

    .bar-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6d848a;
        margin-bottom: 6px;
    }

    .bar-track {
        height: 9px;
        background: #ebf3f2;
        border-radius: 999px;
        overflow: hidden;
    }

    .bar-fill {
        height: 100%;
        border-radius: inherit;
    }

    .bar-fill.student { background: linear-gradient(90deg, #0f8c80, #18a297); }
    .bar-fill.teacher { background: linear-gradient(90deg, #ff8a3d, #f5b03a); }
</style>

<div class="admin-hero">
    <h2 class="admin-hero-title">Selamat Datang, {{ Auth::user()->nama }}</h2>
    <p class="admin-hero-subtitle">
        Anda masuk sebagai {{ Auth::user()->roleLabel() }}. Monitor seluruh aktivitas perpustakaan dari satu panel.
    </p>

    <div class="admin-quick-actions">
        <a href="{{ route('admin.books.create') }}" class="admin-quick-btn">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
        <a href="{{ route('admin.anggota.create') }}" class="admin-quick-btn">
            <i class="bi bi-person-plus"></i> Tambah Anggota
        </a>
        <a href="{{ route('admin.peminjaman.index') }}" class="admin-quick-btn">
            <i class="bi bi-arrow-repeat"></i> Kelola Peminjaman
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-4">
        <div class="metric-card d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Total Buku</div>
                <div class="metric-value">{{ $totalBuku }}</div>
            </div>
            <span class="metric-icon books"><i class="bi bi-journal-bookmark-fill"></i></span>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="metric-card d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Total Anggota</div>
                <div class="metric-value">{{ $totalAnggota }}</div>
            </div>
            <span class="metric-icon members"><i class="bi bi-people-fill"></i></span>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="metric-card d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Total Guru</div>
                <div class="metric-value">{{ $totalGuru }}</div>
            </div>
            <span class="metric-icon teachers"><i class="bi bi-mortarboard-fill"></i></span>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="metric-card d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Total Peminjaman</div>
                <div class="metric-value">{{ $totalPinjam }}</div>
            </div>
            <span class="metric-icon borrow"><i class="bi bi-arrow-left-right"></i></span>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="metric-card d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Kategori Buku</div>
                <div class="metric-value">{{ $totalKategori }}</div>
            </div>
            <span class="metric-icon category"><i class="bi bi-tags-fill"></i></span>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="metric-card d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Buku Rusak/Hilang</div>
                <div class="metric-value">{{ $totalRusakHilang }}</div>
            </div>
            <span class="metric-icon warning"><i class="bi bi-exclamation-triangle-fill"></i></span>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="insight-card">
            <h3 class="insight-title">Distribusi Pengguna</h3>

            @php
                $totalPenggunaPortal = max(1, $totalAnggota + $totalGuru);
                $persenAnggota = round(($totalAnggota / $totalPenggunaPortal) * 100);
                $persenGuru = round(($totalGuru / $totalPenggunaPortal) * 100);
            @endphp

            <div class="bar-wrap">
                <div class="d-flex justify-content-between bar-title">
                    <span>Murid</span>
                    <span>{{ $persenAnggota }}%</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill student" style="width: {{ $persenAnggota }}%"></div>
                </div>
            </div>

            <div class="bar-wrap">
                <div class="d-flex justify-content-between bar-title">
                    <span>Guru</span>
                    <span>{{ $persenGuru }}%</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill teacher" style="width: {{ $persenGuru }}%"></div>
                </div>
            </div>

            <div class="status-pill success">
                <i class="bi bi-check-circle-fill"></i>
                Sistem anggota aktif dan terdata
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="insight-card">
            <h3 class="insight-title">Operasional Harian</h3>

            <div class="overview-row">
                <span class="overview-label">Buku tersedia</span>
                <span class="overview-value">{{ $stokTersedia }}</span>
            </div>
            <div class="overview-row">
                <span class="overview-label">Sedang dipinjam</span>
                <span class="overview-value">{{ $dipinjam }}</span>
            </div>
            <div class="overview-row">
                <span class="overview-label">Koleksi perlu perhatian</span>
                <span class="overview-value">{{ $totalRusakHilang }}</span>
            </div>

            <div class="status-pill {{ $dipinjam > 0 ? 'warning' : 'success' }}">
                <i class="bi bi-activity"></i>
                {{ $dipinjam > 0 ? 'Ada transaksi peminjaman aktif' : 'Tidak ada peminjaman aktif saat ini' }}
            </div>
        </div>
    </div>
</div>
@endsection
