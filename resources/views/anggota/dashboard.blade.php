@extends('layout.anggota')
@section('title','Beranda')
@section('content')

<div class="container-fluid px-4 py-4">
    {{-- HERO SECTION --}}
    <div class="hero p-5 mb-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-white">
                <h2 class="fw-bold mb-2">Selamat Datang, {{ Auth::user()->nama }}! 👋</h2>
                <p class="mb-0 opacity-75">Senang melihat Anda kembali. Mari jelajahi koleksi buku terbaru kami hari ini.</p>
                <div class="mt-3 small opacity-50">Anggota sejak {{ Auth::user()->created_at->format('d F Y') }}</div>
            </div>
            <div class="reading">
                <span class="d-inline-flex align-items-center justify-content-center"
                 style="width:65px;height:65px;border-radius:50%;background: linear-gradient(135deg, #6f42c1, #4a148c);">
                  <i class="bi bi-book-fill text-white fs-2"></i>
                </span>
            </div>
        </div>
    </div>

    {{-- KARTU STATISTIK (Disederhanakan menjadi 2 kolom utama) --}}
    <div class="row g-4 mb-5">
        {{-- Kartu Buku Dipinjam --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 20px; background: #fff;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-box p-3 rounded-4 me-4" style="background: #eef2ff;">
                        <img src="https://cdn-icons-png.flaticon.com/512/3389/3389081.png" width="60">
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase tracking-wider">Sedang Dipinjam</div>
                        <h2 class="fw-bold mb-1">{{ $bookrents ?? 0 }}<span class="fs-5 fw-normal text-muted">Buku</span></h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Riwayat Pinjam --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 20px; background: #fff;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-box p-3 rounded-4 me-4" style="background: #f0fdf4;">
                        <img src="https://cdn-icons-png.flaticon.com/512/3208/3208743.png" width="60">
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase tracking-wider">Total Riwayat</div>
                        <h2 class="fw-bold mb-1">{{$riyawatPinjam ?? 0}} <span class="fs-5 fw-normal text-muted">Buku</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BAGIAN KIRI: REKOMENDASI BUKU --}}
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold mb-0">Rekomendasi Untuk Anda</h5>
            </div>

            <div class="overflow-auto pb-3" style="white-space:nowrap; -webkit-overflow-scrolling: touch;">
                @php
                    $dummyBooks = [
                        ['title' => 'Novel Misteri', 'author' => 'Author A', 'img' => 'https://picsum.photos/200/300?random=1'],
                        ['title' => 'Ilmu Pengetahuan', 'author' => 'Author B', 'img' => 'https://picsum.photos/200/300?random=2'],
                        ['title' => 'Sejarah Indonesia', 'author' => 'Author C', 'img' => 'https://picsum.photos/200/300?random=3'],
                        ['title' => 'Panduan Sehat', 'author' => 'Author D', 'img' => 'https://picsum.photos/200/300?random=4'],
                    ];
                @endphp

                @forelse($books ?? [] as $b)
                    <div class="card d-inline-block border-0 shadow-sm me-3 transition-hover" style="width:160px; vertical-align:top; border-radius: 15px;">
                        <img src="{{ asset('storage/' . $b->cover) }}" class="card-img-top" style="height:210px; object-fit:cover; border-radius: 15px;" alt="cover">
                        <div class="card-body p-2 mt-1">
                            <p class="fw-bold mb-0 text-truncate small mb-1">{{ $b->title }}</p>
                            <p class="text-muted extra-small mb-0">{{ $b->author }}</p>
                        </div>
                    </div>
                @empty
                    @foreach($dummyBooks as $db)
                        <div class="card d-inline-block border-0 shadow-sm me-3 transition-hover" style="width:160px; vertical-align:top; border-radius: 15px;">
                            <img src="{{ $db['img'] }}" class="card-img-top" style="height:210px; object-fit:cover; border-radius: 15px;">
                            <div class="card-body p-2 mt-1">
                                <p class="fw-bold mb-0 text-truncate small mb-1">{{ $db['title'] }}</p>
                                <p class="text-muted extra-small mb-0">{{ $db['author'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>

<style>
    .extra-small { font-size: 0.7rem; }
    .bg-soft-warning { background-color: #fff3cd; }
    .transition-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Smooth Scrollbar */
    ::-webkit-scrollbar { height: 5px; }
    ::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
</style>

@endsection