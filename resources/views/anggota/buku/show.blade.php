@extends('layout.anggota')

@section('title', 'Detail Buku - ' . $book->title)

@section('content')
@php($portalPrefix = request()->routeIs('guru.*') ? 'guru' : 'anggota')
<div class="container py-4">
    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mb-4">
        <a href="{{ route($portalPrefix . '.buku.index') }}" class="btn btn-outline-secondary rounded-pill px-4 font-weight-bold text-center">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Buku
        </a>
        <a href="{{ route($portalPrefix . '.peminjaman', ['book_id' => $book->id]) }}" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-sm text-center">
            <i class="bi bi-card-checklist me-1"></i> Ke Form Peminjaman
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
        <div class="row g-0">
            <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-4">
                @if ($book->cover)
                    <img src="{{ asset('storage/'.$book->cover) }}"
                         class="img-fluid rounded-3 shadow-sm"
                         style="max-height: 420px; object-fit: contain;"
                         alt="Cover {{ $book->title }}">
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted p-5 bg-white rounded-3 border w-100" style="min-height: 320px;">
                        <i class="bi bi-journal-text" style="font-size: 3.5rem;"></i>
                        <span class="mt-2 fw-bold">Tidak Ada Sampul</span>
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            <i class="bi bi-tag-fill me-1"></i> {{ $book->category->name_category ?? 'Tanpa Kategori' }}
                        </span>
                        @if($book->rack)
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                <i class="bi bi-archive-fill me-1"></i> Rak {{ $book->rack->code }} - {{ $book->rack->name }}
                            </span>
                        @endif
                    </div>

                    <h2 class="card-title fw-bold text-dark mb-3">
                        {{ $book->title }}
                    </h2>

                    <div class="row g-3 mb-4 text-secondary">
                        <div class="col-sm-6">
                            <strong><i class="bi bi-person-fill me-1"></i> Penulis:</strong> {{ $book->author }}
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="bi bi-building-fill me-1"></i> Penerbit:</strong> {{ $book->publisher ?? '-' }}
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="bi bi-calendar-event-fill me-1"></i> Tahun Terbit:</strong> {{ $book->year ?? '-' }}
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="bi bi-box-seam-fill me-1"></i> Stok Tersedia:</strong> 
                            <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                {{ $book->stock }} Eksemplar
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-2"><i class="bi bi-text-paragraph me-1"></i> Deskripsi Buku</h5>
                    <p style="text-align: justify; color: #475569; line-height: 1.7;" class="mb-4">
                        {{ $book->description ?? 'Tidak ada deskripsi detail untuk buku ini.' }}
                    </p>

                    <!-- SEKSI AJUKAN PEMINJAMAN LANGSUNG -->
                    <div class="p-4 bg-light rounded-4 border">
                        @if($book->stock > 0)
                            <form action="{{ route($portalPrefix . '.pinjam.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="books[0][book_id]" value="{{ $book->id }}">
                                <input type="hidden" name="books[0][qty]" value="1">

                                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-success"><i class="bi bi-check-circle-fill me-1"></i> Siap Dipinjam</h6>
                                        <small class="text-muted">Klik tombol di bawah untuk langsung mengajukan peminjaman buku ini.</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success font-weight-bold rounded-pill px-4 shadow-sm">
                                            <i class="bi bi-journal-plus me-1"></i> Pinjam Buku Ini
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-danger fw-bold">
                                    <i class="bi bi-x-circle-fill me-1"></i> Stok buku saat ini sedang kosong/habis.
                                </div>
                                <button class="btn btn-secondary rounded-pill px-4" disabled>Stok Habis</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection