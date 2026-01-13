@extends('layout.admin')
@section('content')
@section('title','Beranda')

<style>
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .welcome-card h2 {
        font-size: 32px;
        margin-bottom: 10px;
        color: white;
    }

    .welcome-card p {
        font-size: 16px;
        opacity: 0.95;
        margin: 0;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-top: 4px solid #667eea;
        margin-bottom: 20px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
    }

    .stat-card.green {
        border-top-color: #11998e;
    }

    .stat-card.red {
        border-top-color: #f5576c;
    }

    .stat-card.blue {
        border-top-color: #4facfe;
    }

    .stat-icon {
        font-size: 40px;
        margin-bottom: 15px;
        color: #667eea;
    }

    .stat-card.green .stat-icon {
        color: #11998e;
    }

    .stat-card.red .stat-icon {
        color: #f5576c;
    }

    .stat-card.blue .stat-icon {
        color: #4facfe;
    }

    .stat-number {
        font-size: 36px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 14px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dashboard-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .dashboard-section h4 {
        color: #333;
        margin-bottom: 20px;
        font-weight: 700;
        border-bottom: 3px solid #667eea;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-section h4 i {
        color: #667eea;
    }

    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 8px;
    }

    .info-box-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .info-box-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #555;
        font-weight: 500;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<!-- Welcome Section -->
<div class="welcome-card">
    <h2>
        <i class="fas fa-wave-hand"></i> Selamat Datang, <b>{{Auth::user()->nama}}</b>!
    </h2>
    <p>
        <i class="fas fa-user-tie"></i> Anda login sebagai 
        <b>@if (Auth::user()->role == 1)
            Administrator
        @elseif (Auth::user()->role == 0)
            Anggota
        @endif
        </b> di Sistem Perpustakaan Digital
    </p>
</div>

<!-- Statistik Dashboard -->
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-book"></i></div>
            <div class="stat-number">{{ $totalBuku }}</div>
            <div class="stat-label">Total Buku</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $totalAnggota }}</div>
            <div class="stat-label">Total Anggota</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-handshake"></i></div>
            <div class="stat-number">{{ $totalPinjam }}</div>
            <div class="stat-label">Total Peminjaman</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-list"></i></div>
            <div class="stat-number">{{ $totalKategori }}</div>
            <div class="stat-label">Kategori Buku</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-section">
    <h4><i class="fas fa-rocket"></i> Aksi Cepat</h4>
    <a href="{{ route('admin.books.create') }}" class="quick-action" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; margin-right: 10px; margin-bottom: 10px; transition: all 0.3s ease;"><i class="fas fa-plus"></i> Tambah Buku</a>
    <a href="{{ route('admin.anggota.create') }}" class="quick-action" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; margin-right: 10px; margin-bottom: 10px; transition: all 0.3s ease;"><i class="fas fa-plus"></i> Tambah Anggota</a>
    <a href="{{ route('admin.peminjaman.create') }}" class="quick-action" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; margin-right: 10px; margin-bottom: 10px; transition: all 0.3s ease;"><i class="fas fa-plus"></i> Proses Peminjaman</a>
</div>

<!-- Info Perpustakaan -->
<div class="dashboard-section">
    <h4><i class="fas fa-chart-bar"></i> Ringkasan Buku</h4>
    <div class="info-box">
        <div class="info-box-item">
            <span class="info-label">Buku Tersedia</span>
            <span class="badge badge-success">{{ $stokTersedia }}</span>
        </div>
        <div class="info-box-item">
            <span class="info-label">Sedang Dipinjam</span>
            <span class="badge badge-warning">{{ $dipinjam }}</span>
        </div>
        <div class="info-box-item">
            <span class="info-label">Rusak/Hilang</span>
            <span class="badge badge-danger">{{ $totalRusakHilang }}</span>
        </div>
    </div>
</div>
@endsection