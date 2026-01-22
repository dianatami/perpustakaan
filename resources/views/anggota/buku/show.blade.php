@extends('layout.anggota')

@section('content')
<div class="container mt-4">
    <a href="{{ route('anggota.buku.index') }}" class="btn btn-secondary mb-3">
        ← Kembali
    </a>

    <div class="card shadow">
        <div class="row g-0">
            <div class="col-md-4">
                @if ($book->cover)
                    <img src="{{ asset('storage/'.$book->cover) }}"
                         class="img-fluid rounded-start"
                         style="height: 100%; object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-secondary text-white"
                         style="height: 100%;">
                        Tidak ada gambar
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title fw-bold">
                        {{ $book->title }}
                    </h3>

                    <p class="mb-1">
                        <strong>Penulis:</strong> {{ $book->author }}
                    </p>

                    <p class="mb-1">
                        <strong>Penerbit:</strong> {{ $book->publisher }}
                    </p>

                    <p class="mb-1">
                        <strong>Tahun:</strong> {{ $book->year }}
                    </p>

                    <p class="mb-1">
                        <strong>Kategori:</strong>
                        <span class="badge bg-info">
                            {{ $book->category->name_category ?? 'Tanpa Kategori' }}
                        </span>
                    </p>

                    <p class="mb-2">
                        <strong>Stok:</strong> {{ $book->stock }}
                    </p>

                    <hr>

                    <h5>Deskripsi</h5>
                    <p style="text-align: justify;">
                        {{ $book->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection