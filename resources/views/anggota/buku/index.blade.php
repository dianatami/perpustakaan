@extends('layout.anggota')
@section('title','Daftar Buku')
@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Buku</h3>

    <div class="row">
        @forelse ($books as $book)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    @if ($book->cover)
                        <img src="{{ asset('storage/'.$book->cover) }}"
                             class="card-img-top"
                             style="height: 350px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-secondary text-white"
                             style="height: 220px;">
                            Tidak ada gambar
                        </div>
                    @endif

                    <div class="card-body">
                        <h6 class="card-title fw-bold">
                            {{ $book->title }}
                        </h6>

                        <small class="text-muted">
                            {{ $book->author }}
                        </small>

                        <p class="mt-2 mb-1">
                            <span class="badge bg-info">
                                {{ $book->category->name_category ?? 'Tanpa Kategori' }}
                            </span>
                        </p>

                        <p class="mb-2">
                            Stok:
                            <strong>{{ $book->stock }}</strong>
                        </p>

                        <a href="{{ route('anggota.buku.show', $book->id) }}"
                           class="btn btn-sm btn-primary w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Tidak ada buku tersedia
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection