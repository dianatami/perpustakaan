@extends('layout.anggota')
@section('title','Beranda')
@section('content')

<div class="container-fluid px-4 py-4">
    {{-- HERO SECTION --}}
    <div class="hero p-4 mb-4 rounded-4 shadow-sm" style="background: linear-gradient(90deg, #f8faff 0%, #ffffff 100%); border: 1px solid #eef2ff;">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-1" style="color: #2d3748;">Selamat Datang, {{ Auth::user()->nama }}! 👋</h2>
                <p class="mb-0 text-muted small">Anggota Perpustakaan sejak {{ Auth::user()->created_at->format('d F Y') }}</p>
            </div>
            <div class="d-none d-md-block">
                <img src="https://i.ibb.co/9VhZ0Qw/reading-illustration.png" alt="reading" style="height:100px; object-fit:contain;">
            </div>
        </div>
    </div>

    {{-- KARTU STATISTIK (4 Kolom Sesuai Gambar Referensi) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/3389/3389081.png" width="50" class="mb-2">
                    <div class="text-muted small fw-bold">Buku Dipinjam</div>
                    <h4 class="fw-bold mb-2">3 <span class="fs-6 fw-normal">Buku</span></h4>
                    <a href="#" class="btn btn-sm btn-primary w-100 rounded-pill py-1">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1077/1077035.png" width="50" class="mb-2">
                    <div class="text-muted small fw-bold">Buku Favorit</div>
                    <h4 class="fw-bold mb-2">12 <span class="fs-6 fw-normal">Buku</span></h4>
                    <a href="#" class="btn btn-sm btn-danger w-100 rounded-pill py-1">Lihat Favorit</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/3208/3208743.png" width="50" class="mb-2">
                    <div class="text-muted small fw-bold">Riwayat Pinjam</div>
                    <h4 class="fw-bold mb-2">45 <span class="fs-6 fw-normal">Buku</span></h4>
                    <a href="#" class="btn btn-sm btn-success w-100 rounded-pill py-1">Lihat Riwayat</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1827/1827347.png" width="50" class="mb-2">
                    <div class="text-muted small fw-bold">Pemberitahuan</div>
                    <h4 class="fw-bold mb-2">2 <span class="fs-6 fw-normal">Pesan</span></h4>
                    <a href="#" class="btn btn-sm btn-warning text-white w-100 rounded-pill py-1">Lihat Info</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BAGIAN KIRI: BUKU POPULER --}}
        <div class="col-md-7">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0">Rekomendasi Buku</h5>
                <a href="{{ Route::has('anggota.buku') ? route('anggota.buku') : '#' }}" class="text-decoration-none small fw-bold text-primary">Lihat Semua <i class="bi bi-chevron-right"></i></a>
            </div>

            <div class="overflow-auto pb-3" style="white-space:nowrap; -webkit-overflow-scrolling: touch;">
                @php
                    // Data dummy jika variabel $buku belum dikirim dari controller
                    $dummyBooks = [
                        ['title' => 'Novel Misteri', 'author' => 'Author A', 'img' => 'https://picsum.photos/200/300?random=1'],
                        ['title' => 'Ilmu Pengetahuan', 'author' => 'Author B', 'img' => 'https://picsum.photos/200/300?random=2'],
                        ['title' => 'Sejarah Indonesia', 'author' => 'Author C', 'img' => 'https://picsum.photos/200/300?random=3'],
                        ['title' => 'Panduan Sehat', 'author' => 'Author D', 'img' => 'https://picsum.photos/200/300?random=4'],
                    ];
                @endphp

                @forelse($buku ?? [] as $b)
                    <div class="card d-inline-block border-0 shadow-sm me-3" style="width:145px; vertical-align:top; border-radius: 12px;">
                        <img src="{{ asset('storage/' . $b->cover) }}" class="card-img-top" style="height:190px; object-fit:cover; border-radius: 12px 12px 0 0;" alt="cover">
                        <div class="card-body p-2 text-center">
                            <p class="fw-bold mb-0 text-truncate small" style="color: #2d3748;">{{ $b->title }}</p>
                            <p class="text-muted extra-small mb-0">{{ $b->author }}</p>
                        </div>
                    </div>
                @empty
                    @foreach($dummyBooks as $db)
                        <div class="card d-inline-block border-0 shadow-sm me-3" style="width:145px; vertical-align:top; border-radius: 12px;">
                            <img src="{{ $db['img'] }}" class="card-img-top" style="height:190px; object-fit:cover; border-radius: 12px 12px 0 0;">
                            <div class="card-body p-2 text-center">
                                <p class="fw-bold mb-0 text-truncate small">{{ $db['title'] }}</p>
                                <p class="text-muted extra-small mb-0">{{ $db['author'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>

        {{-- BAGIAN KANAN: JADWAL KEMBALI BUKU --}}
        <div class="col-md-5">
            <h5 class="fw-bold mb-3">Jadwal Kembali Buku</h5>
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr class="border-bottom">
                                <td class="py-3">
                                    <div class="fw-bold small">Seni Fotografi</div>
                                    <div class="text-muted extra-small">Kategori: Non-Fiksi</div>
                                </td>
                                <td class="text-end py-3">
                                    <div class="extra-small text-muted">Tenggat: <span class="fw-bold text-dark">25 Jan 2026</span></div>
                                    <i class="bi bi-chevron-right text-primary"></i>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="py-3">
                                    <div class="fw-bold small">Fiksi Petualangan</div>
                                    <div class="text-muted extra-small">Kategori: Fiksi</div>
                                </td>
                                <td class="text-end py-3">
                                    <div class="extra-small text-muted">Tenggat: <span class="fw-bold text-dark">28 Jan 2026</span></div>
                                    <i class="bi bi-chevron-right text-primary"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-4">Lihat Semua Pinjaman</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
    /* Memperhalus scrollbar horizontal */
    ::-webkit-scrollbar { height: 6px; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #cbd5e0; }
</style>

@endsection