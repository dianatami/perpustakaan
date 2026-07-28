@extends('layout.anggota')
@section('title', 'Kategori Buku')
@section('content')
@php($portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota')

<style>
    .kategori-container {
        margin-top: 20px;
    }

    .page-hero-kategori {
        border-radius: 28px;
        background: linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        color: #f7f2e8;
        padding: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.18);
        margin-bottom: 30px;
    }

    .page-hero-kategori::before,
    .page-hero-kategori::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        opacity: 0.3;
    }

    .page-hero-kategori::before {
        width: 220px;
        height: 220px;
        right: -90px;
        top: -90px;
        background: rgba(255, 201, 92, 0.25);
    }

    .page-hero-kategori::after {
        width: 170px;
        height: 170px;
        left: -70px;
        bottom: -70px;
        background: rgba(23, 143, 120, 0.24);
    }

    .header-title {
        margin: 0;
        font-size: 2.4rem;
        line-height: 1.05;
        font-weight: 800;
    }

    .header-subtitle {
        margin: 16px 0 0;
        color: rgba(247, 242, 232, 0.9);
        max-width: 720px;
        font-size: 1.05rem;
    }

    .page-badge-kategori {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 22px;
        padding: 0.9rem 1.3rem;
        border-radius: 999px;
        background: rgba(247, 242, 232, 0.18);
        border: 1px solid rgba(247, 242, 232, 0.28);
        color: #f7f2e8;
        font-weight: 700;
    }

    .kategori-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.35s ease;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
        height: 100%;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .kategori-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 56px rgba(15, 23, 42, 0.12);
    }

    .kategori-header {
        padding: 34px 20px;
        text-align: center;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        color: #fff;
        min-height: 165px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .kategori-header::before,
    .kategori-header::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        opacity: 0.24;
    }

    .kategori-header::before {
        width: 140px;
        height: 140px;
        right: -30px;
        top: -35px;
        background: rgba(255, 255, 255, 0.16);
    }

    .kategori-header::after {
        width: 100px;
        height: 100px;
        left: 15px;
        bottom: -20px;
        background: rgba(255, 255, 255, 0.1);
    }

    .kategori-icon {
        font-size: 2.3rem;
        margin-bottom: 12px;
        position: relative;
        z-index: 1;
    }

    .kategori-title {
        font-size: 1.35rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .kategori-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .kategori-count {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 18px 0;
    }

    .count-badge {
        background: linear-gradient(140deg, #ff7a59, #ffc95c);
        color: #10172e;
        border-radius: 50%;
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.15rem;
    }

    .kategori-info {
        text-align: center;
        color: #5b6687;
        font-size: 0.95rem;
    }

    .btn-kategori {
        background: linear-gradient(130deg, #f7f2e8 0%, #ffe7c5 100%);
        color: #10172e;
        border: none;
        padding: 12px 18px;
        border-radius: 999px;
        transition: all 0.25s ease;
        width: 100%;
        margin-top: 15px;
        font-weight: 700;
    }

    .btn-kategori:hover {
        color: #10172e;
        transform: translateY(-2px);
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.08);
    }

    .search-box {
        margin-bottom: 28px;
        max-width: 460px;
    }

    .search-box input {
        border-radius: 14px;
        border: 1px solid rgba(19, 33, 64, 0.12);
        padding: 16px 20px;
        font-size: 1rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .search-box input:focus {
        border-color: #ff7a59;
        box-shadow: 0 0 0 4px rgba(255, 122, 89, 0.12);
        outline: none;
    }

    .empty-state {
        text-align: center;
        padding: 72px 20px;
    }

    .empty-icon {
        font-size: 3rem;
        color: #a3b5d5;
        margin-bottom: 20px;
    }

    .empty-text {
        color: #5b6687;
        font-size: 1.1rem;
    }

    .header-muted {
        color: rgba(247, 242, 232, 0.9);
    }

    .header-gradient-1 { background: linear-gradient(135deg, #1d4f78 0%, #ff7a59 100%); }
    .header-gradient-2 { background: linear-gradient(135deg, #1d4f78 0%, #ffc95c 100%); }
    .header-gradient-3 { background: linear-gradient(135deg, #303d7a 0%, #8de4d1 100%); }
    .header-gradient-4 { background: linear-gradient(135deg, #4d5ea9 0%, #ff9a56 100%); }
    .header-gradient-5 { background: linear-gradient(135deg, #178f78 0%, #ff7a59 100%); }
    .header-gradient-6 { background: linear-gradient(135deg, #283d66 0%, #8de4d1 100%); }
    .header-gradient-7 { background: linear-gradient(135deg, #133553 0%, #ff7a59 100%); }
    .header-gradient-8 { background: linear-gradient(135deg, #1d4f78 0%, #ff6a88 100%); }

    @media (max-width: 767px) {
        .page-hero-kategori {
            padding: 20px 18px;
            border-radius: 20px;
        }

        .header-title {
            font-size: 1.75rem;
        }

        .search-box {
            max-width: 100%;
        }

        .kategori-header {
            min-height: 140px;
            padding: 24px 16px;
        }

        .kategori-body {
            padding: 18px;
        }
    }

    @media (max-width: 480px) {
        .header-title {
            font-size: 1.45rem;
        }

        .page-badge-kategori {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid py-5">
    <div class="page-hero-kategori">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="text-uppercase small fw-bold mb-2" style="letter-spacing:0.15em; color: rgba(247, 242, 232, 0.8);">Kategori Buku</p>
                <h1 class="header-title">Temukan kategori bacaan favoritmu</h1>
                <p class="header-subtitle">Filter buku berdasarkan genre, tema, dan mood agar pengalaman mencari tetap cepat dan menyenangkan.</p>
            </div>
            <div class="page-badge-kategori">
                <i class="bi bi-book-half"></i>
                {{ $kategori->count() }} Kategori tersedia
            </div>
        </div>
    </div>

    @if($kategori->count() > 0)
        <!-- Search Box -->
        <div class="search-box">
            <input 
                type="text" 
                id="searchKategori" 
                class="form-control" 
                placeholder="🔍 Cari kategori..."
                style="width: 100%; max-width: 400px;"
            >
        </div>

        <!-- Kategori Grid -->
        <div class="row g-4 kategori-container" id="kategoriGrid">
            @foreach($kategori as $k)
            <div class="col-md-6 col-lg-4 kategori-item" data-kategori="{{ strtolower($k->name_category) }}">
                <a href="{{ route($portalPrefix . '.buku.index', ['kategori' => $k->id]) }}" class="text-decoration-none">
                    <div class="kategori-card">
                        <div class="kategori-header header-gradient-{{ ($loop->index % 8) + 1 }}">
                            <i class="bi {{ $loop->index % 8 == 0 ? 'bi-book-half' : ($loop->index % 8 == 1 ? 'bi-star' : ($loop->index % 8 == 2 ? 'bi-lightbulb' : ($loop->index % 8 == 3 ? 'bi-compass' : ($loop->index % 8 == 4 ? 'bi-pencil' : ($loop->index % 8 == 5 ? 'bi-palette' : ($loop->index % 8 == 6 ? 'bi-heart' : 'bi-rocket')))))) }} icon-map"></i>
                            <h3 class="kategori-title">{{ $k->name_category }}</h3>
                        </div>
                        <div class="kategori-body">
                            <div class="kategori-count">
                                <div class="count-badge">
                                    {{ $k->books->count() }}
                                </div>
                            </div>
                            <div class="kategori-info">
                                <small>Buku Tersedia</small>
                            </div>
                            <button class="btn-kategori">
                                <i class="bi bi-arrow-right"></i> Lihat Buku
                            </button>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="empty-state" style="display: none;">
            <div class="empty-icon">
                <i class="bi bi-search"></i>
            </div>
            <p class="empty-text">Kategori tidak ditemukan</p>
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <p class="empty-text">Belum ada kategori tersedia</p>
        </div>
    @endif
</div>

<script>
    // Search functionality
    const searchInput = document.getElementById('searchKategori');
    const kategoriItems = document.querySelectorAll('.kategori-item');
    const noResults = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            kategoriItems.forEach(item => {
                const kategoriName = item.dataset.kategori;
                if (kategoriName.includes(searchTerm)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        });
    }
</script>

@endsection
