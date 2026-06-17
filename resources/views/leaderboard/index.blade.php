@extends('layout.kepala')

@section('title', 'Leaderboard Peminjam Buku')

@section('style')
<style>
    :root {
        --primary: #178f78;
        --primary-dark: #0f6b5a;
        --secondary: #ff7a59;
        --accent: #ffc95c;
        --text-dark: #10172e;
        --text-light: #f7f2e8;
        --border: #e5e0d8;
    }

    body {
        background: linear-gradient(135deg, #0f6b5a 0%, #1d4f78 50%, #178f78 100%);
        min-height: 100vh;
    }

    .leaderboard-container {
        position: relative;
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .leaderboard-header {
        text-align: center;
        color: white;
        margin-bottom: 3rem;
        z-index: 10;
        position: relative;
    }

    .leaderboard-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .leaderboard-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .leaderboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        z-index: 10;
        position: relative;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 2px solid var(--border);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }

    .stat-card .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        display: block;
    }

    .stat-card .label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.5rem;
    }

    .leaderboard-table {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        z-index: 10;
        position: relative;
        border: 2px solid var(--border);
    }

    .leaderboard-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .leaderboard-table th {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }

    .leaderboard-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .leaderboard-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .leaderboard-table tbody tr:hover {
        background-color: rgba(23, 143, 120, 0.05);
    }

    .leaderboard-table tbody tr:last-child td {
        border-bottom: none;
    }

    .rank-cell {
        font-weight: 700;
        min-width: 60px;
    }

    .rank-1 .rank-cell {
        color: #FFD700;
        font-size: 1.2rem;
    }

    .rank-2 .rank-cell {
        color: #C0C0C0;
        font-size: 1.2rem;
    }

    .rank-3 .rank-cell {
        color: #CD7F32;
        font-size: 1.2rem;
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-weight: 700;
        color: white;
        font-size: 1.1rem;
    }

    .rank-1 .rank-badge {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
    }

    .rank-2 .rank-badge {
        background: linear-gradient(135deg, #C0C0C0, #808080);
        box-shadow: 0 4px 15px rgba(192, 192, 192, 0.4);
    }

    .rank-3 .rank-badge {
        background: linear-gradient(135deg, #CD7F32, #8B4513);
        box-shadow: 0 4px 15px rgba(205, 127, 50, 0.4);
    }

    .rank-4-plus .rank-badge {
        background: var(--primary);
        box-shadow: 0 4px 15px rgba(23, 143, 120, 0.3);
    }

    .name-cell {
        font-weight: 600;
        color: var(--text-dark);
    }

    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-anggota {
        background: #E3F2FD;
        color: #1976D2;
    }

    .role-guru {
        background: #F3E5F5;
        color: #7B1FA2;
    }

    .stat-number {
        font-weight: 700;
        color: var(--primary);
    }

    .my-rank {
        background: linear-gradient(135deg, rgba(23, 143, 120, 0.1), rgba(255, 122, 89, 0.1));
        border-left: 4px solid var(--primary);
    }

    .my-rank .my-rank-label {
        display: inline-block;
        background: var(--primary);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-left: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .leaderboard-header h1 {
            font-size: 1.8rem;
        }

        .leaderboard-stats {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .leaderboard-table {
            overflow-x: auto;
        }

        .leaderboard-table table {
            font-size: 0.9rem;
        }

        .leaderboard-table td,
        .leaderboard-table th {
            padding: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="leaderboard-container">
    <!-- Header -->
    <div class="leaderboard-header">
        <h1>🏆 Leaderboard Peminjam Buku</h1>
        <p>Peringkat peminjam buku terbanyak di perpustakaan</p>
    </div>

    <!-- Stats Cards -->
    <div class="leaderboard-stats">
        <div class="stat-card">
            <span class="number" id="total-peserta">0</span>
            <span class="label">Peserta Aktif</span>
        </div>
        <div class="stat-card">
            <span class="number" id="total-peminjaman">0</span>
            <span class="label">Total Peminjaman</span>
        </div>
        <div class="stat-card">
            <span class="number" id="tertinggi">0</span>
            <span class="label">Peminjaman Tertinggi</span>
        </div>
        @if (auth()->check())
            <div class="stat-card">
                <span class="number" id="my-rank">-</span>
                <span class="label">Peringkat Saya</span>
            </div>
        @endif
    </div>

    <!-- Leaderboard Table -->
    <div class="leaderboard-table">
        <table>
            <thead>
                <tr>
                    <th width="80">Peringkat</th>
                    <th>Nama</th>
                    <th width="120">Peran</th>
                    <th width="180">Total Peminjaman</th>
                    <th width="180">Dikembalikan</th>
                </tr>
            </thead>
            <tbody id="leaderboard-body">
                <tr class="empty-state">
                    <td colspan="5">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada data peminjaman</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Last Updated -->
    <div style="text-align: center; margin-top: 1.5rem; color: rgba(255, 255, 255, 0.8); z-index: 10; position: relative;">
        <small>Terakhir diperbarui: <span id="updated-at">-</span></small>
    </div>
</div>

<script>
    // Load leaderboard data
    async function loadLeaderboard() {
        try {
            const response = await fetch('{{ route('leaderboard.live') }}');
            const data = await response.json();

            // Update stats
            document.getElementById('total-peserta').textContent = data.total_peserta || 0;
            document.getElementById('total-peminjaman').textContent = data.total_peminjaman || 0;
            document.getElementById('tertinggi').textContent = data.peminjaman_tertinggi || 0;
            document.getElementById('updated-at').textContent = data.updated_at;

            // Update leaderboard table
            const tbody = document.getElementById('leaderboard-body');
            tbody.innerHTML = '';

            if (data.items && data.items.length > 0) {
                data.items.forEach((item, index) => {
                    const rank = index + 1;
                    const row = document.createElement('tr');
                    
                    let rowClass = `rank-${rank > 3 ? '4-plus' : rank}`;
                    if ({{ auth()->check() ? 'true' : 'false' }} && item.id === {{ auth()->id() ?? 'null' }}) {
                        rowClass += ' my-rank';
                        document.getElementById('my-rank').textContent = rank;
                    }
                    
                    row.className = rowClass;
                    
                    const medal = rank === 1 ? '🥇' : rank === 2 ? '🥈' : rank === 3 ? '🥉' : '';
                    
                    row.innerHTML = `
                        <td>
                            <div class="rank-badge">${medal || rank}</div>
                        </td>
                        <td class="name-cell">
                            ${item.nama}
                            ${{{ auth()->check() ? 'true' : 'false' }} && item.id === {{ auth()->id() ?? 'null' }} ? '<span class="my-rank-label">Saya</span>' : ''}
                        </td>
                        <td>
                            <span class="role-badge role-${item.role.toLowerCase()}">${item.role}</span>
                        </td>
                        <td>
                            <span class="stat-number">${item.total_peminjaman}</span>
                        </td>
                        <td>
                            <span class="stat-number">${item.total_dikembalikan || 0}</span>
                        </td>
                    `;
                    
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = `
                    <tr class="empty-state">
                        <td colspan="5">
                            <i class="fas fa-inbox"></i>
                            <p>Belum ada data peminjaman</p>
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error loading leaderboard:', error);
        }
    }

    // Load on page load
    loadLeaderboard();

    // Auto refresh every 30 seconds
    setInterval(loadLeaderboard, 30000);
</script>
@endsection
