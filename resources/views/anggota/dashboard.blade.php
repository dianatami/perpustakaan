@extends('layout.anggota')
@section('title', 'Beranda Murid')

@section('content')
@php
    $portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota';
@endphp

<style>
    .member-hero {
        border-radius: 24px;
        background: linear-gradient(140deg, #0b7ca6 0%, #08526f 70%, #ff6b6b 150%);
        color: #fff;
        padding: 28px;
        box-shadow: 0 24px 40px rgba(13, 87, 112, 0.25);
        position: relative;
        overflow: hidden;
    }

    .member-hero::after {
        content: '';
        position: absolute;
        width: 290px;
        height: 290px;
        border-radius: 999px;
        right: -130px;
        top: -120px;
        background: rgba(255, 255, 255, 0.16);
    }

    .member-hero > * {
        position: relative;
        z-index: 1;
    }

    .member-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
    }

    .member-subtitle {
        margin: 8px 0 0;
        color: rgba(255, 255, 255, 0.92);
        max-width: 620px;
    }

    .member-meta {
        margin-top: 12px;
        display: inline-flex;
        gap: 7px;
        align-items: center;
        font-size: 0.85rem;
        border-radius: 999px;
        padding: 7px 12px;
        background: rgba(255, 255, 255, 0.18);
    }

    .info-card {
        border: 1px solid rgba(13, 86, 106, 0.14);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 20px rgba(20, 59, 75, 0.08);
        padding: 18px;
        height: 100%;
    }

    .info-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #617b86;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .info-value {
        margin-top: 8px;
        font-size: 1.8rem;
        font-weight: 800;
        color: #133643;
        line-height: 1;
    }

    .action-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .action-btn {
        border-radius: 999px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 9px 14px;
        border: 1px solid rgba(13, 86, 106, 0.22);
        color: #0b6f94;
        background: #fff;
        transition: transform 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        color: #0b6f94;
    }

    .book-section {
        margin-top: 22px;
        border: 1px solid rgba(13, 86, 106, 0.14);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 20px rgba(20, 59, 75, 0.06);
        padding: 18px;
    }

    .book-heading {
        margin: 0 0 14px;
        color: #133643;
        font-size: 1.06rem;
        font-weight: 800;
    }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(140px, 1fr));
        gap: 12px;
    }

    .book-card {
        border: 1px solid #dde9ee;
        border-radius: 14px;
        padding: 10px;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #fefefe;
    }

    .book-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 18px rgba(24, 74, 90, 0.12);
        color: inherit;
    }

    .book-cover {
        width: 100%;
        height: 155px;
        border-radius: 10px;
        object-fit: cover;
        background: #dbe9ef;
    }

    .book-title {
        margin: 10px 0 3px;
        font-size: 0.86rem;
        font-weight: 700;
        color: #173845;
        line-height: 1.2;
    }

    .book-author {
        margin: 0;
        font-size: 0.75rem;
        color: #687f8a;
    }

    @media (max-width: 1199px) {
        .book-grid {
            grid-template-columns: repeat(4, minmax(140px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .book-grid {
            grid-template-columns: repeat(3, minmax(140px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .member-title {
            font-size: 1.45rem;
        }

        .book-grid {
            grid-template-columns: repeat(2, minmax(140px, 1fr));
        }
    }
</style>

<div class="member-hero mb-3">
    <h2 class="member-title">Halo, {{ Auth::user()->nama }}.</h2>
    <p class="member-subtitle">Lanjutkan petualangan literasi Anda hari ini. Temukan buku baru, cek kategori favorit, dan pantau riwayat peminjaman pribadi Anda.</p>
    <span class="member-meta">
        <i class="bi bi-calendar-week"></i>
        Anggota sejak {{ Auth::user()->created_at->format('d M Y') }}
    </span>

    <div class="action-list">
        <a href="{{ route($portalPrefix . '.buku.index') }}" class="action-btn"><i class="bi bi-journal-text"></i> Jelajahi Buku</a>
        <a href="{{ route($portalPrefix . '.kategori.index') }}" class="action-btn"><i class="bi bi-collection"></i> Lihat Kategori</a>
        <a href="{{ route($portalPrefix . '.profil.detail') }}" class="action-btn"><i class="bi bi-person-vcard"></i> Profil Saya</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="info-card">
            <div class="info-label">Buku Tersedia</div>
            <div class="info-value">{{ $bukuTersedia ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card">
            <div class="info-label">Sedang Dipinjam</div>
            <div class="info-value">{{ $bookrents ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card">
            <div class="info-label">Total Riwayat</div>
            <div class="info-value">{{ $riyawatPinjam ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="book-section">
    <h3 class="book-heading">Rekomendasi Koleksi</h3>

    <div class="book-grid">
        @forelse(($books ?? collect())->take(10) as $book)
            <a href="{{ route($portalPrefix . '.buku.show', $book->id) }}" class="book-card">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="book-cover">
                @else
                    <div class="book-cover d-flex align-items-center justify-content-center text-muted small">Tanpa Cover</div>
                @endif

                <h4 class="book-title">{{ $book->title }}</h4>
                <p class="book-author">{{ $book->author }}</p>
            </a>
        @empty
            <div class="text-muted">Belum ada data buku untuk ditampilkan.</div>
        @endforelse
    </div>
</div>
@endsection
