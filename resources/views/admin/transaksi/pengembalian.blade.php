@extends('layout.admin')
@section('title', 'Terima Pengembalian')

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

    .stat-card.returning {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(245, 158, 11, 0) 100%);
    }

    .stat-card.returning::before {
        background: #f59e0b;
    }

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
        color: #f59e0b;
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

    .datatable tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.25s ease;
    }

    .datatable tbody tr:hover {
        background: linear-gradient(135deg, rgba(255, 122, 89, 0.04) 0%, rgba(255, 122, 89, 0) 100%);
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

    .book-item:last-child {
        margin-bottom: 0;
    }

    .book-title {
        font-weight: 600;
        color: #10172e;
    }

    .book-qty {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        background: linear-gradient(135deg, #ff7a59 0%, #ff5252 100%);
        color: white;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(255, 122, 89, 0.22);
    }

    .book-qty .qty-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.75rem;
        min-width: 20px;
        height: 20px;
    }

    .book-qty.qty-zero {
        background: #f1f5f9;
        color: #64748b;
        box-shadow: none;
        border: 1px solid #cbd5e1;
    }

    .book-qty.qty-zero .qty-num {
        background: #cbd5e1;
        color: #334155;
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

    .status-returning {
        background: #fde68a;
        color: #92400e;
    }

    .btn-return {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-return:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .alert-custom {
        border: none;
        border-radius: 14px;
        padding: 1.3rem 1.6rem;
        margin-bottom: 1.5rem;
        border-left: 5px solid;
    }

    .alert-success-custom {
        background: #d1fae5;
        border-left-color: #10b981;
        color: #065f46;
    }

    .alert-error-custom {
        background: #fee2e2;
        border-left-color: #ef4444;
        color: #7f1d1d;
    }

    @media (max-width: 768px) {
        .admin-header {
            padding: 2.2rem 1.5rem;
        }

        .admin-header h1 {
            font-size: 1.8rem;
        }

        .table-wrapper {
            padding: 1.5rem;
        }

        .datatable {
            font-size: 0.85rem;
        }

        .btn-return {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="admin-header">
        <div>
            <h1><i class="bi bi-arrow-left-right"></i> Terima Pengembalian</h1>
            <p>Konfirmasi pengembalian buku dan tentukan kondisi buku serta denda kerusakan/keterlambatan.</p>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="bi bi-check-circle"></i> <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-custom alert-error-custom">
            <i class="bi bi-exclamation-circle"></i> <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    {{-- STATISTICS SECTION --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card returning">
                <div class="stat-label">Sedang Diproses Kembali</div>
                <div class="stat-value">{{ $peminjaman->count() }}</div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-wrapper">
        <h4 class="table-title">
            <i class="bi bi-list-check"></i> Daftar Pengembalian Menunggu Konfirmasi
        </h4>

        {{-- SEARCH BAR --}}
        <div class="mb-4">
            <input type="text" class="form-control" id="peminjam_search" placeholder="🔍 Cari nama murid atau buku...">
        </div>

        @if($peminjaman->count() > 0)
            <div class="table-responsive">
                <table class="table datatable mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Murid</th>
                            <th>Buku Diajukan</th>
                            <th width="120">Jumlah</th>
                            <th width="120">Tgl Ajuan</th>
                            <th width="120">Status</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $item)
                            <tr>
                                <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}</td>
                                <td>
                                    <span class="student-name peminjam-nama">{{ $item->user->nama ?? '-' }}</span>
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
                                    @php $qtySum = $item->details->sum('qty'); @endphp
                                    <span class="book-qty {{ $qtySum == 0 ? 'qty-zero' : '' }}">
                                        <span class="qty-num">{{ $qtySum }}</span> Buku
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge status-returning">Proses Kembali</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.peminjaman.process-return', $item->id) }}" class="btn-return">
                                        <i class="bi bi-box-arrow-in-down"></i> Terima Pengembalian
                                    </a>
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
                <p class="text-muted mt-3">Tidak ada antrean pengembalian buku yang harus dikonfirmasi</p>
            </div>
        @endif
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('peminjam_search');
        const rows = document.querySelectorAll('table tbody tr');

        function filterRows() {
            const query = searchInput.value.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterRows);
    });
</script>
@endsection
