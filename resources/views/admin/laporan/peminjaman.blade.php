@extends('layout.admin')
@section('title', 'Laporan Peminjaman')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .admin-header {
        background: linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        color: white;
        padding: 3rem 2.2rem;
        border-radius: 24px;
        margin-bottom: 2rem;
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
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .admin-header p {
        font-size: 1.05rem;
        opacity: 0.92;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .filter-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.5rem;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        margin-bottom: 2rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
    }

    .stat-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.4rem;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
        border-left: 5px solid;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        transition: all 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        flex-shrink: 0;
    }

    .stat-info h3 {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        color: #10172e;
    }

    .stat-info p {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .c-primary { border-color: #0ea5e9; } .c-primary .stat-icon { background: #0ea5e9; }
    .c-teal { border-color: #14b8a6; } .c-teal .stat-icon { background: #14b8a6; }
    .c-success { border-color: #10b981; } .c-success .stat-icon { background: #10b981; }
    .c-warning { border-color: #f59e0b; } .c-warning .stat-icon { background: #f59e0b; }
    .c-danger { border-color: #ef4444; } .c-danger .stat-icon { background: #ef4444; }

    .btn-action {
        padding: 0.65rem 1.4rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-excel { background: #10b981; color: #fff; }
    .btn-excel:hover { background: #059669; color: #fff; transform: translateY(-2px); }

    .btn-pdf { background: #ef4444; color: #fff; }
    .btn-pdf:hover { background: #dc2626; color: #fff; transform: translateY(-2px); }

    .btn-print-custom { background: #64748b; color: #fff; }
    .btn-print-custom:hover { background: #475569; color: #fff; transform: translateY(-2px); }

    .table-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 2rem;
        margin-top: 1.5rem;
    }

    .table-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #10172e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .table-title i {
        color: #ff7a59;
        font-size: 1.5rem;
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
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 1.2rem 1rem;
    }

    .datatable tbody td {
        vertical-align: middle;
        border-color: #e2e8f0;
        padding: 1.05rem;
        color: #334155;
    }

    .student-name {
        font-weight: 700;
        color: #0f172a;
        display: block;
        margin-bottom: 0.25rem;
    }

    .student-email {
        font-size: 0.78rem;
        color: #64748b;
    }

    .book-item {
        background: linear-gradient(135deg, #fff8f2 0%, #fef3e8 100%);
        border-left: 3px solid #ff7a59;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        margin-bottom: 0.35rem;
        font-size: 0.86rem;
    }

    .status-badge {
        padding: 0.55rem 1rem;
        border-radius: 999px;
        font-size: 0.73rem;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-borrowed { background: #dbeafe; color: #1e40af; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .status-returning { background: #fed7aa; color: #9a3412; }
    .status-returned { background: #d1fae5; color: #065f46; }

    /* Clean Print Document Styles */
    .print-only-header {
        display: none;
    }

    @media print {
        .admin-sidebar,
        .admin-topbar,
        .no-print,
        .admin-header,
        .filter-card,
        .stat-row,
        .pagination,
        footer {
            display: none !important;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .admin-shell, .admin-main, .admin-content, .container-fluid {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .table-wrapper {
            box-shadow: none !important;
            padding: 0 !important;
            margin-top: 0 !important;
            border: none !important;
        }

        .print-only-header {
            display: block !important;
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px double #000000;
        }

        .print-only-header h2 {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
        }

        .print-only-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 4px 0;
        }

        .print-only-header p {
            font-size: 0.85rem;
            margin: 0;
            color: #333333;
        }

        .datatable {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .datatable thead th {
            background: #f1f5f9 !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
            padding: 8px !important;
            font-size: 0.75rem !important;
        }

        .datatable tbody td {
            border: 1px solid #000000 !important;
            padding: 8px !important;
            font-size: 0.75rem !important;
            color: #000000 !important;
        }

        .status-badge {
            border: 1px solid #000000;
            background: transparent !important;
            color: #000000 !important;
            padding: 2px 6px;
        }

        .book-item {
            background: transparent !important;
            border-left: none !important;
            padding: 0 !important;
        }
    }
</style>

<div class="container-fluid py-4" id="laporanContainer">
    {{-- HEADER SECTION --}}
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap gap-3 no-print">
        <div>
            <h1><i class="bi bi-file-earmark-arrow-up-fill me-2"></i> Laporan Peminjaman Buku</h1>
            <p>Rekapitulasi resmi pencatatan transaksi peminjaman buku perpustakaan — Periode <strong>{{ $namaBulan }} {{ $tahun }}</strong></p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button onclick="exportExcel()" class="btn-action btn-excel">
                <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
            </button>
            <button onclick="exportPDF()" class="btn-action btn-pdf">
                <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
            </button>
            <button onclick="window.print()" class="btn-action btn-print-custom">
                <i class="bi bi-printer-fill"></i> Cetak Laporan
            </button>
        </div>
    </div>

    {{-- FILTER PANEL --}}
    <div class="filter-card no-print">
        <form method="GET" action="{{ route('admin.laporan.peminjaman') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label font-weight-bold text-secondary small">Bulan</label>
                <select name="bulan" class="form-select font-weight-bold">
                    <option value="all" {{ (string)$bulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach($namaBulanList as $num => $nama)
                        <option value="{{ $num }}" {{ (string)$bulan === (string)$num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label font-weight-bold text-secondary small">Tahun</label>
                <select name="tahun" class="form-select font-weight-bold">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label font-weight-bold text-secondary small">Status Peminjaman</label>
                <select name="status" class="form-select font-weight-bold">
                    <option value="">Semua Status</option>
                    <option value="menunggu_acc" {{ $status == 'menunggu_acc' ? 'selected' : '' }}>Menunggu ACC</option>
                    <option value="dipinjam" {{ $status == 'dipinjam' ? 'selected' : '' }}>Disetujui (Dipinjam)</option>
                    <option value="proses_kembali" {{ $status == 'proses_kembali' ? 'selected' : '' }}>Proses Pengembalian</option>
                    <option value="kembali" {{ $status == 'kembali' ? 'selected' : '' }}>Sudah Kembali</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label font-weight-bold text-secondary small">Jenis Anggota</label>
                <select name="role" class="form-select font-weight-bold">
                    <option value="">Semua Anggota</option>
                    <option value="0" {{ (string)$role === '0' ? 'selected' : '' }}>Siswa</option>
                    <option value="2" {{ (string)$role === '2' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label font-weight-bold text-secondary small">Pencarian</label>
                <input type="text" name="search" class="form-control" placeholder="Nama / Judul Buku..." value="{{ $search }}">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100 font-weight-bold" title="Filter"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-outline-secondary w-100" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
        </form>
    </div>

    {{-- STATISTIK WIDGETS TERSINKRONISASI --}}
    <div class="row g-3 mb-4 stat-row no-print">
        <div class="col-md-3 sm-6">
            <div class="stat-card c-primary">
                <div class="stat-icon"><i class="bi bi-journals"></i></div>
                <div class="stat-info">
                    <p>Total Peminjaman</p>
                    <h3>{{ number_format($totalPinjam) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 sm-6">
            <div class="stat-card c-teal">
                <div class="stat-icon"><i class="bi bi-book-half"></i></div>
                <div class="stat-info">
                    <p>Sedang Dipinjam</p>
                    <h3>{{ number_format($sedangDipinjam) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 sm-6">
            <div class="stat-card c-success">
                <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                <div class="stat-info">
                    <p>Sudah Kembali</p>
                    <h3>{{ number_format($sudahDikembalikan) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 sm-6">
            <div class="stat-card c-warning">
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-info">
                    <p>Total Denda</p>
                    <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- HEADER KOP UNTUK MODE CETAK / PRINT --}}
    <div class="print-only-header">
        <h2>PERPUSTAKAAN SMKN 1 TIRTAMULYA</h2>
        <h3>LAPORAN PEMINJAMAN BUKU</h3>
        <p>Periode: {{ $namaBulan }} {{ $tahun }} | Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h4 class="table-title">
                <i class="bi bi-file-earmark-spreadsheet"></i> Rekapitulasi Transaksi Peminjaman
            </h4>
            <div class="text-muted small">
                Menampilkan {{ $peminjaman->count() }} dari {{ $totalPinjam }} total data
            </div>
        </div>

        @if($peminjaman->count() > 0)
            <div class="table-responsive">
                <table class="table datatable mb-0" id="reportTableMain">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="120">Tgl Ajuan</th>
                            <th>Nama Anggota</th>
                            <th width="90">Peran</th>
                            <th>Buku Diajukan</th>
                            <th width="80" class="text-center">Qty</th>
                            <th width="120">Status</th>
                            <th width="120">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $item)
                            @php
                                $roleLabel = (string)($item->user->role ?? '') === '2' ? 'Guru' : 'Siswa';
                                $qtySum = $item->details->sum('qty');
                            @endphp
                            <tr>
                                <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="student-name">{{ $item->user->nama ?? '-' }}</span>
                                    <span class="student-email">{{ $item->user->email ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $roleLabel == 'Guru' ? 'bg-success' : 'bg-info' }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->details->count() > 0)
                                        @foreach($item->details as $detail)
                                            <div class="book-item">
                                                <strong>{{ $detail->book->title ?? '-' }}</strong>
                                                <span class="text-muted small">({{ $detail->book->category->name_category ?? 'Umum' }})</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada data buku</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $qtySum }}</td>
                                <td>
                                    @if($item->status == 'menunggu_acc')
                                        <span class="status-badge status-pending">Menunggu</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="status-badge status-borrowed">Disetujui</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="status-badge status-rejected">Ditolak</span>
                                    @elseif($item->status == 'proses_kembali')
                                        <span class="status-badge status-returning">Proses Kembali</span>
                                    @elseif($item->status == 'kembali')
                                        <span class="status-badge status-returned">Kembali</span>
                                    @else
                                        <span class="status-badge">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $fine = method_exists($item, 'calculateFine') ? $item->calculateFine() : ($item->denda ?? 0);
                                    @endphp
                                    @if($fine > 0)
                                        <span class="fw-bold text-danger">Rp {{ number_format($fine, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION (No Print) --}}
            <div class="mt-4 d-flex justify-content-center no-print">
                {{ $peminjaman->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                <p class="text-muted mt-3 font-weight-bold">Tidak ada data transaksi peminjaman untuk periode ini.</p>
            </div>
        @endif
    </div>
</div>

{{-- SCRIPT EKSPOR MURNI DATA (EXCEL & PDF) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
    // Data murni hasil filter dari backend untuk ekspor bersih
    const rawExportData = [
        @foreach($allPeminjaman as $idx => $item)
        @php
            $roleLabel = (string)($item->user->role ?? '') === '2' ? 'Guru' : 'Siswa';
            $bookTitles = [];
            foreach($item->details as $d) {
                $bookTitles[] = ($d->book->title ?? '-') . ' (' . ($d->qty ?? 1) . 'x)';
            }
            $fine = method_exists($item, 'calculateFine') ? $item->calculateFine() : ($item->denda ?? 0);
            
            $statusStr = 'Menunggu ACC';
            if($item->status == 'dipinjam') $statusStr = 'Disetujui (Dipinjam)';
            if($item->status == 'ditolak') $statusStr = 'Ditolak';
            if($item->status == 'proses_kembali') $statusStr = 'Proses Kembali';
            if($item->status == 'kembali') $statusStr = 'Sudah Kembali';
        @endphp
        {
            "No": {{ $idx + 1 }},
            "Tanggal Ajuan": "{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}",
            "Nama Anggota": "{{ addslashes($item->user->nama ?? '-') }}",
            "Email": "{{ addslashes($item->user->email ?? '-') }}",
            "Peran": "{{ $roleLabel }}",
            "Buku Diajukan": "{{ addslashes(implode(', ', $bookTitles)) }}",
            "Total Qty": {{ $item->details->sum('qty') }},
            "Status": "{{ $statusStr }}",
            "Total Denda (Rp)": {{ (int) $fine }}
        },
        @endforeach
    ];

    const exportNamaBulan = @json($namaBulan);
    const exportTahun = @json($tahun);

    // Fungsi Ekspor Excel Murni Data
    function exportExcel() {
        if (rawExportData.length === 0) {
            alert('Tidak ada data transaksi untuk diekspor.');
            return;
        }

        const worksheet = XLSX.utils.json_to_sheet(rawExportData);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Data Peminjaman");

        // Format kolom lebar otomatis
        const max_width = rawExportData.reduce((w, r) => Math.max(w, (r["Buku Diajukan"] || '').length), 20);
        worksheet["!cols"] = [
            { wch: 6 },  // No
            { wch: 15 }, // Tanggal
            { wch: 25 }, // Nama
            { wch: 25 }, // Email
            { wch: 10 }, // Peran
            { wch: Math.min(max_width, 60) }, // Buku
            { wch: 10 }, // Qty
            { wch: 20 }, // Status
            { wch: 18 }  // Denda
        ];

        XLSX.writeFile(workbook, `Laporan_Peminjaman_${exportNamaBulan}_${exportTahun}.xlsx`);
    }

    // Fungsi Ekspor PDF Murni Tabel Data (jsPDF AutoTable)
    function exportPDF() {
        if (rawExportData.length === 0) {
            alert('Tidak ada data transaksi untuk diekspor.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'pt', 'a4');

        doc.setFontSize(14);
        doc.text(`LAPORAN DATA PEMINJAMAN BUKU PERPUSTAKAAN`, 40, 40);
        doc.setFontSize(11);
        doc.text(`Periode: ${exportNamaBulan} ${exportTahun} | SMKN 1 Tirtamulya`, 40, 58);

        const tableColumn = ["No", "Tgl Ajuan", "Nama Anggota", "Peran", "Buku Diajukan", "Qty", "Status", "Denda (Rp)"];
        const tableRows = rawExportData.map(item => [
            item["No"],
            item["Tanggal Ajuan"],
            item["Nama Anggota"],
            item["Peran"],
            item["Buku Diajukan"],
            item["Total Qty"],
            item["Status"],
            item["Total Denda (Rp)"] > 0 ? "Rp " + item["Total Denda (Rp)"].toLocaleString('id-ID') : "-"
        ]);

        doc.autoTable({
            head: [tableColumn],
            body: tableRows,
            startY: 75,
            theme: 'grid',
            styles: { fontSize: 8, cellPadding: 5 },
            headStyles: { fillColor: [29, 79, 120], textColor: [255, 255, 255], fontStyle: 'bold' }
        });

        doc.save(`Laporan_Peminjaman_${exportNamaBulan}_${exportTahun}.pdf`);
    }
</script>
@endsection

