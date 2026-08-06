@extends('layout.admin')
@section('title', 'Riwayat Transaksi')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    * {font-family: 'Plus Jakarta Sans', sans-serif;}
    .admin-header {background: linear-gradient(120deg,rgba(16,23,46,0.94) 0%,rgba(29,79,120,0.9) 42%,rgba(255,122,89,0.88) 100%);color:#fff;padding:3.5rem 2.5rem;border-radius:24px;margin-bottom:2.5rem;box-shadow:0 25px 50px rgba(15,23,42,.12);position:relative;overflow:hidden;}
    .admin-header::before,.admin-header::after{content:'';position:absolute;border-radius:999px;}
    .admin-header::before{width:350px;height:350px;background:rgba(255,201,92,.18);top:-120px;right:-100px;}
    .admin-header::after{width:250px;height:250px;background:rgba(23,143,120,.15);bottom:-80px;left:-80px;}
    .admin-header h1{font-size:2.4rem;font-weight:800;margin-bottom:.6rem;z-index:1;position:relative;}
    .admin-header p{font-size:1.1rem;opacity:.92;z-index:1;position:relative;}
    .table-wrapper{background:#fff;border-radius:20px;box-shadow:0 10px 30px rgba(15,23,42,.08);padding:2.2rem;margin-top:2rem;}
    .table-title{font-size:1.5rem;font-weight:800;color:#10172e;margin-bottom:1.8rem;display:flex;align-items:center;gap:.8rem;}
    .table-title i{color:#ff7a59;font-size:1.6rem;}
    .datatable thead th{background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);border:none;border-bottom:2px solid #e2e8f0;color:#475569;font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;padding:1.3rem 1rem;}
    .datatable tbody td{vertical-align:middle;border-color:#e2e8f0;padding:1.1rem;color:#334155;}
    .status-badge{padding:.65rem 1.1rem;border-radius:999px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;display:inline-block;}
    .status-pending{background:#fef3c7;color:#92400e;}
    .status-returned{background:#d1fae5;color:#065f46;}
    .status-overdue{background:#fee2e2;color:#7f1d1d;}
    .status-borrowed{background:#dbeafe;color:#0c2d6b;}
    .action-buttons{display:flex;gap:.6rem;flex-wrap:wrap;}
    .btn-detail{background:#6b7280;color:#fff;border:none;padding:.6rem 1.1rem;border-radius:10px;font-weight:700;font-size:.8rem;}
    .modal-header{background:linear-gradient(120deg,rgba(16,23,46,0.94) 0%,rgba(29,79,120,0.9) 42%,rgba(255,122,89,0.88) 100%);border:none;}
    .modal-header .btn-close{filter:brightness(0) invert(1);}
    .modal-title{font-weight:800;color:#fff;}
    @media (max-width:768px){.admin-header{padding:2.2rem 1.5rem;}.admin-header h1{font-size:1.8rem;}.table-wrapper{padding:1.5rem;}.datatable{font-size:.85rem;}.action-buttons{flex-direction:column;}.btn-detail{width:100%;text-align:center;}}
    .filter-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        padding: 1.5rem 1.8rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .filter-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.2rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .filter-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-card-title i {
        color: #0f8c80;
        font-size: 1.15rem;
    }
    .filter-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: block;
    }
    .filter-control {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.65rem 0.9rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #1e293b;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        width: 100%;
    }
    .filter-control:focus {
        background-color: #fff;
        border-color: #0f8c80;
        box-shadow: 0 0 0 3px rgba(15, 140, 128, 0.15);
        outline: none;
    }
    .btn-reset-filter {
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        color: #64748b;
        background: #f1f5f9;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-reset-filter:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
</style>

<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="admin-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="bi bi-clock-history"></i> Riwayat Transaksi</h1>
                <p>Riwayat lengkap semua transaksi perpustakaan.</p>
            </div>
        </div>
    </div>

    @php
        $uniqueNames = $peminjaman->pluck('user.nama')->unique()->filter();
        $uniqueBooks = $peminjaman->pluck('details')->flatten()->pluck('book.title')->unique()->filter();
        $uniqueCategories = $peminjaman->pluck('details')->flatten()->map(function($detail) {
            return $detail->book->category->name ?? $detail->book->kategori->nama ?? $detail->book->kategori->name ?? null;
        })->unique()->filter();
    @endphp

    {{-- FILTERS --}}
    <div class="filter-card">
        <div class="filter-card-header">
            <h6 class="filter-card-title">
                <i class="bi bi-funnel-fill"></i> Filter & Cari Data Riwayat
            </h6>
            <button type="button" class="btn-reset-filter" onclick="resetFilters()">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
            </button>
        </div>
        <div class="row g-3">
            <div class="col-xl-2 col-lg-4 col-md-6">
                <label for="period_start" class="filter-label">Dari Tgl Pinjam</label>
                <input type="date" class="form-control filter-control" id="period_start">
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <label for="period_end" class="filter-label">Sampai Tgl Pinjam</label>
                <input type="date" class="form-control filter-control" id="period_end">
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <label for="name_filter" class="filter-label">Nama Anggota</label>
                <select class="form-select filter-control" id="name_filter">
                    <option value="">Semua Nama</option>
                    @foreach($uniqueNames as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <label for="book_filter" class="filter-label">Judul Buku</label>
                <select class="form-select filter-control" id="book_filter">
                    <option value="">Semua Judul Buku</option>
                    @foreach($uniqueBooks as $title)
                        <option value="{{ $title }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <label for="category_filter" class="filter-label">Kategori</label>
                <select class="form-select filter-control" id="category_filter">
                    <option value="">Semua Kategori</option>
                    @foreach($uniqueCategories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <label for="status_filter" class="filter-label">Status</label>
                <select class="form-select filter-control" id="status_filter">
                    <option value="">Semua Status</option>
                    <option value="menunggu_acc">Menunggu</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="kembali">Kembali</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <h4 class="table-title"><i class="bi bi-card-list"></i> Daftar Riwayat</h4>
        @if($peminjaman->count() > 0)
            <div class="table-responsive">
                <table class="table datatable mb-0">
                    <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Anggota</th>
                        <th>Kategori</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($peminjaman as $item)
                        <tr data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}">
                            <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}</td>
                            <td>
                                <span class="member-name">{{ $item->user->nama ?? '-' }}</span><br>
                                <small class="text-muted">{{ $item->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                @foreach($item->details as $detail)
                                    <div class="category-item text-muted" style="font-size:0.8rem;">
                                        {{ $detail->book->category->name ?? $detail->book->kategori->nama ?? $detail->book->kategori->name ?? '-' }}
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @foreach($item->details as $detail)
                                    <div class="book-item">{{ $detail->book->title ?? '-' }}</div>
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                            <td>{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $statusClass = 'status-badge';
                                    $statusLabel = $item->status;
                                    if(in_array($item->status, ['menunggu_acc'])) { $statusClass .= ' status-pending'; $statusLabel = 'Menunggu'; }
                                    elseif(in_array($item->status, ['dipinjam'])) { $statusClass .= ' status-borrowed'; $statusLabel = 'Dipinjam'; }
                                    elseif(in_array($item->status, ['kembali'])) { $statusClass .= ' status-returned'; $statusLabel = 'Kembali'; }
                                    elseif(in_array($item->status, ['ditolak'])) { $statusClass .= ' status-overdue'; $statusLabel = 'Ditolak'; }
                                    elseif(in_array($item->status, ['terlambat'])) { $statusClass .= ' status-overdue'; $statusLabel = 'Terlambat'; }
                                @endphp
                                <span class="{{ $statusClass }}" data-status="{{ $item->status }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn-detail" onclick="showDetailModal({{ $item->id }})">
                                    <i class="bi bi-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $peminjaman->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:#cbd5e1;"></i>
                <p class="text-muted mt-3">Tidak ada riwayat transaksi.</p>
            </div>
        @endif
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:none;">
            <div class="modal-header">
                <h5 class="modal-title">📄 Detail Riwayat Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2.2rem;">
                <p><strong>Nama Anggota:</strong> <span id="detailMemberName"></span></p>
                <p><strong>Tanggal Pinjam:</strong> <span id="detailBorrowedAt"></span></p>
                <p><strong>Jatuh Tempo:</strong> <span id="detailDueDate"></span></p>
                <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                <p><strong>Denda:</strong> <span id="detailFine"></span></p>
                <hr>
                <h6>Detail Buku:</h6>
                <ul id="detailBookList"></ul>
            </div>
            <div class="modal-footer" style="padding:1.8rem;border-top:1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:12px;font-weight:700;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const detailData = {
        @foreach($peminjaman as $item)
        {{ $item->id }}: {
            memberName: "{{ addslashes($item->user->nama ?? '') }}",
            borrowedAt: "{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}",
            dueDate: "{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '-' }}",
            status: "{{ $item->status }}",
            fine: "{{ (method_exists($item, 'calculateFine') && $item->calculateFine()) ? 'Rp '.number_format($item->calculateFine(),0,',','.') : '-' }}",
            books: [
                @foreach($item->details as $detail)
                {
                    title: "{{ addslashes($detail->book->title ?? '') }}",
                    category: "{{ addslashes($detail->book->category->name ?? $detail->book->kategori->nama ?? $detail->book->kategori->name ?? '') }}",
                    qty: {{ $detail->qty }}
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }@if(!$loop->last),@endif
        @endforeach
    };

    const rows = document.querySelectorAll('table tbody tr');
    const periodStart = document.getElementById('period_start');
    const periodEnd   = document.getElementById('period_end');
    const nameFilter  = document.getElementById('name_filter');
    const bookFilter  = document.getElementById('book_filter');
    const categoryFilter = document.getElementById('category_filter');
    const statusFilter= document.getElementById('status_filter');

    function filterRows(){
        const start = periodStart.value;
        const end   = periodEnd.value;
        const name  = nameFilter.value.toLowerCase();
        const book  = bookFilter.value.toLowerCase();
        const cat   = categoryFilter.value.toLowerCase();
        const status= statusFilter.value;

        rows.forEach(row => {
            const dateStr = row.getAttribute('data-date');
            const memberName = row.querySelector('.member-name')?.textContent.toLowerCase() || '';
            const rowStatus = row.querySelector('.status-badge')?.getAttribute('data-status') || '';
            const booksInRow = Array.from(row.querySelectorAll('.book-item')).map(el => el.textContent.toLowerCase());
            const catsInRow = Array.from(row.querySelectorAll('.category-item')).map(el => el.textContent.toLowerCase());

            let matchDate = true;
            if(start && dateStr < start) matchDate = false;
            if(end && dateStr > end) matchDate = false;

            const matchName = !name || memberName.includes(name);
            const matchStatus = !status || rowStatus === status;
            const matchBook = !book || booksInRow.some(b => b.includes(book));
            const matchCat = !cat || catsInRow.some(c => c.includes(cat));

            row.style.display = (matchDate && matchName && matchStatus && matchBook && matchCat) ? '' : 'none';
        });
    }

    function showDetailModal(id){
        const data = detailData[id];
        if(!data) return;
        document.getElementById('detailMemberName').textContent = data.memberName;
        document.getElementById('detailBorrowedAt').textContent = data.borrowedAt;
        document.getElementById('detailDueDate').textContent = data.dueDate;
        
        let statusLabel = data.status;
        if(data.status === 'menunggu_acc') statusLabel = 'Menunggu';
        if(data.status === 'dipinjam') statusLabel = 'Dipinjam';
        if(data.status === 'kembali') statusLabel = 'Kembali';
        
        document.getElementById('detailStatus').textContent = statusLabel;
        document.getElementById('detailFine').textContent = data.fine;
        
        const bookList = document.getElementById('detailBookList');
        bookList.innerHTML = '';
        data.books.forEach(b => {
            const li = document.createElement('li');
            li.innerHTML = `<strong>${b.title}</strong> <small class="text-muted">(${b.category})</small> - Qty: ${b.qty}`;
            bookList.appendChild(li);
        });

        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }

    function resetFilters() {
        if(periodStart) periodStart.value = '';
        if(periodEnd) periodEnd.value = '';
        if(nameFilter) nameFilter.value = '';
        if(bookFilter) bookFilter.value = '';
        if(categoryFilter) categoryFilter.value = '';
        if(statusFilter) statusFilter.value = '';
        filterRows();
    }

    document.addEventListener('DOMContentLoaded', () => {
        periodStart.addEventListener('change', filterRows);
        periodEnd.addEventListener('change', filterRows);
        nameFilter.addEventListener('change', filterRows);
        bookFilter.addEventListener('change', filterRows);
        categoryFilter.addEventListener('change', filterRows);
        statusFilter.addEventListener('change', filterRows);
    });
</script>
@endsection
