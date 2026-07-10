@extends('layout.admin')
@section('title', 'Laporan Pengembalian')

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

    .btn-print {
        background: white;
        color: #0f8c80;
        border: 2px solid #0f8c80;
        padding: 0.8rem 1.8rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: transparent;
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
        background-color: #0f8c80;
        color: white;
    }

    .table-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 2.2rem;
        margin-top: 2rem;
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

    .datatable {
        margin-bottom: 0;
    }

    .datatable thead th {
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

    .datatable tbody td {
        vertical-align: middle;
        border-color: #e2e8f0;
        padding: 1.1rem;
        color: #334155;
    }

    .student-name {
        font-weight: 700;
        color: #0f172a;
        display: block;
        margin-bottom: 0.35rem;
    }

    .student-email {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .book-list {
        font-size: 0.9rem;
    }

    .book-item {
        background: linear-gradient(135deg, #fff8f2 0%, #fef3e8 100%);
        border-left: 3px solid #ff7a59;
        padding: 0.6rem 0.9rem;
        border-radius: 8px;
        margin-bottom: 0.4rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-badge {
        padding: 0.65rem 1.1rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .status-returned {
        background: #c6f6d5;
        color: #22543d;
    }

    /* Print styling rules */
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        .admin-sidebar,
        .admin-topbar,
        .btn-print,
        .pagination,
        footer,
        .admin-header::before,
        .admin-header::after {
            display: none !important;
        }
        .admin-shell {
            display: block !important;
        }
        .admin-main {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .admin-content {
            padding: 0 !important;
            max-width: 100% !important;
        }
        .table-wrapper {
            box-shadow: none !important;
            padding: 0 !important;
            margin-top: 0 !important;
        }
        .admin-header {
            background: none !important;
            color: black !important;
            padding: 0 !important;
            margin-bottom: 2rem !important;
            border-bottom: 2px solid #333 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .admin-header h1 {
            font-size: 2rem !important;
            margin-bottom: 0.2rem !important;
        }
        .admin-header p {
            color: #555 !important;
        }
    }
</style>

<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-file-earmark-arrow-down-fill"></i> Laporan Pengembalian</h1>
            <p>Rekapitulasi laporan peminjaman buku yang telah selesai dikembalikan oleh murid.</p>
        </div>
        <button onclick="window.print()" class="btn-print">
            <i class="bi bi-printer-fill"></i> Cetak Laporan
        </button>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-wrapper">
        <h4 class="table-title">
            <i class="bi bi-file-earmark-spreadsheet"></i> Rekapitulasi Data Pengembalian
        </h4>

        @if($peminjaman->count() > 0)
            <div class="table-responsive">
                <table class="table datatable mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Murid</th>
                            <th>Buku Diajukan</th>
                            <th width="120">Jumlah</th>
                            <th width="120">Tgl Pinjam</th>
                            <th width="120">Tgl Kembali</th>
                            <th width="120">Status</th>
                            <th width="120">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $item)
                            <tr>
                                <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}</td>
                                <td>
                                    <span class="student-name">{{ $item->user->nama ?? '-' }}</span>
                                    <span class="student-email">{{ $item->user->email ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="book-list">
                                        @if($item->details->count() > 0)
                                            @foreach($item->details as $detail)
                                                <div class="book-item">
                                                    <span class="book-title">{{ $detail->book->title ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada buku</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="book-qty">{{ $item->details->sum('qty') }} Buku</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge status-returned">Kembali</span>
                                </td>
                                <td>
                                    @if($item->denda && (int)$item->denda > 0)
                                        <div class="fw-bold text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $peminjaman->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">Tidak ada data pengembalian buku untuk dilaporkan</p>
            </div>
        @endif
    </div>
</div>
@endsection
