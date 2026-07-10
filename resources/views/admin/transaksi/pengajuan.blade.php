@extends('layout.admin')
@section('title', 'Pengajuan Peminjaman')

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

    .btn-add-peminjaman {
        background: white;
        color: #10172e;
        border: none;
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
    }

    .btn-add-peminjaman:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.15);
        color: #10172e;
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

    .stat-card.pending {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(245, 158, 11, 0) 100%);
    }

    .stat-card.pending::before {
        background: #f59e0b;
    }

    .stat-card.approved {
        border-left-color: #10b981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0) 100%);
    }

    .stat-card.approved::before {
        background: #10b981;
    }

    .stat-card.rejected {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, rgba(239, 68, 68, 0) 100%);
    }

    .stat-card.rejected::before {
        background: #ef4444;
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
        color: #0f172a;
    }

    .stat-card.pending .stat-value {
        color: #f59e0b;
    }

    .stat-card.approved .stat-value {
        color: #10b981;
    }

    .stat-card.rejected .stat-value {
        color: #ef4444;
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
        background: linear-gradient(135deg, #ff7a59 0%, #ffc95c 100%);
        color: white;
        padding: 0.25rem 0.7rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
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

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .status-borrowed {
        background: #dbeafe;
        color: #0c2d6b;
    }

    .action-buttons {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .btn-approve {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
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

    .modal-header {
        background: linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        border: none;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-title {
        font-weight: 800;
        color: white;
    }

    .form-label {
        font-weight: 700;
        color: #10172e;
        margin-bottom: 0.7rem;
    }

    .form-control,
    .form-select {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.8rem 1.1rem;
        transition: all 0.25s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff7a59;
        box-shadow: 0 0 0 4px rgba(255, 122, 89, 0.12);
        outline: none;
    }

    .btn-submit-modal {
        background: linear-gradient(135deg, #ff7a59 0%, #ffc95c 100%);
        color: white;
        border: none;
        padding: 0.9rem 2.2rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.25s ease;
        margin-top: 1.8rem;
    }

    .btn-submit-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(255, 122, 89, 0.3);
        color: white;
    }

    .btn-cancel-modal {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 0.9rem 2.2rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .btn-cancel-modal:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .info-box-modal {
        background: linear-gradient(135deg, rgba(255, 122, 89, 0.08) 0%, rgba(255, 193, 7, 0.04) 100%);
        border-left: 4px solid #ff7a59;
        padding: 1.1rem;
        border-radius: 10px;
        color: #10172e;
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

        .action-buttons {
            flex-direction: column;
        }

        .btn-approve,
        .btn-reject {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="admin-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="bi bi-file-earmark-text-fill"></i> Pengajuan Peminjaman</h1>
                <p>Kelola persetujuan dan penolakan pengajuan peminjaman buku dari para murid dengan cepat.</p>
            </div>
            <a href="{{ route('admin.peminjaman.create') }}" class="btn-add-peminjaman">
                <i class="bi bi-plus-circle"></i> Tambah Peminjaman
            </a>
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
            <div class="stat-card pending">
                <div class="stat-label">Menunggu Persetujuan</div>
                <div class="stat-value">{{ $peminjaman->where('status', 'menunggu_acc')->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card approved">
                <div class="stat-label">Sudah Disetujui</div>
                <div class="stat-value">{{ $peminjaman->where('status', 'dipinjam')->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card rejected">
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $peminjaman->where('status', 'ditolak')->count() }}</div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    @php
        $uniqueNames = $peminjaman->unique('user.id')->pluck('user.nama');
        $uniqueBooks = $peminjaman->pluck('details')->flatten()->unique('book.id')->pluck('book.title');
    @endphp
    <div class="table-wrapper">
        <h4 class="table-title">
            <i class="bi bi-list-check"></i> Daftar Pengajuan
        </h4>

        {{-- FILTER & SEARCH BAR --}}
        <div class="row g-2 mb-4">
            <div class="col-md-8">
                <input type="text" class="form-control" id="peminjam_search" placeholder="🔍 Cari nama murid, status, atau buku...">
            </div>
            <div class="col-md-4">
            <div class="col-md-3">
                <select class="form-select" id="name_filter">
                    <option value="">Semua Nama</option>
                    @foreach($uniqueNames as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="book_filter">
                    <option value="">Semua Buku</option>
                    @foreach($uniqueBooks as $title)
                        <option value="{{ $title }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="status_filter">
                    <option value="">Semua Status Pengajuan</option>
                    <option value="menunggu_acc">Menunggu Persetujuan</option>
                    <option value="dipinjam">Sudah Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            </div>
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
                            <th width="100">Status</th>
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
                                    <span class="book-qty">{{ $item->details->sum('qty') }} Buku</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($item->status == 'menunggu_acc')
                                        <span class="status-badge status-pending">Menunggu</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="status-badge status-borrowed">Disetujui</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="status-badge status-rejected">Ditolak</span>
                                    @else
                                        <span class="status-badge">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($item->status == 'menunggu_acc')
                                        <button type="button" class="btn-approve" onclick="showApproveModal({{ $item->id }}, '{{ $item->user->nama }}')">
                                            <i class="bi bi-check-circle"></i> Setujui
                                        </button>
                                        <form action="{{ route('admin.peminjaman.reject', $item->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn-reject" onclick="return confirm('Yakin ingin menolak pengajuan ini?')">
                                                <i class="bi bi-x-circle"></i> Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    <button type="button" class="btn-detail" onclick="showDetailModal({{ $item->id }})" style="margin-left:0.5rem; background:#6b7280; color:white; border:none; padding:0.6rem 1.1rem; border-radius:10px; font-weight:700; font-size:0.8rem;">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </button>
                                    </div>
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
                <p class="text-muted mt-3">Belum ada pengajuan peminjaman buku</p>
            </div>
        @endif
    </div>
</div>

{{-- MODAL APPROVE --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none;">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">✓ Setujui Pengajuan Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body" style="padding: 2.2rem;">
                    <p style="color: #334155; margin-bottom: 1.8rem; font-size: 1rem;">
                        Murid <strong id="studentNameDisplay"></strong> akan diminta datang ke perpustakaan untuk mengambil buku secara langsung.
                    </p>

                    <div class="mb-4">
                        <label for="borrowDuration" class="form-label">Lama Peminjaman (Hari)</label>
                        <input type="number" class="form-control" id="borrowDuration" name="borrow_duration" min="1" max="30" value="7" required>
                        <small class="text-muted">Berapa hari buku boleh dipinjam?</small>
                    </div>

                    <div class="mb-4">
                        <label for="returnDateDisplay" class="form-label">Tanggal Pengembalian Otomatis</label>
                        <input type="text" class="form-control" id="returnDateDisplay" readonly style="background: linear-gradient(135deg, #fff8f2 0%, #fef3e8 100%); cursor: not-allowed; color: #ff7a59; font-weight: 600;">
                    </div>

                    <div class="info-box-modal">
                        <i class="bi bi-info-circle" style="color: #ff7a59; margin-right: 0.5rem;"></i> Murid akan menerima notifikasi persetujuan dan diminta datang ke perpustakaan.
                    </div>
                </div>
                <div class="modal-footer" style="padding: 1.8rem; gap: 0.9rem; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-submit-modal"><i class="bi bi-check-circle"></i> Konfirmasi Persetujuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none;">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">📄 Detail Pengajuan Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2.2rem;">
                <p><strong>Nama Murid:</strong> <span id="detailStudentName"></span></p>
                <p><strong>Email:</strong> <span id="detailStudentEmail"></span></p>
                <p><strong>Tanggal Pengajuan:</strong> <span id="detailCreatedAt"></span></p>
                <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                <hr>
                <h6>Buku yang Diajukan:</h6>
                <ul id="detailBookList"></ul>
            </div>
            <div class="modal-footer" style="padding: 1.8rem; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    // Store detail data in a JS object for quick lookup
    const detailData = {
        @foreach($peminjaman as $item)
        {{ $item->id }}: {
            studentName: "{{ addslashes($item->user->nama ?? '') }}",
            email: "{{ addslashes($item->user->email ?? '') }}",
            createdAt: "{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}",
            status: "{{ $item->status }}",
            books: [
                @foreach($item->details as $detail)
                {
                    title: "{{ addslashes($detail->book->title ?? '') }}",
                    qty: {{ $detail->qty }}
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }@if(!$loop->last),@endif
        @endforeach
    };

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('peminjam_search');
        const statusFilter = document.getElementById('status_filter');
        const rows = document.querySelectorAll('table tbody tr');

        function filterRows() {
            const query = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const statusCell = row.querySelector('td:nth-child(6)'); // Status column
                let statusText = '';
                
                if (statusCell) {
                    if (statusCell.textContent.includes('Menunggu')) statusText = 'menunggu_acc';
                    else if (statusCell.textContent.includes('Disetujui')) statusText = 'dipinjam';
                    else if (statusCell.textContent.includes('Ditolak')) statusText = 'ditolak';
                }
                
                const matchesSearch = text.includes(query);
                const matchesStatus = !selectedStatus || statusText === selectedStatus;
                
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterRows);
        statusFilter.addEventListener('change', filterRows);

        document.getElementById('borrowDuration').addEventListener('change', updateReturnDate);
        document.getElementById('approveForm').addEventListener('submit', handleApproveSubmit);
    });

    function updateReturnDate() {
        const duration = parseInt(document.getElementById('borrowDuration').value) || 0;
        const today = new Date();
        const returnDate = new Date(today.getTime() + duration * 24 * 60 * 60 * 1000);
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('returnDateDisplay').value = returnDate.toLocaleDateString('id-ID', options);
    }

    function clearModalBackdrop() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    function showApproveModal(peminjaman_id, student_name) {
        document.getElementById('studentNameDisplay').textContent = student_name;
        document.getElementById('approveForm').action = '/admin/peminjaman/' + peminjaman_id + '/approve';
        updateReturnDate();
        clearModalBackdrop();
        const modal = new bootstrap.Modal(document.getElementById('approveModal'));
        modal.show();
    }

    function handleApproveSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        const formData = new FormData(form);
        const actionUrl = form.action;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses permintaan');
                });
            }
            return response.json();
        })
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();
            showNotification('success', 'Peminjaman berhasil disetujui!');
            setTimeout(() => {
                location.reload();
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', error.message || 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    }

    function showDetailModal(peminjamanId) {
        const data = detailData[peminjamanId];
        if (!data) return;
        document.getElementById('detailStudentName').textContent = data.studentName;
        document.getElementById('detailStudentEmail').textContent = data.email;
        document.getElementById('detailCreatedAt').textContent = data.createdAt;
        document.getElementById('detailStatus').textContent = (function(status) {
            if (status === 'menunggu_acc') return 'Menunggu';
            if (status === 'dipinjam') return 'Disetujui';
            if (status === 'ditolak') return 'Ditolak';
            return status;
        })(data.status);
        const list = document.getElementById('detailBookList');
        list.innerHTML = '';
        data.books.forEach(book => {
            const li = document.createElement('li');
            li.textContent = `${book.title} (${book.qty} buku)`;
            list.appendChild(li);
        });
        clearModalBackdrop();
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }
    
    function showNotification(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);';
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
</script>
@endsection
