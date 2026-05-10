@extends('layout.anggota')
@section('title', 'Beranda Murid')

@section('content')
@php
    $portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota';
    $displayBooksCount = ($books ?? collect())->count();

    $memberStats = [
        ['label' => 'Buku Tersedia', 'value' => $bukuTersedia ?? 0, 'icon' => 'bi-journal-bookmark-fill', 'tone' => 'teal'],
        ['label' => 'Sedang Dipinjam', 'value' => $bookrents ?? 0, 'icon' => 'bi-arrow-repeat', 'tone' => 'ocean'],
        ['label' => 'Total Riwayat', 'value' => $riyawatPinjam ?? 0, 'icon' => 'bi-clock-history', 'tone' => 'indigo'],
        ['label' => 'Koleksi Ditampilkan', 'value' => $displayBooksCount, 'icon' => 'bi-collection-fill', 'tone' => 'coral'],
    ];

    $totalRiwayat = max(1, (int) ($riyawatPinjam ?? 0));
    $pinjamAktif = min((int) ($bookrents ?? 0), $totalRiwayat);
    $aktifPersen = (int) round(($pinjamAktif / $totalRiwayat) * 100);
    $selesaiPersen = 100 - $aktifPersen;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Unbounded:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .portal-header {
        border-bottom: 1px solid rgba(16, 23, 46, 0.12);
        background: rgba(255, 255, 255, 0.76);
        backdrop-filter: blur(9px);
    }

    .portal-brand-title {
        font-family: 'Unbounded', sans-serif;
        color: #10172e;
        letter-spacing: 0.02em;
    }

    .portal-brand-subtitle,
    .portal-nav-link,
    .portal-user-chip,
    .portal-footer {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .portal-brand-icon {
        background: linear-gradient(145deg, #1d4f78, #178f78);
    }

    .portal-nav-link.active {
        background: linear-gradient(130deg, #1d4f78, #ff7a59);
    }

    .portal-main {
        width: min(1240px, calc(100% - 26px));
    }

    .member-wrap {
        display: grid;
        gap: 16px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .member-hero {
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background:
            linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        color: #f7f2e8;
        box-shadow: 0 25px 44px rgba(18, 43, 69, 0.25);
        padding: 26px;
        position: relative;
        overflow: hidden;
    }

    .member-hero::before {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        right: -110px;
        top: -118px;
        background: rgba(255, 201, 92, 0.24);
    }

    .member-hero::after {
        content: '';
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 999px;
        left: -62px;
        bottom: -78px;
        background: rgba(23, 143, 120, 0.3);
    }

    .member-bead {
        position: absolute;
        border-radius: 999px;
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.18);
        animation: beadFlow 3.5s ease-in-out infinite;
        z-index: 1;
    }

    .member-bead.one {
        width: 14px;
        height: 14px;
        top: 34px;
        right: 142px;
        background: #ffc95c;
    }

    .member-bead.two {
        width: 10px;
        height: 10px;
        top: 62px;
        right: 108px;
        background: #ff7a59;
        animation-delay: 0.9s;
    }

    .member-bead.three {
        width: 12px;
        height: 12px;
        left: 46%;
        bottom: 30px;
        background: #8de4d1;
        animation-delay: 1.5s;
    }

    .member-hero > * {
        position: relative;
        z-index: 2;
    }

    .member-kicker {
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-size: 0.77rem;
        font-weight: 700;
        color: rgba(247, 242, 232, 0.82);
    }

    .member-title {
        margin: 10px 0 8px;
        font-family: 'Unbounded', sans-serif;
        font-size: 1.55rem;
        line-height: 1.34;
    }

    .member-subtitle {
        margin: 0;
        max-width: 760px;
        color: rgba(247, 242, 232, 0.9);
    }

    .member-meta {
        margin-top: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 0.79rem;
        font-weight: 700;
        color: #f7f2e8;
        background: rgba(247, 242, 232, 0.17);
        border: 1px solid rgba(247, 242, 232, 0.34);
    }

    .member-actions {
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .member-action {
        text-decoration: none;
        border-radius: 999px;
        padding: 10px 14px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #0f1d3a;
        background: #f7f2e8;
        transition: transform 0.22s ease;
    }

    .member-action:hover {
        transform: translateY(-2px);
        color: #0f1d3a;
    }

    .member-action.alt {
        color: #f7f2e8;
        background: rgba(247, 242, 232, 0.18);
        border: 1px solid rgba(247, 242, 232, 0.34);
    }

    .member-action.alt:hover {
        color: #f7f2e8;
    }

    .member-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .member-stat-card {
        border-radius: 16px;
        border: 1px solid rgba(19, 33, 64, 0.13);
        background: rgba(255, 255, 255, 0.79);
        backdrop-filter: blur(7px);
        box-shadow: 0 12px 24px rgba(17, 42, 67, 0.08);
        padding: 14px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .member-stat-label {
        margin: 0;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        color: #5b6687;
    }

    .member-stat-value {
        margin: 7px 0 0;
        font-family: 'Unbounded', sans-serif;
        font-size: 1.34rem;
        line-height: 1;
        color: #10172e;
    }

    .member-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
    }

    .member-stat-icon.teal { background: linear-gradient(140deg, #178f78, #116557); }
    .member-stat-icon.ocean { background: linear-gradient(140deg, #1d4f78, #133553); }
    .member-stat-icon.indigo { background: linear-gradient(140deg, #4d5ea9, #303d7a); }
    .member-stat-icon.coral { background: linear-gradient(140deg, #ff7a59, #db512f); }

    .member-panel-grid {
        display: grid;
        grid-template-columns: 0.78fr 1.22fr;
        gap: 12px;
    }

    .member-panel {
        border-radius: 18px;
        border: 1px solid rgba(19, 33, 64, 0.14);
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(7px);
        box-shadow: 0 12px 22px rgba(17, 42, 67, 0.07);
        padding: 16px;
    }

    .member-panel-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #10172e;
    }

    .member-panel-note {
        margin: 6px 0 12px;
        font-size: 0.84rem;
        color: #556284;
    }

    .activity-row {
        margin-bottom: 12px;
    }

    .activity-row:last-child {
        margin-bottom: 0;
    }

    .activity-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        font-size: 0.85rem;
        color: #4f5d7f;
        font-weight: 700;
    }

    .activity-track {
        height: 10px;
        border-radius: 999px;
        background: #e4ebef;
        overflow: hidden;
    }

    .activity-fill {
        height: 100%;
        border-radius: inherit;
    }

    .activity-fill.active {
        background: linear-gradient(90deg, #ff7a59, #ffc95c);
    }

    .activity-fill.done {
        background: linear-gradient(90deg, #178f78, #88e4d1);
    }

    .member-pill {
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .member-pill.good {
        background: #d4f2e8;
        color: #125543;
    }

    .member-pill.warn {
        background: #ffe5d1;
        color: #7a4214;
    }

    .member-book-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px;
    }

    .member-book {
        text-decoration: none;
        border-radius: 14px;
        border: 1px solid #d8e1ea;
        padding: 9px;
        background: #fff;
        color: inherit;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .member-book:hover {
        transform: translateY(-3px);
        color: inherit;
        box-shadow: 0 10px 18px rgba(23, 50, 77, 0.12);
    }

    .member-book-cover {
        width: 100%;
        height: 148px;
        border-radius: 10px;
        object-fit: cover;
        background: #dce8ef;
    }

    .member-book-title {
        margin: 8px 0 2px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #132545;
        line-height: 1.24;
    }

    .member-book-author {
        margin: 0;
        font-size: 0.74rem;
        color: #62718f;
    }

    @keyframes beadFlow {
        0%,
        100% {
            transform: translateY(0);
            opacity: 0.9;
        }
        50% {
            transform: translateY(-5px);
            opacity: 1;
        }
    }

    @media (max-width: 1200px) {
        .member-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .member-book-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 992px) {
        .member-panel-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .member-hero {
            padding: 20px;
        }

        .member-title {
            font-size: 1.25rem;
        }

        .member-stat-grid,
        .member-book-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<div class="member-wrap">
    <section class="member-hero">
        <span class="member-bead one"></span>
        <span class="member-bead two"></span>
        <span class="member-bead three"></span>

        <p class="member-kicker">Portal Murid</p>
        <h2 class="member-title">Halo, {{ Auth::user()->nama }}.</h2>
        <p class="member-subtitle">
            Jelajahi koleksi favorit, cek aktivitas peminjaman, dan lanjutkan petualangan membaca Anda dalam tampilan yang selaras dengan nuansa landing page.
        </p>

        <span class="member-meta">
            <i class="bi bi-calendar-week"></i>
            Anggota sejak {{ Auth::user()->created_at->format('d M Y') }}
        </span>

        <div class="member-actions">
            <a href="{{ route($portalPrefix . '.buku.index') }}" class="member-action">
                <i class="bi bi-journal-text"></i> Jelajahi Buku
            </a>
            <a href="{{ route($portalPrefix . '.kategori.index') }}" class="member-action alt">
                <i class="bi bi-collection"></i> Lihat Kategori
            </a>
            <a href="{{ route($portalPrefix . '.profil.detail') }}" class="member-action alt">
                <i class="bi bi-person-vcard"></i> Profil Saya
            </a>
        </div>
    </section>

    <section class="member-stat-grid">
        @foreach ($memberStats as $stat)
            <article class="member-stat-card">
                <div>
                    <p class="member-stat-label">{{ $stat['label'] }}</p>
                    <p class="member-stat-value">{{ $stat['value'] }}</p>
                </div>
                <span class="member-stat-icon {{ $stat['tone'] }}">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </span>
            </article>
        @endforeach
    </section>

    <section class="member-panel-grid">
        <article class="member-panel">
            <h3 class="member-panel-title">Aktivitas Peminjaman</h3>
            <p class="member-panel-note">Perbandingan pinjaman aktif dan pinjaman yang sudah selesai.</p>

            <div class="activity-row">
                <div class="activity-head">
                    <span>Pinjaman aktif</span>
                    <span>{{ $aktifPersen }}%</span>
                </div>
                <div class="activity-track">
                    <div class="activity-fill active" style="width: {{ $aktifPersen }}%"></div>
                </div>
            </div>

            <div class="activity-row">
                <div class="activity-head">
                    <span>Pinjaman selesai</span>
                    <span>{{ $selesaiPersen }}%</span>
                </div>
                <div class="activity-track">
                    <div class="activity-fill done" style="width: {{ $selesaiPersen }}%"></div>
                </div>
            </div>

            <span class="member-pill {{ ($bookrents ?? 0) > 0 ? 'warn' : 'good' }}">
                <i class="bi bi-stars"></i>
                {{ ($bookrents ?? 0) > 0 ? 'Masih ada buku yang sedang dipinjam' : 'Semua pinjaman saat ini sudah selesai' }}
            </span>
        </article>

        <article class="member-panel">
            <h3 class="member-panel-title">Rekomendasi Koleksi</h3>
            <p class="member-panel-note">Pilihan buku terbaru untuk memperluas referensi bacaan Anda.</p>

            <div class="member-book-grid">
                @forelse (($books ?? collect())->take(10) as $book)
                    <a href="{{ route($portalPrefix . '.buku.show', $book->id) }}" class="member-book">
                        @if ($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="member-book-cover">
                        @else
                            <div class="member-book-cover d-flex align-items-center justify-content-center text-muted small">Tanpa Cover</div>
                        @endif
                        <h4 class="member-book-title">{{ $book->title }}</h4>
                        <p class="member-book-author">{{ $book->author }}</p>
                    </a>
                @empty
                    <div class="text-muted">Belum ada data buku untuk ditampilkan.</div>
                @endforelse
            </div>
        </article>
    </section>

    @include('partials.leaderboard-peminjam')
</div>
@endsection