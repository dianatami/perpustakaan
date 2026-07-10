@extends('layout.admin')
@section('title', 'Statistik Perpustakaan')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .admin-header {
        background: linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        color: white;
        padding: 3.5rem 2.5rem;
        border-radius: 24px;
        margin-bottom: 2.5rem;
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.12);
        position: relative;
        overflow: hidden;
    }

    .admin-header::before {
        content: '';
        position: absolute;
        width: 350px;
        height: 350px;
        border-radius: 999px;
        background: rgba(255, 201, 92, 0.18);
        top: -120px;
        right: -100px;
    }

    .admin-header::after {
        content: '';
        position: absolute;
        width: 250px;
        height: 250px;
        border-radius: 999px;
        background: rgba(23, 143, 120, 0.15);
        bottom: -80px;
        left: -80px;
    }

    .admin-header h1 {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 0.6rem;
        position: relative;
        z-index: 1;
    }

    .admin-header p {
        font-size: 1.1rem;
        opacity: 0.92;
        position: relative;
        z-index: 1;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 18px;
        padding: 1.8rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        transition: all 0.3s ease;
        border-left: 6px solid;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 120px;
        height: 120px;
        border-radius: 999px;
        opacity: 0.06;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
    }

    .stat-card.blue {
        border-left-color: #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0) 100%);
    }
    .stat-card.blue::before { background: #3b82f6; }

    .stat-card.teal {
        border-left-color: #0f8c80;
        background: linear-gradient(135deg, rgba(15, 140, 128, 0.05) 0%, rgba(15, 140, 128, 0) 100%);
    }
    .stat-card.teal::before { background: #0f8c80; }

    .stat-card.orange {
        border-left-color: #f97316;
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(249, 115, 22, 0) 100%);
    }
    .stat-card.orange::before { background: #f97316; }

    .stat-card.red {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, rgba(239, 68, 68, 0) 100%);
    }
    .stat-card.red::before { background: #ef4444; }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.7rem;
    }

    .stat-value {
        font-size: 2.4rem;
        font-weight: 800;
        color: #1e293b;
    }

    .table-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 2.2rem;
        margin-top: 1.5rem;
    }

    .table-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #10172e;
        margin-bottom: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .table-title i {
        color: #ff7a59;
        font-size: 1.6rem;
    }

    .leaderboard-table thead th {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: none;
        border-bottom: 2px solid #e2e8f0;
        color: #475569;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 1.3rem 1rem;
    }

    .leaderboard-table tbody td {
        vertical-align: middle;
        border-color: #e2e8f0;
        padding: 1.1rem;
        color: #334155;
    }

    .trophy-1 { color: #f59e0b; font-size: 1.25rem; }
    .trophy-2 { color: #cbd5e1; font-size: 1.25rem; }
    .trophy-3 { color: #b45309; font-size: 1.25rem; }
</style>

<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="admin-header">
        <div>
            <h1><i class="bi bi-graph-up-arrow"></i> Statistik & Metrik Perpustakaan</h1>
            <p>Visualisasi ringkasan inventaris buku, metrik keaktifan anggota, dan status peminjaman.</p>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card blue">
                <div class="stat-label">Total Judul Buku</div>
                <div class="stat-value">{{ $totalBuku }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card teal">
                <div class="stat-label">Stok Buku Tersedia</div>
                <div class="stat-value">{{ $stokTersedia }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card orange">
                <div class="stat-label">Buku Sedang Dipinjam</div>
                <div class="stat-value">{{ $dipinjam }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card red">
                <div class="stat-label">Buku Rusak / Hilang</div>
                <div class="stat-value">{{ $totalRusak + $totalHilang }}</div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card blue">
                <div class="stat-label">Total Anggota (Siswa)</div>
                <div class="stat-value">{{ $totalAnggota }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card teal">
                <div class="stat-label">Total Anggota (Guru)</div>
                <div class="stat-value">{{ $totalGuru }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card orange">
                <div class="stat-label">Total Kategori Buku</div>
                <div class="stat-value">{{ $totalKategori }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card red">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $totalPinjam }}</div>
            </div>
        </div>
    </div>

    {{-- LEADERBOARD --}}
    <div class="table-wrapper">
        <h4 class="table-title">
            <i class="bi bi-trophy-fill trophy-1"></i> Peminjam Paling Aktif (Leaderboard)
        </h4>
        @if($leaderboardSiswa->count() > 0)
            <div class="table-responsive">
                <table class="table leaderboard-table mb-0">
                    <thead>
                        <tr>
                            <th width="80">Peringkat</th>
                            <th>Nama Anggota</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="200" class="text-end">Jumlah Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaderboardSiswa as $siswa)
                            <tr>
                                <td>
                                    @if($loop->iteration == 1)
                                        <span class="fw-bold"><i class="bi bi-trophy-fill trophy-1"></i> 1</span>
                                    @elseif($loop->iteration == 2)
                                        <span class="fw-bold"><i class="bi bi-trophy-fill trophy-2"></i> 2</span>
                                    @elseif($loop->iteration == 3)
                                        <span class="fw-bold"><i class="bi bi-trophy-fill trophy-3"></i> 3</span>
                                    @else
                                        <span class="fw-bold text-muted">&nbsp;&nbsp;&nbsp;{{ $loop->iteration }}</span>
                                    @endif
                                </td>
                                <td><span class="fw-bold">{{ $siswa->nama }}</span></td>
                                <td>{{ $siswa->email }}</td>
                                <td>
                                    @if($siswa->role == '1')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($siswa->role == '2')
                                        <span class="badge bg-warning text-dark">Guru</span>
                                    @else
                                        <span class="badge bg-info text-dark">Siswa</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-primary">{{ $siswa->total_peminjaman }} Kali</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-award" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">Data keaktifan murid belum terekam</p>
            </div>
        @endif
    </div>
</div>
@endsection
