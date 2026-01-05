@extends('layout.admin')
@section('title','Tambah Buku')
@section('content')

<h3>Tambah Buku</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Kategori</label>
        <select name="category_id" class="form-control">
            <option value="">Pilih Kategori</option>
            @isset($categories)
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_category }}</option>
                @endforeach
            @endisset
        </select>
    </div>

    <div class="form-group">
        <label>Kode Buku</label>
        <input type="text" name="book_code" class="form-control" value="{{ old('book_code') }}" required>
    </div>

    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
    </div>

    <div class="form-group">
        <label>Penerbit</label>
        <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}">
    </div>

    <div class="form-group">
        <label>Tahun</label>
        <input type="number" name="year" class="form-control" value="{{ old('year') }}">
    </div>

    <div class="form-group">
        <label>Cover (opsional)</label>
        <input type="file" name="cover" class="form-control-file">
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required min="0">
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
</form>

@endsection
