@extends('layout.anggota')
@section('title', 'Riwayat Peminjaman')

@section('content')
@php($portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota')

<style>
    .history-card {
        border-radius: 18px;
        border: 1px solid rgba(15, 74, 72, 0.14);
        background: #fff;
        box-shadow: 0 14px 24px rgba(15, 61, 60, 0.08);
        overflow: hidden;
    }

    .history-header {
        padding: 18px 22px;
        color: #fff;
        background: linear-gradient(140deg, #0f7ea8 0%, #0d5f80 70%, #f97360 160%);
    }

    .history-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .history-subtitle {
        margin: 6px 0 0;
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .history-body {
        padding: 18px;
    }

    .history-table th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #687d88;
        border-bottom-width: 1px;
    }

    .history-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
</style>

<div class="history-card">
    <div class="history-header">
        <h2 class="history-title">Riwayat Peminjaman</h2>
        <p class="history-subtitle">Daftar aktivitas peminjaman buku pada akun Anda.</p>
    </div>

    <div class="history-body">
        <div class="table-responsive">
            <table class="table history-table mb-0">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        <tr>
                            <td>{{ $item->book->title ?? '-' }}</td>
                            <td>{{ $item->borrow_date ?? '-' }}</td>
                            <td>{{ $item->return_date ?? '-' }}</td>
                            <td>{{ ucfirst($item->status ?? '-') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">Belum ada riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route($portalPrefix . '.beranda') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
