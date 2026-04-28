@extends('layout.kepala')
@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<style>
    .executive-hero {
        border-radius: 24px;
        padding: 28px;
        color: #fff;
        background: linear-gradient(140deg, #0e6b69 0%, #0b4f4d 70%, #c79a3f 150%);
        box-shadow: 0 28px 48px rgba(11, 79, 77, 0.25);
        position: relative;
        overflow: hidden;
    }

    .executive-hero::before {
        content: '';
        position: absolute;
        width: 340px;
        height: 340px;
        border-radius: 999px;
        right: -155px;
        top: -130px;
        background: rgba(255, 255, 255, 0.16);
    }

    .executive-hero > * {
        position: relative;
        z-index: 1;
    }

    .executive-title {
        margin: 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.35rem;
        font-weight: 700;
        line-height: 1;
    }

    .executive-subtitle {
        margin: 10px 0 0;
        max-width: 700px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .executive-meta {
        margin-top: 12px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.84rem;
        border-radius: 999px;
        padding: 8px 12px;
        background: rgba(255, 255, 255, 0.16);
    }

    .exec-card {
        border-radius: 18px;
        border: 1px solid rgba(15, 74, 72, 0.16);
        background: #fff;
        padding: 18px;
        box-shadow: 0 14px 26px rgba(19, 67, 66, 0.08);
        height: 100%;
    }

    .exec-label {
        font-size: 0.78rem;
        letter-spacing: 0.68px;
        text-transform: uppercase;
        font-weight: 700;
        color: #6d807f;
    }

    .exec-value {
        margin-top: 8px;
        color: #1f2d2c;
        font-size: 1.9rem;
        font-weight: 800;
        line-height: 1;
    }

    .exec-kpi {
        border-radius: 14px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.87rem;
        margin-top: 14px;
    }

    .exec-kpi.good {
        color: #145447;
        background: #def5eb;
    }

    .exec-kpi.warn {
        color: #8a5718;
        background: #ffedcf;
    }

    .panel {
        margin-top: 20px;
        border-radius: 18px;
        border: 1px solid rgba(15, 74, 72, 0.16);
        background: #fff;
        box-shadow: 0 12px 22px rgba(19, 67, 66, 0.07);
        padding: 20px;
    }

    .panel-title {
        margin: 0 0 14px;
        color: #223534;
        font-size: 1.08rem;
        font-weight: 800;
    }

    .row-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px dashed #d8e4e3;
        padding: 9px 0;
        font-size: 0.9rem;
    }

    .row-item:last-child {
        border-bottom: none;
    }

    .row-item-label {
        color: #627978;
        font-weight: 700;
    }

    .row-item-value {
        color: #203433;
        font-weight: 800;
    }

    .history-table th {
        color: #607675;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom-width: 1px;
    }

    .history-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .status-pill.borrowed {
        color: #7c4b18;
        background: #ffe9cc;
    }

    .status-pill.returned {
        color: #155347;
        background: #dff4eb;
    }

    @media (max-width: 768px) {
        .executive-title {
            font-size: 1.8rem;
        }
    }
</style>

<div class="executive-hero mb-3">
    <h2 class="executive-title">Ringkasan Strategis Perpustakaan</h2>
    <p class="executive-subtitle">
        Dashboard ini memberi gambaran performa literasi sekolah secara menyeluruh: kekuatan koleksi, aktivitas peminjaman, serta distribusi pengguna aktif.
    </p>
    <span class="executive-meta">
        <i class="bi bi-calendar2-week"></i>
        Update data: {{ now()->format('d M Y H:i') }}
    </span>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="exec-card">
            <div class="exec-label">Total Koleksi Buku</div>
            <div class="exec-value">{{ $totalBuku }}</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="exec-card">
            <div class="exec-label">Pengguna Aktif</div>
            <div class="exec-value">{{ $totalPenggunaAktif }}</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="exec-card">
            <div class="exec-label">Total Peminjaman</div>
            <div class="exec-value">{{ $totalPeminjaman }}</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="exec-card">
            <div class="exec-label">Kesehatan Koleksi</div>
            <div class="exec-value">{{ $kesehatanKoleksi }}%</div>
            <div class="exec-kpi {{ $kesehatanKoleksi >= 80 ? 'good' : 'warn' }}">
                {{ $kesehatanKoleksi >= 80 ? 'Kondisi koleksi baik' : 'Perlu penanganan koleksi rusak/hilang' }}
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-4">
        <div class="panel h-100">
            <h3 class="panel-title">Komposisi Pengguna</h3>

            <div class="row-item">
                <span class="row-item-label">Murid</span>
                <span class="row-item-value">{{ $totalSiswa }}</span>
            </div>
            <div class="row-item">
                <span class="row-item-label">Guru</span>
                <span class="row-item-value">{{ $totalGuru }}</span>
            </div>
            <div class="row-item">
                <span class="row-item-label">Kategori Buku</span>
                <span class="row-item-value">{{ $totalKategori }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel h-100">
            <h3 class="panel-title">Status Peminjaman</h3>

            <div class="row-item">
                <span class="row-item-label">Sedang dipinjam</span>
                <span class="row-item-value">{{ $peminjamanAktif }}</span>
            </div>
            <div class="row-item">
                <span class="row-item-label">Sudah kembali</span>
                <span class="row-item-value">{{ $peminjamanSelesai }}</span>
            </div>
            <div class="row-item">
                <span class="row-item-label">Potensi terlambat</span>
                <span class="row-item-value">{{ $peminjamanTerlambat }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel h-100">
            <h3 class="panel-title">Kategori Terpopuler</h3>

            @forelse($kategoriPopuler as $kategori)
                <div class="row-item">
                    <span class="row-item-label">{{ $kategori->name_category }}</span>
                    <span class="row-item-value">{{ $kategori->books_count }} buku</span>
                </div>
            @empty
                <div class="text-muted">Belum ada data kategori.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="panel">
    <h3 class="panel-title">Aktivitas Peminjaman Terbaru</h3>

    <div class="table-responsive">
        <table class="table history-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamanTerbaru as $item)
                    <tr>
                        <td>{{ $item->user->nama ?? '-' }}</td>
                        <td>{{ $item->book->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</td>
                        <td>
                            <span class="status-pill {{ $item->status === 'dikembalikan' ? 'returned' : 'borrowed' }}">
                                <i class="bi {{ $item->status === 'dikembalikan' ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">Belum ada transaksi peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
