@extends('layout.anggota')
@section('title', 'Beranda Guru')

@section('content')
@php
    $portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota';
@endphp

<style>
    .teacher-hero {
        border-radius: 24px;
        background: linear-gradient(145deg, #1f7a46 0%, #145d34 68%, #f3a530 160%);
        color: #fff;
        padding: 28px;
        box-shadow: 0 24px 44px rgba(20, 86, 49, 0.27);
        position: relative;
        overflow: hidden;
    }

    .teacher-hero::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 999px;
        right: -120px;
        top: -120px;
        background: rgba(255, 255, 255, 0.15);
    }

    .teacher-hero > * {
        position: relative;
        z-index: 1;
    }

    .teacher-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
    }

    .teacher-subtitle {
        margin: 8px 0 0;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
    }

    .teacher-actions {
        margin-top: 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .teacher-action-btn {
        text-decoration: none;
        border-radius: 999px;
        padding: 9px 15px;
        font-size: 0.87rem;
        font-weight: 700;
        color: #145d34;
        background: #fff;
        transition: transform 0.2s ease;
    }

    .teacher-action-btn:hover {
        transform: translateY(-2px);
        color: #145d34;
    }

    .teacher-stat {
        border-radius: 16px;
        border: 1px solid rgba(19, 84, 49, 0.14);
        background: #fff;
        padding: 18px;
        box-shadow: 0 12px 22px rgba(15, 62, 35, 0.09);
        height: 100%;
    }

    .teacher-stat-label {
        font-size: 0.8rem;
        letter-spacing: 0.7px;
        font-weight: 700;
        text-transform: uppercase;
        color: #688170;
    }

    .teacher-stat-value {
        margin-top: 8px;
        font-size: 1.8rem;
        line-height: 1;
        color: #143724;
        font-weight: 800;
    }

    .teacher-section {
        margin-top: 22px;
        border-radius: 16px;
        border: 1px solid rgba(19, 84, 49, 0.14);
        background: #fff;
        padding: 18px;
        box-shadow: 0 10px 20px rgba(15, 62, 35, 0.07);
    }

    .teacher-section-title {
        margin: 0 0 12px;
        font-size: 1.05rem;
        font-weight: 800;
        color: #143724;
    }

    .teacher-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(150px, 1fr));
        gap: 12px;
    }

    .teacher-book {
        border: 1px solid #dbe8de;
        border-radius: 13px;
        padding: 10px;
        text-decoration: none;
        color: inherit;
        background: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .teacher-book:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 18px rgba(19, 82, 44, 0.13);
        color: inherit;
    }

    .teacher-book img,
    .teacher-book-cover {
        width: 100%;
        height: 158px;
        border-radius: 10px;
        object-fit: cover;
        background: #d8e8dc;
    }

    .teacher-book-title {
        margin: 9px 0 3px;
        font-size: 0.86rem;
        font-weight: 700;
        color: #183d27;
        line-height: 1.25;
    }

    .teacher-book-meta {
        margin: 0;
        font-size: 0.75rem;
        color: #6a8171;
    }

    @media (max-width: 1199px) {
        .teacher-grid {
            grid-template-columns: repeat(3, minmax(150px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .teacher-title {
            font-size: 1.5rem;
        }

        .teacher-grid {
            grid-template-columns: repeat(2, minmax(150px, 1fr));
        }
    }
</style>

<div class="teacher-hero mb-3">
    <h2 class="teacher-title">Selamat Datang, {{ Auth::user()->nama }}</h2>
    <p class="teacher-subtitle">
        Portal guru memberi akses cepat ke koleksi pembelajaran. Gunakan ruang ini untuk menelusuri referensi, mengelola pinjaman pribadi, dan mendukung kegiatan literasi kelas.
    </p>

    <div class="teacher-actions">
        <a href="{{ route($portalPrefix . '.buku.index') }}" class="teacher-action-btn"><i class="bi bi-search"></i> Cari Buku</a>
        <a href="{{ route($portalPrefix . '.kategori.index') }}" class="teacher-action-btn"><i class="bi bi-grid-3x3-gap"></i> Jelajahi Kategori</a>
        <a href="{{ route($portalPrefix . '.profil.detail') }}" class="teacher-action-btn"><i class="bi bi-person-vcard"></i> Kelola Profil</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3 col-6">
        <div class="teacher-stat">
            <div class="teacher-stat-label">Koleksi Tersedia</div>
            <div class="teacher-stat-value">{{ $bukuTersedia ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="teacher-stat">
            <div class="teacher-stat-label">Kategori</div>
            <div class="teacher-stat-value">{{ $totalKategori ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="teacher-stat">
            <div class="teacher-stat-label">Dipinjam</div>
            <div class="teacher-stat-value">{{ $bookrents ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="teacher-stat">
            <div class="teacher-stat-label">Riwayat</div>
            <div class="teacher-stat-value">{{ $riyawatPinjam ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="teacher-section">
    <h3 class="teacher-section-title">Rekomendasi Bacaan Pengajaran</h3>

    <div class="teacher-grid">
        @forelse(($books ?? collect())->take(8) as $book)
            <a href="{{ route($portalPrefix . '.buku.show', $book->id) }}" class="teacher-book">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
                @else
                    <div class="teacher-book-cover d-flex align-items-center justify-content-center text-muted small">Tanpa Cover</div>
                @endif
                <h4 class="teacher-book-title">{{ $book->title }}</h4>
                <p class="teacher-book-meta">{{ $book->author }}</p>
            </a>
        @empty
            <div class="text-muted">Belum ada koleksi rekomendasi.</div>
        @endforelse
    </div>
</div>
@endsection
