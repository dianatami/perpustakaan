@extends('layout.anggota')
@section('title', 'Peminjaman Buku')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))
{{-- Tom Select CDN --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
<style>
    * {
        transition: all 0.25s ease;
    }

    .card {
        box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
        border: none;
        transition: all 0.25s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.1);
    }

    .badge {
        font-size: 0.83rem;
        padding: 0.55rem 0.9rem;
    }

    .rounded-4 {
        border-radius: 1.2rem;
    }

    .rounded-3 {
        border-radius: 0.9rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f4f8ff;
    }

    .alert-success {
        animation: slideInDown 0.45s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Tom Select overrides ── */
    .ts-wrapper { border-radius: 12px !important; }
    .ts-wrapper .ts-control {
        border: 1px solid var(--portal-border) !important;
        border-radius: 12px !important;
        padding: 8px 14px !important;
        min-height: 44px;
        font-size: 0.95rem;
    }
    .ts-wrapper.focus .ts-control {
        border-color: var(--portal-primary) !important;
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--portal-primary) 18%, transparent) !important;
    }
    .ts-dropdown {
        border-radius: 12px !important;
        border: 1px solid var(--portal-border) !important;
        box-shadow: 0 12px 32px rgba(15,23,42,0.12) !important;
        margin-top: 4px !important;
    }
    .ts-dropdown .option {
        padding: 10px 14px !important;
        border-bottom: 1px solid #f1f5f9;
    }
    .ts-dropdown .option:last-child { border-bottom: none; }
    .ts-dropdown .option .book-opt-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.92rem;
    }
    .ts-dropdown .option .book-opt-code {
        color: var(--portal-primary);
        font-weight: 600;
        font-size: 0.8rem;
    }
    .ts-dropdown .option .book-opt-author {
        font-size: 0.82rem;
        color: #64748b;
    }
    .ts-dropdown .option .book-opt-stock {
        font-size: 0.78rem;
        font-weight: 700;
    }
    .ts-dropdown .option .book-opt-stock.in-stock { color: #10b981; }
    .ts-dropdown .option .book-opt-stock.out-of-stock { color: #ef4444; }
    .ts-dropdown .option[aria-disabled="true"],
    .ts-dropdown .option.disabled {
        opacity: 0.55;
        cursor: not-allowed;
        background: #fafafa !important;
    }
    .ts-dropdown .active { background: color-mix(in srgb, var(--portal-primary) 8%, transparent) !important; }
    .ts-wrapper .ts-control .item {
        font-weight: 600;
        color: #0f172a;
    }
</style>

<div class="container-fluid py-4">
    <!-- Success/Error Notification -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" 
         style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); border: none; color: white; border-radius: 10px; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
        <i class="bi bi-check-circle"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" 
         style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); border: none; color: white; border-radius: 10px; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Statistik & Peminjaman -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden;">
                <div class="card-header border-0 pt-4 pb-3"
                    style="background: linear-gradient(130deg, #1d4f78, #ff7a59);">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 44px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                        <h5 class="card-title fw-bold mb-0 text-white">
                            Panel Peminjaman Buku SMKN 1 Tirtamulya
                        </h5>
                    </div>
                </div>

                <div class="card-body pb-4">
                    <!-- FORM PINJAM BUKU -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 38px; width: auto; object-fit: contain;">
                                <h5 class="fw-bold mb-0">Form Pinjam Buku Baru</h5>
                            </div>
                            <span class="badge bg-primary">
                                {{ $availableBooks->count() ?? 0 }} Buku Tersedia
                            </span>
                        </div>

                        @if(isset($availableBooks) && $availableBooks->count() > 0)
                            <form action="{{ route($portalPrefix . '.pinjam.store') }}" method="POST">
                                @csrf
                                <div id="book-wrapper">
                                    <div class="row g-2 mb-2 book-item">
                                        <div class="col-md-7">
                                            <select name="books[0][book_id]" class="form-select book-select" required>
                                                <option value="">-- Pilih Buku --</option>
                                                @foreach($availableBooks as $b)
                                                    <option value="{{ $b->id }}"
                                                        data-book-code="{{ $b->book_code ?? '' }}"
                                                        data-author="{{ $b->author ?? '' }}"
                                                        data-stock="{{ $b->stock }}"
                                                        {{ $b->stock <= 0 ? 'disabled' : '' }}>
                                                        {{ $b->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="books[0][qty]" class="form-control" min="1" value="1" placeholder="Qty" required>
                                        </div>
                                        <div class="col-md-2 d-grid">
                                            <button type="button" class="btn btn-danger remove-book">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <button type="button" class="btn btn-outline-primary" id="add-book">
                                        <i class="bi bi-plus-circle"></i> Tambah Buku
                                    </button>
                                    <button type="submit" class="btn btn-primary fw-bold">
                                        <i class="bi bi-send"></i> Ajukan Peminjaman
                                    </button>
                                </div>
                            </form>
                            <div class="text-muted small mt-2">
                                Pengajuan peminjaman akan diproses oleh admin perpustakaan.
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Tidak ada buku tersedia untuk dipinjam saat ini.
                            </div>
                        @endif
                    </div>

                    <!-- Statistik Peminjaman -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-4 rounded-4"
                                style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 5px solid #667eea;">
                                <div class="text-muted small fw-bold mb-2">Total Pengajuan Peminjaman</div>
                                <div class="fw-bold" style="color: #667eea; font-size: 2rem;">
                                    {{ $bookrents->count() ?? 0 }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-4 rounded-4"
                                style="background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 193, 7, 0.1) 100%); border-left: 5px solid #ff9800;">
                                <div class="text-muted small fw-bold mb-2">Sedang Dipinjam</div>
                                <div class="fw-bold" style="color: #f79a0e; font-size: 2rem;">
                                    {{ $bookrents->where('status', 'dipinjam')->count() ?? 0 }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-4 rounded-4"
                                style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(56, 142, 60, 0.1) 100%); border-left: 5px solid #4caf50;">
                                <div class="text-muted small fw-bold mb-2">Selesai Dipinjam</div>
                                <div class="fw-bold" style="color: #4caf50; font-size: 2rem;">
                                    {{ $bookrents->where('status', 'kembali')->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Riwayat Peminjaman -->
                    <h5 class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Riwayat Transaksi Anda</h5>
                    <div class="table-responsive">
                        @if($bookrents && $bookrents->count() > 0)
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Batas Kembali</th>
                                        <th>Sisa Waktu (Countdown)</th>
                                        <th>Denda</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookrents->sortByDesc('created_at') as $rent)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach($rent->details as $detail)
                                                        <div class="d-flex justify-content-between align-items-start" style="min-width:0;">
                                                            <div style="min-width:0;">
                                                                <div class="fw-semibold text-truncate" style="max-width:320px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $detail->book->title }}">
                                                                    {{ $detail->book->title }}
                                                                </div>
                                                                @if(!empty($detail->book->book_code))
                                                                    <small class="text-muted">Kode: {{ $detail->book->book_code }}</small>
                                                                @endif
                                                            </div>
                                                            <div class="text-end ms-2">
                                                                <span class="badge bg-secondary">Qty: {{ $detail->qty }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                {{ $rent->borrow_date ? \Carbon\Carbon::parse($rent->borrow_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                {{ $rent->return_date ? \Carbon\Carbon::parse($rent->return_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                @if(($rent->jenis_peminjam === 'murid' || $rent->user->role == 0) && $rent->status === 'dipinjam' && $rent->tgl_kembali_maksimal)
                                                    <div class="d-flex flex-column align-items-start">
                                                        <span class="badge bg-warning text-dark countdown-timer" 
                                                              data-expires-at="{{ $rent->tgl_kembali_maksimal->toIso8601String() }}">
                                                            <i class="bi bi-hourglass-split"></i> Menghitung...
                                                        </span>
                                                        <small class="text-muted mt-1" style="font-size:0.75rem;">
                                                            Maksimal: {{ \Carbon\Carbon::parse($rent->tgl_kembali_maksimal)->format('d M Y H:i:s') }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($rent->denda) && (int)$rent->denda > 0)
                                                    <span class="fw-bold text-danger">Rp {{ number_format($rent->denda, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rent->status == 'menunggu_acc')
                                                    <span class="badge bg-warning text-dark">Menunggu ACC</span>
                                                @elseif($rent->status == 'dipinjam')
                                                    <span class="badge bg-primary">Dipinjam</span>
                                                @elseif($rent->status == 'kembali')
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @elseif($rent->status == 'proses_kembali')
                                                    <span class="badge bg-warning text-dark">Proses Pengembalian</span>
                                                @elseif($rent->status == 'ditolak')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $rent->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rent->status == 'dipinjam')
                                                    <form action="{{ route($portalPrefix . '.pengembalian.store', $rent->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-arrow-return-left"></i> Kembalikan
                                                        </button>
                                                    </form>
                                                @elseif($rent->status == 'proses_kembali')
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-clock"></i> Menunggu Konfirmasi</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info mb-0">
                                Belum ada riwayat peminjaman buku.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close alert notifications
        const alertSuccess = document.querySelector('.alert-success');
        const alertDanger = document.querySelector('.alert-danger');
        
        [alertSuccess, alertDanger].forEach(alertEl => {
            if (alertEl) {
                setTimeout(function() {
                    const alertInstance = new bootstrap.Alert(alertEl);
                    alertInstance.close();
                }, 5000);
            }
        });

        // Real-time Countdown Timer
        const timers = Array.from(document.querySelectorAll('.countdown-timer'));

        if (timers.length > 0) {
            const formatCountdown = (totalSeconds) => {
                const safeSeconds = Math.max(0, totalSeconds);
                const hours = Math.floor(safeSeconds / 3600);
                const minutes = Math.floor((safeSeconds % 3600) / 60);
                const seconds = safeSeconds % 60;

                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            };

            const updateTimers = () => {
                const now = Date.now();

                timers.forEach((timer) => {
                    const expiresAt = timer.getAttribute('data-expires-at');

                    if (!expiresAt) {
                        timer.innerHTML = '-';
                        return;
                    }

                    const expiresMs = new Date(expiresAt).getTime();
                    const remaining = Math.max(0, Math.floor((expiresMs - now) / 1000));

                    if (remaining <= 0) {
                        timer.innerHTML = '<i class="bi bi-exclamation-octagon-fill"></i> Terlambat';
                        timer.classList.remove('bg-warning', 'text-dark');
                        timer.classList.add('bg-danger', 'text-white');
                        return;
                    }

                    timer.innerHTML = `<i class="bi bi-hourglass-split"></i> ${formatCountdown(remaining)}`;
                });
            };

            updateTimers();
            setInterval(updateTimers, 1000);
        }

        // ── Tom Select helper ──
        function initTomSelect(selectEl) {
            if (!selectEl || selectEl.tomselect) return; // already initialized
            new TomSelect(selectEl, {
                placeholder: '🔍 Cari judul, kode, atau penulis...',
                allowEmptyOption: true,
                searchField: [], // we use custom scoring
                score: function(search) {
                    var query = search.toLowerCase();
                    return function(item) {
                        if (!query) return 1;
                        var title = (item.text || '').toLowerCase();
                        var code  = (item.bookCode || '').toLowerCase();
                        var author= (item.author || '').toLowerCase();
                        if (title.indexOf(query) !== -1) return 1 + (title.indexOf(query) === 0 ? 0.5 : 0);
                        if (code.indexOf(query) !== -1) return 1;
                        if (author.indexOf(query) !== -1) return 0.8;
                        return 0;
                    };
                },
                onInitialize: function() {
                    // Inject data-attributes into internal item data
                    var self = this;
                    Object.keys(self.options).forEach(function(key) {
                        var optEl = selectEl.querySelector('option[value="' + key + '"]');
                        if (optEl) {
                            self.options[key].bookCode = optEl.getAttribute('data-book-code') || '';
                            self.options[key].author   = optEl.getAttribute('data-author') || '';
                            self.options[key].stock    = optEl.getAttribute('data-stock') || '0';
                            self.options[key].disabled = optEl.disabled;
                        }
                    });
                },
                render: {
                    option: function(data, escape) {
                        var stock = parseInt(data.stock || 0);
                        var stockClass = stock > 0 ? 'in-stock' : 'out-of-stock';
                        var stockLabel = stock > 0 ? 'Stok tersedia : ' + stock : '❌ Stok Habis';
                        var codeHtml = data.bookCode ? '<span class="book-opt-code">' + escape(data.bookCode) + ' — </span>' : '';
                        var authorHtml = data.author ? '<div class="book-opt-author">✏️ ' + escape(data.author) + '</div>' : '';
                        return '<div>'
                            + '<div class="book-opt-title">' + codeHtml + escape(data.text) + '</div>'
                            + authorHtml
                            + '<div class="book-opt-stock ' + stockClass + '">📦 ' + stockLabel + '</div>'
                            + '</div>';
                    },
                    item: function(data, escape) {
                        var codePrefix = data.bookCode ? escape(data.bookCode) + ' — ' : '';
                        return '<div>' + codePrefix + escape(data.text) + '</div>';
                    },
                    no_results: function() {
                        return '<div class="no-results" style="padding:10px 14px;color:#94a3b8;">Buku tidak ditemukan</div>';
                    }
                }
            });
        }

        // Initialize Tom Select on the initial book select (if exists)
        var initialSelect = document.querySelector('#book-wrapper .book-select');
        if (initialSelect) initTomSelect(initialSelect);

        // Add/Remove Book Items in Borrow Form
        let indexBook = 1;
        const addBtn = document.getElementById('add-book');
        const wrapper = document.getElementById('book-wrapper');

        if (addBtn && wrapper) {
            addBtn.addEventListener('click', function () {
                let html = `
                    <div class="row g-2 mb-2 book-item">
                        <div class="col-md-7">
                            <select name="books[${indexBook}][book_id]" class="form-select book-select" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach($availableBooks as $b)
                                    <option value="{{ $b->id }}"
                                        data-book-code="{{ $b->book_code ?? '' }}"
                                        data-author="{{ $b->author ?? '' }}"
                                        data-stock="{{ $b->stock }}"
                                        {{ $b->stock <= 0 ? 'disabled' : '' }}>
                                        {{ $b->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="books[${indexBook}][qty]" class="form-control" min="1" value="1" placeholder="Qty" required>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="button" class="btn btn-danger remove-book">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                wrapper.insertAdjacentHTML('beforeend', html);
                // Initialize Tom Select on the newly added select
                var newSelect = wrapper.querySelector('.book-item:last-child .book-select');
                initTomSelect(newSelect);
                indexBook++;
            });
        }

        document.addEventListener('click', function(e) {
            if(e.target.closest('.remove-book')) {
                const items = document.querySelectorAll('.book-item');
                if(items.length > 1) {
                    // Destroy Tom Select instance before removing DOM
                    var bookItem = e.target.closest('.book-item');
                    var sel = bookItem.querySelector('.book-select');
                    if (sel && sel.tomselect) sel.tomselect.destroy();
                    bookItem.remove();
                }
            }
        });
    });
</script>
@endsection
