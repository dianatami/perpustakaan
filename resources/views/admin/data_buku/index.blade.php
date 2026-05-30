@extends('layout.admin')
@section('title','Data Buku')
@section('content')
<style>
    :root {
        --book-ink: #102a32;
        --book-muted: #6b8088;
        --book-surface: #ffffff;
        --book-soft: #f4faf9;
        --book-accent: #ff8a3d;
        --book-primary: #0f8c80;
        --book-border: rgba(16, 42, 50, 0.12);
        --book-shadow: 0 18px 38px rgba(16, 42, 50, 0.12);
    }

    .book-hero {
        background: linear-gradient(120deg, rgba(15, 140, 128, 0.95), rgba(29, 79, 120, 0.92), rgba(255, 138, 61, 0.88));
        color: #f7f5f0;
        padding: 26px 30px;
        border-radius: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--book-shadow);
        margin-bottom: 24px;
    }

    .book-hero h1 {
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 2rem;
    }

    .book-hero p {
        margin: 0;
        color: rgba(247, 245, 240, 0.9);
    }

    .book-toolbar {
        background: var(--book-surface);
        border-radius: 18px;
        border: 1px solid var(--book-border);
        box-shadow: 0 12px 26px rgba(16, 42, 50, 0.08);
        padding: 16px 18px;
        margin-bottom: 20px;
    }

    .book-toolbar .form-control,
    .book-toolbar .form-select {
        border-radius: 12px;
        border: 1px solid var(--book-border);
        padding: 10px 14px;
    }

    .book-table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #54636a;
        background: #f1f6f6;
    }

    .book-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(15, 140, 128, 0.12);
        color: #0f8c80;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .book-chip.alt {
        background: rgba(255, 138, 61, 0.14);
        color: #d65b3a;
    }

    .book-cover-thumb {
        width: 52px;
        height: 68px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 8px 16px rgba(15, 23, 42, 0.12);
    }

    .book-actions .btn {
        border-radius: 10px;
        font-weight: 700;
    }

    .book-muted {
        color: var(--book-muted);
    }
</style>

<div class="book-hero">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div>
            <h1>{{ $judul ?? 'Data Buku' }}</h1>
            <p>Kelola koleksi buku, kategorinya, dan rak penempatannya agar lebih rapi.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn btn-light fw-bold">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="book-toolbar">
    <form action="{{ route('admin.books.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @isset($categories)
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name_category }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Rak</label>
            <select name="rack" class="form-select">
                <option value="">Semua Rak</option>
                @isset($racks)
                    @foreach($racks as $rack)
                        <option value="{{ $rack->id }}" {{ request('rack') == $rack->id ? 'selected' : '' }}>{{ $rack->code }} - {{ $rack->name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Pencarian</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul, pengarang, kode buku">
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-secondary">Terapkan Filter</button>
        </div>
    </form>
</div>

<div class="table-responsive">
    <table class="table book-table align-middle mb-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Cover</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Rak</th>
                <th>Pengarang</th>
                <th>Stok</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books ?? [] as $book)
                <tr>
                    <td>{{ isset($books) && method_exists($books, 'currentPage') ? ($loop->iteration + ($books->currentPage()-1)*$books->perPage()) : $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $book->book_code ?? '-' }}</td>
                    <td>
                        @if($book->cover)
                            <img src="{{ asset('storage/'.$book->cover) }}" alt="cover" class="book-cover-thumb">
                        @else
                            <span class="book-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $book->title ?? '-' }}</div>
                        <div class="book-muted">{{ $book->publisher ?? '-' }}{{ $book->year ? ' | ' . $book->year : '' }}</div>
                    </td>
                    <td>
                        @if($book->category)
                            <a href="{{ route('admin.books.index', array_merge(request()->query(), ['category' => $book->category->id])) }}" class="book-chip">
                                {{ $book->category->name_category }}
                            </a>
                        @else
                            <span class="book-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($book->rack)
                            <a href="{{ route('admin.books.index', array_merge(request()->query(), ['rack' => $book->rack->id])) }}" class="book-chip alt">
                                {{ $book->rack->code }}
                            </a>
                        @else
                            <span class="book-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $book->author ?? '-' }}</td>
                    <td class="fw-semibold">{{ $book->stock ?? 0 }}</td>
                    <td class="text-end book-actions">
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus buku ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Tidak ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($books) && method_exists($books, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $books->links() }}
    </div>
@endif

@endsection
