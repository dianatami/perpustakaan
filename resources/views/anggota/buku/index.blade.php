@extends('layout.anggota')
@section('title','Daftar Buku')
@section('content')
@php($portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota')
<style>
    .page-hero-buku {
        border-radius: 28px;
        background: linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        color: #f7f2e8;
        padding: 32px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.18);
    }

    .page-hero-buku::before,
    .page-hero-buku::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        opacity: 0.24;
    }

    .page-hero-buku::before {
        width: 200px;
        height: 200px;
        right: -80px;
        top: -80px;
        background: rgba(255, 201, 92, 0.25);
    }

    .page-hero-buku::after {
        width: 170px;
        height: 170px;
        left: -70px;
        bottom: -70px;
        background: rgba(23, 143, 120, 0.24);
    }

    .page-kicker {
        text-transform: uppercase;
        letter-spacing: 0.18em;
        font-size: 0.75rem;
        margin-bottom: 16px;
        color: rgba(247, 242, 232, 0.85);
        font-weight: 700;
    }

    .page-title {
        font-size: 2.5rem;
        line-height: 1.05;
        margin-bottom: 14px;
        font-weight: 800;
    }

    .page-copy {
        font-size: 1rem;
        max-width: 680px;
        color: rgba(247, 242, 232, 0.92);
    }

    .book-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.95rem 1.3rem;
        border-radius: 999px;
        background: rgba(247, 242, 232, 0.18);
        border: 1px solid rgba(247, 242, 232, 0.28);
        color: #f7f2e8;
        font-weight: 700;
    }

    .book-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }

    .filter-panel {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(16, 23, 46, 0.08);
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08);
        padding: 18px;
        margin-bottom: 26px;
    }

    .filter-panel .form-select,
    .filter-panel .form-control {
        border-radius: 12px;
        border: 1px solid rgba(16, 23, 46, 0.12);
        padding: 10px 14px;
    }

    .rack-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        background: rgba(255, 122, 89, 0.14);
        color: #b94b2f;
        margin-bottom: 10px;
    }

    .book-card {
        border-radius: 24px;
        border: 1px solid rgba(16, 23, 46, 0.08);
        background: #ffffff;
        box-shadow: 0 22px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        min-height: 520px;
        display: flex;
        flex-direction: column;
    }

    .book-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
    }

    .book-cover {
        height: 300px;
        object-fit: cover;
        width: 100%;
    }

    .book-cover-placeholder {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        color: #334155;
        font-size: 1rem;
        font-weight: 700;
    }

    .book-body {
        padding: 22px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: #10172e;
    }

    .book-author {
        color: #5b6687;
        font-size: 0.95rem;
        margin-bottom: 16px;
    }

    .book-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #1d4f78 0%, #ff7a59 100%);
        color: #fff;
        padding: 0.55rem 0.9rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 16px;
        width: fit-content;
    }

    .book-meta {
        color: #5b6687;
        margin-bottom: 22px;
        font-weight: 600;
    }

    .btn-detail {
        margin-top: auto;
        background: #f7f2e8;
        color: #10172e;
        border: none;
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        background: #fff2d7;
    }

    .empty-state {
        text-align: center;
        padding: 72px 20px;
    }

    .empty-state .alert-warning {
        border-radius: 20px;
        background: #fff8ec;
        color: #5b6687;
        border: 1px solid rgba(255, 185, 91, 0.3);
    }

    @media (max-width: 767px) {
        .page-hero-buku {
            padding: 20px 18px;
            border-radius: 20px;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .book-card {
            min-height: auto;
            border-radius: 20px;
        }

        .book-cover,
        .book-cover-placeholder {
            height: 220px;
        }

        .book-body {
            padding: 16px;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.45rem;
        }

        .book-count-badge {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid py-5">
    <div class="page-hero-buku">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="page-kicker">Daftar Buku</p>
                <h1 class="page-title">Jelajahi koleksi terbaik kami</h1>
                <p class="page-copy">Buku-buku dengan kategori lengkap, tampilan yang terasa modern, dan informasi cepat untuk membantu kamu memilih bacaan berikutnya.</p>
            </div>
            <div class="book-count-badge">
                <i class="bi bi-collection-fill"></i>
                {{ $bookCount ?? 0 }} Buku tersedia
            </div>
        </div>
    </div>

    <div class="filter-panel">
        <form action="{{ route($portalPrefix . '.buku.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filter Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ (string) $kategoriId === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name_category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filter Rak</label>
                <select name="rack" class="form-select">
                    <option value="">Semua Rak</option>
                    @foreach($racks ?? [] as $rack)
                        <option value="{{ $rack->id }}" {{ (string) $rackId === (string) $rack->id ? 'selected' : '' }}>{{ $rack->code }} - {{ $rack->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-grid">
                <button class="btn btn-primary">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <div class="book-list">
        @forelse ($books as $book)
            <article class="book-card">
                @if ($book->cover)
                    <img src="{{ asset('storage/'.$book->cover) }}" class="book-cover" alt="Cover {{ $book->title }}">
                @else
                    <div class="book-cover-placeholder">
                        Tidak ada gambar
                    </div>
                @endif

                <div class="book-body">
                    <h3 class="book-title">{{ $book->title }}</h3>
                    <p class="book-author">{{ $book->author }}</p>
                    <span class="book-tag">{{ $book->category->name_category ?? 'Tanpa Kategori' }}</span>
                    @if($book->rack)
                        <span class="rack-tag"><i class="bi bi-archive"></i> {{ $book->rack->code }}</span>
                    @endif
                    <p class="book-meta">Stok tersedia: <strong>{{ $book->stock }}</strong></p>
                    <div class="mt-auto pt-2">
                        <a href="{{ route($portalPrefix . '.buku.show', $book->id) }}" class="btn-detail text-center w-100 d-block">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <div class="alert alert-warning">
                    Tidak ada buku tersedia
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection