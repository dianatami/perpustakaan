@extends('layout.anggota')
@section('title', 'Beranda Guru')

@section('content')
@php
    $portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota';

    $guruStats = [
        ['label' => 'Koleksi Tersedia', 'value' => $bukuTersedia ?? 0, 'icon' => 'bi-journal-richtext', 'tone' => 'teal'],
        ['label' => 'Total Kategori', 'value' => $totalKategori ?? 0, 'icon' => 'bi-grid-3x3-gap-fill', 'tone' => 'ocean'],
        ['label' => 'Sedang Dipinjam', 'value' => $bookrents ?? 0, 'icon' => 'bi-arrow-repeat', 'tone' => 'amber'],
        ['label' => 'Riwayat Pinjam', 'value' => $riyawatPinjam ?? 0, 'icon' => 'bi-clock-history', 'tone' => 'indigo'],
    ];
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
        background: linear-gradient(145deg, #10172e, #1d4f78);
    }

    .portal-nav-link.active {
        background: linear-gradient(130deg, #1d4f78, #178f78);
    }

    .portal-main {
        width: min(1240px, calc(100% - 26px));
    }

    .mentor-wrap {
        display: grid;
        gap: 16px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .mentor-hero {
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background:
            linear-gradient(118deg, rgba(16, 23, 46, 0.93) 0%, rgba(29, 79, 120, 0.9) 47%, rgba(23, 143, 120, 0.88) 100%);
        color: #f7f2e8;
        box-shadow: 0 26px 46px rgba(17, 41, 63, 0.25);
        padding: 26px;
        position: relative;
        overflow: hidden;
    }

    .mentor-hero::before {
        content: '';
        position: absolute;
        width: 270px;
        height: 270px;
        border-radius: 999px;
        right: -110px;
        top: -120px;
        background: rgba(255, 201, 92, 0.25);
    }

    .mentor-hero::after {
        content: '';
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 999px;
        left: -70px;
        bottom: -80px;
        background: rgba(255, 122, 89, 0.28);
    }

    .mentor-bead {
        position: absolute;
        border-radius: 999px;
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.18);
        animation: drift 3.3s ease-in-out infinite;
        z-index: 1;
    }

    .mentor-bead.one {
        width: 14px;
        height: 14px;
        top: 34px;
        right: 138px;
        background: #ffc95c;
    }

    .mentor-bead.two {
        width: 10px;
        height: 10px;
        top: 62px;
        right: 104px;
        background: #ff7a59;
        animation-delay: 0.8s;
    }

    .mentor-bead.three {
        width: 12px;
        height: 12px;
        left: 47%;
        bottom: 32px;
        background: #88e4cf;
        animation-delay: 1.4s;
    }

    .mentor-hero > * {
        position: relative;
        z-index: 2;
    }

    .mentor-kicker {
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.77rem;
        font-weight: 700;
        color: rgba(247, 242, 232, 0.82);
    }

    .mentor-title {
        margin: 10px 0 8px;
        font-size: 1.55rem;
        font-family: 'Unbounded', sans-serif;
        line-height: 1.34;
    }

    .mentor-subtitle {
        margin: 0;
        max-width: 790px;
        color: rgba(247, 242, 232, 0.9);
    }

    .mentor-chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .mentor-chip {
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #f7f2e8;
        background: rgba(247, 242, 232, 0.19);
        border: 1px solid rgba(247, 242, 232, 0.33);
    }

    .mentor-actions {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .mentor-action {
        text-decoration: none;
        border-radius: 999px;
        padding: 10px 14px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #0f1d3a;
        background: #f7f2e8;
        transition: transform 0.22s ease;
    }

    .mentor-action:hover {
        transform: translateY(-2px);
        color: #0f1d3a;
    }

    .mentor-action.alt {
        color: #f7f2e8;
        background: rgba(247, 242, 232, 0.18);
        border: 1px solid rgba(247, 242, 232, 0.34);
    }

    .mentor-action.alt:hover {
        color: #f7f2e8;
    }

    .mentor-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .mentor-stat-card {
        border-radius: 16px;
        border: 1px solid rgba(19, 33, 64, 0.13);
        background: rgba(255, 255, 255, 0.79);
        backdrop-filter: blur(7px);
        box-shadow: 0 13px 24px rgba(17, 42, 67, 0.08);
        padding: 14px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .mentor-stat-label {
        margin: 0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        color: #5b6687;
    }

    .mentor-stat-value {
        margin: 7px 0 0;
        font-family: 'Unbounded', sans-serif;
        font-size: 1.34rem;
        line-height: 1;
        color: #10172e;
    }

    .mentor-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
    }

    .mentor-stat-icon.teal { background: linear-gradient(140deg, #178f78, #116557); }
    .mentor-stat-icon.ocean { background: linear-gradient(140deg, #1d4f78, #133553); }
    .mentor-stat-icon.amber { background: linear-gradient(140deg, #ffb33d, #e5891f); }
    .mentor-stat-icon.indigo { background: linear-gradient(140deg, #4d5ea9, #303d7a); }

    .mentor-panel-grid {
        display: grid;
        grid-template-columns: 0.8fr 1.2fr;
        gap: 12px;
    }

    .mentor-panel {
        border-radius: 18px;
        border: 1px solid rgba(19, 33, 64, 0.14);
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(7px);
        box-shadow: 0 12px 22px rgba(17, 42, 67, 0.07);
        padding: 16px;
    }

    .mentor-panel-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #10172e;
    }

    .mentor-panel-note {
        margin: 6px 0 12px;
        font-size: 0.84rem;
        color: #556284;
    }

    .focus-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 0;
        border-bottom: 1px dashed #d5dee7;
        font-size: 0.86rem;
    }

    .focus-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .focus-label {
        font-weight: 700;
        color: #556284;
    }

    .focus-value {
        font-family: 'Unbounded', sans-serif;
        color: #10172e;
        font-size: 0.84rem;
    }

    .focus-pill {
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .focus-pill.good {
        background: #d4f2e8;
        color: #11533f;
    }

    .focus-pill.warn {
        background: #ffe4cd;
        color: #7b4315;
    }

    .mentor-book-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .mentor-book {
        text-decoration: none;
        border-radius: 14px;
        border: 1px solid #d8e1ea;
        padding: 9px;
        background: #fff;
        color: inherit;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .mentor-book:hover {
        transform: translateY(-3px);
        color: inherit;
        box-shadow: 0 10px 18px rgba(23, 50, 77, 0.12);
    }

    .mentor-book-cover {
        width: 100%;
        height: 150px;
        border-radius: 10px;
        object-fit: cover;
        background: #dce8ef;
    }

    .mentor-book-title {
        margin: 8px 0 2px;
        font-size: 0.83rem;
        font-weight: 700;
        color: #132545;
        line-height: 1.28;
    }

    .mentor-book-author {
        margin: 0;
        font-size: 0.74rem;
        color: #62718f;
    }

    @keyframes drift {
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
        .mentor-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .mentor-book-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 992px) {
        .mentor-panel-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .mentor-hero {
            padding: 20px;
        }

        .mentor-title {
            font-size: 1.25rem;
        }

        .mentor-stat-grid,
        .mentor-book-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<div class="mentor-wrap">
    <section class="mentor-hero">
        <span class="mentor-bead one"></span>
        <span class="mentor-bead two"></span>
        <span class="mentor-bead three"></span>

        <p class="mentor-kicker">Portal Pengajar</p>
        <h2 class="mentor-title">Selamat datang, {{ Auth::user()->nama }}.</h2>
        <p class="mentor-subtitle">
            Dasbor ini membantu Anda memetakan bahan bacaan, memantau pinjaman pribadi, dan memperkaya literasi kelas dengan pengalaman visual yang selaras dengan landing page.
        </p>

        <div class="mentor-chip-list">
            <span class="mentor-chip"><i class="bi bi-journal-check"></i> Koleksi aktif {{ $bukuTersedia ?? 0 }}</span>
            <span class="mentor-chip"><i class="bi bi-collection"></i> Kategori {{ $totalKategori ?? 0 }}</span>
            <span class="mentor-chip"><i class="bi bi-clock-history"></i> Riwayat {{ $riyawatPinjam ?? 0 }}</span>
        </div>

        <div class="mentor-actions">
            <a href="{{ route($portalPrefix . '.buku.index') }}" class="mentor-action">
                <i class="bi bi-search"></i> Cari Buku
            </a>
            <a href="{{ route($portalPrefix . '.kategori.index') }}" class="mentor-action alt">
                <i class="bi bi-grid-3x3-gap"></i> Jelajahi Kategori
            </a>
            <a href="{{ route($portalPrefix . '.profil.detail') }}" class="mentor-action alt">
                <i class="bi bi-person-vcard"></i> Kelola Profil
            </a>
        </div>
    </section>

    <section class="mentor-stat-grid">
        @foreach ($guruStats as $stat)
            <article class="mentor-stat-card">
                <div>
                    <p class="mentor-stat-label">{{ $stat['label'] }}</p>
                    <p class="mentor-stat-value">{{ $stat['value'] }}</p>
                </div>
                <span class="mentor-stat-icon {{ $stat['tone'] }}">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </span>
            </article>
        @endforeach
    </section>

    <section class="mentor-panel-grid">
        <article class="mentor-panel">
            <h3 class="mentor-panel-title">Fokus Hari Ini</h3>
            <p class="mentor-panel-note">Ringkasan target bacaan dan aktivitas pinjaman Anda.</p>

            <div class="focus-item">
                <span class="focus-label">Buku siap dipinjam</span>
                <span class="focus-value">{{ $bukuTersedia ?? 0 }}</span>
            </div>
            <div class="focus-item">
                <span class="focus-label">Pinjaman aktif</span>
                <span class="focus-value">{{ $bookrents ?? 0 }}</span>
            </div>
            <div class="focus-item">
                <span class="focus-label">Total histori</span>
                <span class="focus-value">{{ $riyawatPinjam ?? 0 }}</span>
            </div>

            <span class="focus-pill {{ ($bookrents ?? 0) > 0 ? 'warn' : 'good' }}">
                <i class="bi bi-stars"></i>
                {{ ($bookrents ?? 0) > 0 ? 'Ada pinjaman yang sedang berjalan' : 'Semua pinjaman telah tertutup' }}
            </span>
        </article>

        <article class="mentor-panel">
            <h3 class="mentor-panel-title">Rekomendasi Bacaan Pengajaran</h3>
            <p class="mentor-panel-note">Koleksi terbaru yang relevan untuk pendampingan kelas.</p>

            <div class="mentor-book-grid">
                @forelse (($books ?? collect())->take(8) as $book)
                    <a href="{{ route($portalPrefix . '.buku.show', $book->id) }}" class="mentor-book">
                        @if ($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="mentor-book-cover">
                        @else
                            <div class="mentor-book-cover d-flex align-items-center justify-content-center text-muted small">Tanpa Cover</div>
                        @endif
                        <h4 class="mentor-book-title">{{ $book->title }}</h4>
                        <p class="mentor-book-author">{{ $book->author }}</p>
                    </a>
                @empty
                    <div class="text-muted">Belum ada koleksi rekomendasi.</div>
                @endforelse
            </div>
        </article>
    </section>

    @include('partials.leaderboard-siswa')
</div>
@endsection