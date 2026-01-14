@extends('layout.anggota')
@section('title', 'Kategori Buku')
@section('content')

<style>
    .kategori-container {
        margin-top: 20px;
    }
    
    .kategori-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }
    
    .kategori-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.25);
    }
    
    .kategori-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .kategori-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .kategori-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    
    .kategori-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        display: block;
        position: relative;
        z-index: 1;
    }
    
    .kategori-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
        position: relative;
        z-index: 1;
    }
    
    .kategori-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .kategori-count {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 15px 0;
        gap: 10px;
    }
    
    .count-badge {
        background: #667eea;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .kategori-info {
        text-align: center;
        color: #666;
        font-size: 0.95rem;
    }
    
    .btn-kategori {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        transition: all 0.3s;
        width: 100%;
        margin-top: 15px;
        font-weight: 500;
    }
    
    .btn-kategori:hover {
        color: white;
        transform: scale(1.02);
    }
    
    .header-section {
        margin-bottom: 30px;
    }
    
    .header-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .header-subtitle {
        color: #666;
        font-size: 1.05rem;
    }
    
    .search-box {
        margin-bottom: 30px;
    }
    
    .search-box input {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 20px;
    }
    
    .empty-text {
        color: #999;
        font-size: 1.1rem;
    }
    
    .icon-map {
        font-size: 2rem;
    }

    /* Gradient variations untuk kategori */
    .header-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .header-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .header-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .header-gradient-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .header-gradient-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .header-gradient-6 { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
    .header-gradient-7 { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
    .header-gradient-8 { background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%); }
</style>

<div class="container-fluid py-5">
    <!-- Header Section -->
    <div class="header-section mb-5">
        <h1 class="header-title">
            <i class="bi bi-tag"></i> Kategori Buku
        </h1>
        <p class="header-subtitle">Jelajahi berbagai kategori buku yang tersedia di perpustakaan kami</p>
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
                <a href="#" class="text-decoration-none">
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
