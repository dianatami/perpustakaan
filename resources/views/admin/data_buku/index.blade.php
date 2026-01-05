@extends('layout.admin')
@section('title','Data Buku')
@section('content')

    <h3>{{ $judul ?? 'Data Buku' }}</h3>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('books.create') }}" class="btn btn-primary">Tambah Buku</a>
        </div>
        <form action="{{ route('books.index') }}" method="GET" class="form-inline">
            <select name="category" class="form-control mr-2">
                <option value="">Semua Kategori</option>
                @isset($categories)
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name_category }}</option>
                    @endforeach
                @endisset
            </select>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control mr-2" placeholder="Cari judul / pengarang" />
            <button class="btn btn-secondary">Filter</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>ISBN</th>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books ?? [] as $book)
                    <tr>
                        <td>{{ isset($books) && method_exists($books, 'currentPage') ? ($loop->iteration + ($books->currentPage()-1)*$books->perPage()) : $loop->iteration }}</td>
                        <td>{{ $book->book_code ?? '-' }}</td>
                        <td>
                            @if($book->cover)
                                <img src="{{ asset('storage/'.$book->cover) }}" alt="cover" style="height:60px">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $book->title ?? '-' }}</td>
                        <td>
                            @if($book->category)
                                <a href="{{ route('books.index', array_merge(request()->query(), ['category' => $book->category->id])) }}">{{ $book->category->name_category }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $book->author ?? '-' }}</td>
                        <td>{{ $book->publisher ?? '-' }}</td>
                        <td>{{ $book->year ?? '-' }}</td>
                        <td>{{ $book->stock ?? 0 }}</td>
                        <td>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline-block; margin-left:6px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus buku ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($books) && method_exists($books, 'links'))
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    @endif

@endsection
