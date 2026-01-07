@extends('layout.admin')
@section('title','Edit Buku')
@section('content')

<h3>Edit Buku</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Kategori</label>
        <select name="category_id" class="form-control">
            <option value="">Pilih Kategori</option>
            @isset($categories)
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $book->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->name_category }}</option>
                @endforeach
            @endisset
        </select>
    </div>

    <div class="form-group">
        <label>Kode Buku</label>
        <input type="text" name="book_code" class="form-control" value="{{ old('book_code', $book->book_code) }}" required>
    </div>

    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}" required>
    </div>

    <div class="form-group">
        <label>Penerbit</label>
        <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher) }}">
    </div>

    <div class="form-group">
        <label>Tahun</label>
        <input type="number" name="year" class="form-control" value="{{ old('year', $book->year) }}">
    </div>

    <div class="form-group">
        <label>Cover (ganti jika perlu)</label>
        <input type="file" name="cover" class="form-control-file">
        @if($book->cover)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$book->cover) }}" alt="cover" style="max-height:120px">
            </div>
        @endif
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
    </div>

    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock) }}" required min="0">
    </div>

    <button class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
</form>

@endsection
