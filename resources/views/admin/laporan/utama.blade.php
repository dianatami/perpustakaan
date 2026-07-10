@extends('layout.admin')
@section('title', 'Laporan Perpustakaan')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    * {font-family: 'Plus Jakarta Sans', sans-serif;}
    .report-header {background: linear-gradient(120deg, #10172e 0%, #1d4f78 42%, #0f8c80 100%);color:#fff;padding:3rem 2.5rem;border-radius:24px;margin-bottom:2rem;box-shadow:0 20px 40px rgba(15,23,42,.1);position:relative;overflow:hidden;}
    .report-header h1 {font-size:2.2rem;font-weight:800;margin-bottom:0.5rem;z-index:1;position:relative;}
    .report-header p {font-size:1.05rem;opacity:0.9;z-index:1;position:relative;margin:0;}
    .report-header::before, .report-header::after {content:'';position:absolute;border-radius:999px;opacity:0.15;}
    .report-header::before {width:300px;height:300px;background:#fff;top:-100px;right:-50px;}
    .report-header::after {width:200px;height:200px;background:#fff;bottom:-50px;left:-50px;}
    
    .stat-card {background:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 10px 25px rgba(15,23,42,.05);border-left:5px solid;display:flex;align-items:center;gap:1.2rem;transition:all 0.3s ease;}
    .stat-card:hover {transform:translateY(-5px);box-shadow:0 15px 35px rgba(15,23,42,.08);}
    .stat-icon {width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;}
    .stat-info h3 {font-size:1.6rem;font-weight:800;margin:0;color:#10172e;}
    .stat-info p {font-size:0.85rem;font-weight:600;color:#64748b;margin:0;text-transform:uppercase;letter-spacing:0.05em;}
    
    .c-primary {border-color:#0ea5e9;} .c-primary .stat-icon {background:#0ea5e9;}
    .c-success {border-color:#10b981;} .c-success .stat-icon {background:#10b981;}
    .c-warning {border-color:#f59e0b;} .c-warning .stat-icon {background:#f59e0b;}
    .c-danger {border-color:#ef4444;} .c-danger .stat-icon {background:#ef4444;}
    .c-purple {border-color:#8b5cf6;} .c-purple .stat-icon {background:#8b5cf6;}
    .c-teal {border-color:#14b8a6;} .c-teal .stat-icon {background:#14b8a6;}
    
    .section-card {background:#fff;border-radius:20px;padding:1.8rem;box-shadow:0 10px 30px rgba(15,23,42,.06);margin-bottom:2rem;}
    .section-title {font-size:1.2rem;font-weight:800;color:#1e293b;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.6rem;}
    .section-title i {color:#0f8c80;font-size:1.4rem;}
    
    .chart-container {position:relative;height:300px;width:100%;}
    
    .list-group-custom .list-group-item {border:none;border-bottom:1px solid #f1f5f9;padding:1rem 0;display:flex;justify-content:space-between;align-items:center;}
    .list-group-custom .list-group-item:last-child {border-bottom:none;}
    .rank-badge {width:28px;height:28px;border-radius:8px;background:#f1f5f9;color:#475569;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;margin-right:1rem;}
    .rank-1 {background:#fef08a;color:#854d0e;}
    .rank-2 {background:#e2e8f0;color:#475569;}
    .rank-3 {background:#fed7aa;color:#9a3412;}
    
    .datatable {font-size:0.85rem;}
    .datatable thead th {background:#f8fafc;color:#475569;text-transform:uppercase;font-size:0.75rem;letter-spacing:0.05em;padding:1rem;border-bottom:2px solid #e2e8f0;}
    .datatable tbody td {padding:1rem;vertical-align:middle;border-bottom:1px solid #f1f5f9;}
    
    .status-badge {padding:0.4rem 0.8rem;border-radius:999px;font-size:0.7rem;font-weight:700;text-transform:uppercase;}
    .badge-dipinjam {background:#dbeafe;color:#1e40af;}
    .badge-kembali {background:#d1fae5;color:#065f46;}
    .badge-terlambat {background:#fee2e2;color:#991b1b;}
    .badge-menunggu {background:#fef3c7;color:#92400e;}
    
    .action-bar {display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
    .btn-action {padding:0.6rem 1.2rem;border-radius:10px;font-weight:700;font-size:0.85rem;border:none;display:inline-flex;align-items:center;gap:0.5rem;transition:all 0.2s;}
    .btn-pdf {background:#ef4444;color:#fff;} .btn-pdf:hover {background:#dc2626;}
    .btn-excel {background:#10b981;color:#fff;} .btn-excel:hover {background:#059669;}
    .btn-print {background:#64748b;color:#fff;} .btn-print:hover {background:#475569;}
    
    @media print {
        .admin-sidebar, .admin-topbar, .action-bar, .no-print {display:none !important;}
        .admin-main {margin:0 !important;padding:0 !important;}
        body {background:#fff;}
        .report-header {box-shadow:none;color:#000;background:none;padding:1rem 0;margin-bottom:1rem;border-bottom:2px solid #000;border-radius:0;}
        .report-header h1 {color:#000;}
        .section-card {box-shadow:none;border:1px solid #ccc;break-inside:avoid;}
        .chart-container {height:250px;}
    }
</style>

<div class="container-fluid py-4" id="report-content">
    {{-- HEADER --}}
    <div class="report-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="bi bi-file-earmark-bar-graph"></i> Laporan Resmi Perpustakaan</h1>
                <p>Ringkasan Eksekutif & Data Statistik Operasional Perpustakaan</p>
            </div>
            <div class="text-end d-none d-md-block">
                <div style="font-size:0.9rem;opacity:0.8;">Tanggal Cetak</div>
                <div style="font-size:1.2rem;font-weight:700;">{{ date('d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- STATS WIDGETS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card c-primary">
                <div class="stat-icon"><i class="bi bi-book"></i></div>
                <div class="stat-info"><p>Total Buku</p><h3>{{ number_format($totalBuku) }}</h3></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card c-purple">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-info"><p>Total Anggota</p><h3>{{ number_format($totalAnggota) }}</h3></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card c-success">
                <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
                <div class="stat-info"><p>Total Peminjaman</p><h3>{{ number_format($totalPinjam) }}</h3></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card c-warning">
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-info"><p>Total Denda</p><h3>Rp {{ number_format($totalDenda,0,',','.') }}</h3></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="stat-card c-teal">
                <div class="stat-icon"><i class="bi bi-journal-arrow-up"></i></div>
                <div class="stat-info"><p>Sedang Dipinjam</p><h3>{{ number_format($sedangDipinjam) }}</h3></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="stat-card c-success">
                <div class="stat-icon"><i class="bi bi-journal-check"></i></div>
                <div class="stat-info"><p>Sudah Kembali</p><h3>{{ number_format($sudahDikembalikan) }}</h3></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="stat-card c-danger">
                <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-info"><p>Terlambat</p><h3>{{ number_format($terlambat) }}</h3></div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="section-card h-100">
                <h4 class="section-title"><i class="bi bi-bar-chart-fill"></i> Tren Peminjaman per Bulan</h4>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="section-card h-100">
                <h4 class="section-title"><i class="bi bi-pie-chart-fill"></i> Status Peminjaman</h4>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- SUMMARIES ROW --}}
    @php
        // Prepare Data for Summaries via PHP collections
        $bookCounts = [];
        $memberCounts = [];
        $catCounts = [];
        
        foreach($peminjaman as $p) {
            $memberType = ($p->user->role ?? '') == '2' ? 'Guru' : 'Siswa';
            $memberName = $p->user->nama ?? 'Unknown';
            if(!isset($memberCounts[$memberName])) {
                $memberCounts[$memberName] = ['count'=>0, 'type'=>$memberType];
            }
            $memberCounts[$memberName]['count']++;
            
            foreach($p->details as $d) {
                $title = $d->book->title ?? 'Unknown';
                $cat = $d->book->category->name ?? $d->book->kategori->nama ?? $d->book->kategori->name ?? 'Lainnya';
                $qty = $d->qty ?? 1;
                
                $bookCounts[$title] = ($bookCounts[$title] ?? 0) + $qty;
                $catCounts[$cat] = ($catCounts[$cat] ?? 0) + $qty;
            }
        }
        arsort($bookCounts);
        $topBooks = array_slice($bookCounts, 0, 10, true);
        
        uasort($memberCounts, function($a,$b){ return $b['count'] <=> $a['count']; });
        $topMembers = array_slice($memberCounts, 0, 10, true);
        
        arsort($catCounts);
        $topCats = array_slice($catCounts, 0, 5, true);
        
        // Prepare Monthly Chart Data
        $monthly = [];
        for($i=1; $i<=12; $i++) $monthly[date('M', mktime(0,0,0,$i,1))] = 0;
        foreach($peminjaman as $p) {
            if($p->created_at && $p->created_at->format('Y') == date('Y')) {
                $m = $p->created_at->format('M');
                $monthly[$m]++;
            }
        }
    @endphp

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="section-card h-100">
                <h4 class="section-title"><i class="bi bi-trophy-fill"></i> 10 Buku Terlaris</h4>
                <ul class="list-group list-group-custom">
                    @php $i = 1; @endphp
                    @forelse($topBooks as $title => $count)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="rank-badge rank-{{ $i<=3 ? $i : 'other' }}">{{ $i }}</span>
                                <span class="fw-semibold text-truncate" style="max-width:180px;" title="{{ $title }}">{{ $title }}</span>
                            </div>
                            <span class="badge bg-light text-dark">{{ $count }}x</span>
                        </li>
                        @php $i++; @endphp
                    @empty
                        <li class="list-group-item text-muted">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="section-card h-100">
                <h4 class="section-title"><i class="bi bi-star-fill"></i> 10 Anggota Teraktif</h4>
                <ul class="list-group list-group-custom">
                    @php $i = 1; @endphp
                    @forelse($topMembers as $name => $data)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="rank-badge rank-{{ $i<=3 ? $i : 'other' }}">{{ $i }}</span>
                                <div>
                                    <div class="fw-semibold text-truncate" style="max-width:150px;">{{ $name }}</div>
                                    <small class="text-muted">{{ $data['type'] }}</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark">{{ $data['count'] }}x</span>
                        </li>
                        @php $i++; @endphp
                    @empty
                        <li class="list-group-item text-muted">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="section-card h-100">
                <h4 class="section-title"><i class="bi bi-tags-fill"></i> Kategori Populer</h4>
                <div class="chart-container" style="height:220px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAILED REPORT TABLE --}}
    <div class="section-card" id="report-table-section">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h4 class="section-title mb-0"><i class="bi bi-table"></i> Data Lengkap Transaksi</h4>
            <div class="action-bar mb-0 no-print">
                <button class="btn-action btn-print" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
                <button class="btn-action btn-pdf" onclick="exportPDF()"><i class="bi bi-file-pdf"></i> Export PDF</button>
                <button class="btn-action btn-excel" onclick="exportExcel()"><i class="bi bi-file-excel"></i> Export Excel</button>
            </div>
        </div>

        {{-- FILTERS (No Print) --}}
        <div class="row g-2 mb-4 no-print">
            <div class="col-md-3">
                <select class="form-select" id="filter-period">
                    <option value="">Semua Periode</option>
                    <option value="{{ date('Y-m') }}">Bulan Ini</option>
                    <option value="{{ date('Y-m', strtotime('-1 month')) }}">Bulan Lalu</option>
                    <option value="{{ date('Y') }}">Tahun Ini</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filter-type">
                    <option value="">Semua Jenis Anggota</option>
                    <option value="Siswa">Siswa</option>
                    <option value="Guru">Guru</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filter-status">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam">Dipinjam</option>
                    <option value="Kembali">Kembali</option>
                    <option value="Terlambat">Terlambat</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Nama/Buku...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table datatable" id="reportTable">
                <thead>
                    <tr>
                        <th>Tgl Pinjam</th>
                        <th>Nama Anggota</th>
                        <th>Jenis</th>
                        <th>Buku (Qty)</th>
                        <th>Kategori</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $item)
                        @php
                            $memberType = ($item->user->role ?? '') == '2' ? 'Guru' : 'Siswa';
                            $fine = method_exists($item, 'calculateFine') ? $item->calculateFine() : 0;
                            
                            $statusLabel = 'Menunggu'; $badgeClass = 'badge-menunggu';
                            if($item->status == 'dipinjam') { $statusLabel = 'Dipinjam'; $badgeClass = 'badge-dipinjam'; }
                            if($item->status == 'kembali') { $statusLabel = 'Kembali'; $badgeClass = 'badge-kembali'; }
                            if($item->status == 'terlambat') { $statusLabel = 'Terlambat'; $badgeClass = 'badge-terlambat'; }
                            
                            $books = [];
                            $cats = [];
                            foreach($item->details as $d) {
                                $books[] = ($d->book->title ?? '') . ' ('.$d->qty.')';
                                $cats[] = $d->book->category->name ?? $d->book->kategori->nama ?? $d->book->kategori->name ?? 'Lainnya';
                            }
                            $dateSort = $item->created_at ? $item->created_at->format('Y-m') : '';
                        @endphp
                        <tr data-period="{{ $dateSort }}" data-type="{{ $memberType }}" data-status="{{ $statusLabel }}">
                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="searchable fw-semibold">{{ $item->user->nama ?? '-' }}</td>
                            <td>{{ $memberType }}</td>
                            <td class="searchable">
                                <ul class="mb-0 ps-3">
                                    @foreach($books as $b) <li>{{ $b }}</li> @endforeach
                                </ul>
                            </td>
                            <td>{{ implode(', ', array_unique($cats)) }}</td>
                            <td>{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') : '-' }}</td>
                            <td><span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td>{{ $fine > 0 ? 'Rp '.number_format($fine,0,',','.') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- External Libraries for Charts & Export --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- CHARTS INITIALIZATION ---
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        
        // 1. Monthly Borrowing Chart
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($monthly)) !!},
                datasets: [{
                    label: 'Total Peminjaman',
                    data: {!! json_encode(array_values($monthly)) !!},
                    borderColor: '#0f8c80',
                    backgroundColor: 'rgba(15, 140, 128, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // 2. Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Sedang Dipinjam', 'Sudah Kembali', 'Terlambat'],
                datasets: [{
                    data: [{{ $sedangDipinjam }}, {{ $sudahDikembalikan }}, {{ $terlambat }}],
                    backgroundColor: ['#0ea5e9', '#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
                }
            }
        });

        // 3. Category Popularity Chart (Top 5)
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($topCats)) !!},
                datasets: [{
                    label: 'Jumlah Buku',
                    data: {!! json_encode(array_values($topCats)) !!},
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // --- FILTERING LOGIC ---
        const rows = document.querySelectorAll('#reportTable tbody tr');
        const filterPeriod = document.getElementById('filter-period');
        const filterType = document.getElementById('filter-type');
        const filterStatus = document.getElementById('filter-status');
        const filterSearch = document.getElementById('filter-search');

        function applyFilters() {
            const pVal = filterPeriod.value;
            const tVal = filterType.value;
            const sVal = filterStatus.value;
            const qVal = filterSearch.value.toLowerCase();

            rows.forEach(row => {
                const rPeriod = row.getAttribute('data-period');
                const rType = row.getAttribute('data-type');
                const rStatus = row.getAttribute('data-status');
                const text = row.querySelector('.searchable').textContent.toLowerCase();
                const books = row.querySelectorAll('.searchable')[1].textContent.toLowerCase();

                let show = true;
                if(pVal && !rPeriod.startsWith(pVal)) show = false;
                if(tVal && rType !== tVal) show = false;
                if(sVal && rStatus !== sVal) show = false;
                if(qVal && !(text.includes(qVal) || books.includes(qVal))) show = false;

                row.style.display = show ? '' : 'none';
            });
        }

        filterPeriod.addEventListener('change', applyFilters);
        filterType.addEventListener('change', applyFilters);
        filterStatus.addEventListener('change', applyFilters);
        filterSearch.addEventListener('input', applyFilters);
    });

    // --- EXPORT FUNCTIONS ---
    function exportPDF() {
        const element = document.getElementById('report-content');
        const opt = {
            margin:       0.3,
            filename:     'Laporan_Perpustakaan.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        
        // Hide elements not needed in PDF
        document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');
        
        html2pdf().set(opt).from(element).save().then(() => {
            // Restore elements
            document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
        });
    }

    function exportExcel() {
        const table = document.getElementById('reportTable');
        
        // Clone table to modify for excel (remove hidden rows)
        const clone = table.cloneNode(true);
        Array.from(clone.querySelectorAll('tr')).forEach(tr => {
            if(tr.style.display === 'none') tr.remove();
        });
        
        const wb = XLSX.utils.table_to_book(clone, {sheet: "Laporan"});
        XLSX.writeFile(wb, 'Laporan_Perpustakaan.xlsx');
    }
</script>
@endsection
