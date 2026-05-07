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

    .countdown-badge {
        min-width: 150px;
        text-align: center;
        font-weight: 700;
    }
</style>

<div class="history-card">
    <div class="history-header">
        <h2 class="history-title">Riwayat Peminjaman</h2>
        <p class="history-subtitle">Daftar aktivitas peminjaman buku pada akun Anda.</p>
    </div>

    <div class="history-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table history-table mb-0">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        <tr>
                            <td>{{ $item->book->title ?? '-' }}</td>
                            <td>{{ $item->borrow_date ?? '-' }}</td>
                            <td>{{ $item->return_date ?? '-' }}</td>
                            <td>
                                @if(($item->status ?? null) === 'menunggu_acc')
                                    Menunggu ACC
                                @elseif(($item->status ?? null) === 'dipinjam')
                                    Dipinjam
                                @elseif(($item->status ?? null) === 'ditolak')
                                    Ditolak
                                @elseif(($item->status ?? null) === 'proses_kembali')
                                    Proses Pengembalian
                                @elseif(($item->status ?? null) === 'kembali')
                                    Sudah dikembalikan
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if(($item->status ?? null) === 'dipinjam')
                                    <form action="{{ route($portalPrefix . '.pengembalian.store', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-return-left"></i> Kembalikan
                                        </button>
                                    </form>
=======
                            <td>{{ $item->borrow_date ? \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') : '-' }}</td>
                            <td>{{ $item->return_date ? \Carbon\Carbon::parse($item->return_date)->format('d M Y') : '-' }}</td>
                            <td colspan="5" class="text-muted">Belum ada riwayat peminjaman.</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = Array.from(document.querySelectorAll('[data-countdown]'));

        if (!timers.length) {
            return;
        }

        const formatCountdown = (totalSeconds) => {
            const safeSeconds = Math.max(0, totalSeconds);
            const days = Math.floor(safeSeconds / 86400);
            const hours = Math.floor((safeSeconds % 86400) / 3600);
            const minutes = Math.floor((safeSeconds % 3600) / 60);
            const seconds = safeSeconds % 60;

            return `${days}h ${String(hours).padStart(2, '0')}j ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}d`;
        };

        const updateTimers = () => {
            const now = Date.now();

            timers.forEach((timer) => {
                const expiresAt = timer.getAttribute('data-expires-at');

                if (!expiresAt) {
                    timer.textContent = '-';
                    return;
                }

                const expiresMs = new Date(expiresAt).getTime();
                const remaining = Math.max(0, Math.floor((expiresMs - now) / 1000));

                if (remaining <= 0) {
                    timer.textContent = 'Terlambat';
                    timer.classList.remove('text-bg-warning');
                    timer.classList.add('text-bg-danger');
                    return;
                }

                timer.textContent = formatCountdown(remaining);
            });
        };

        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>
@endsection
