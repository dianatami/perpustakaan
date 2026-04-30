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

<form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
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
        <div id="cover-dropzone" class="border rounded p-4 text-center bg-light" style="cursor:pointer; border-style:dashed !important;">
            <input type="file" name="cover" id="cover-input" class="d-none" accept="image/*">
            <div id="cover-dropzone-text">
                <div class="font-weight-bold">Seret foto ke sini atau klik untuk memilih file</div>
                <div class="text-muted small mt-1">Format: JPG, PNG, GIF. Maksimal 2 MB.</div>
            </div>
            <img id="cover-preview" src="" alt="Preview cover" class="img-fluid d-none mt-3" style="max-height:220px; object-fit:contain;">
            <div id="cover-filename" class="small text-muted mt-2 d-none"></div>
        </div>
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
    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
</form>

<script>
(function () {
    const dropzone = document.getElementById('cover-dropzone');
    const input = document.getElementById('cover-input');
    const preview = document.getElementById('cover-preview');
    const filename = document.getElementById('cover-filename');
    const hint = document.getElementById('cover-dropzone-text');

    if (!dropzone || !input || !preview || !filename || !hint) {
        return;
    }

    const showFile = (file) => {
        if (!file) {
            preview.classList.add('d-none');
            filename.classList.add('d-none');
            hint.classList.remove('d-none');
            preview.src = '';
            filename.textContent = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            preview.src = event.target.result;
            preview.classList.remove('d-none');
            filename.textContent = file.name;
            filename.classList.remove('d-none');
            hint.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    };

    dropzone.addEventListener('click', () => input.click());
    input.addEventListener('change', () => showFile(input.files[0]));

    ['dragenter', 'dragover'].forEach((eventName) => {
        dropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            event.stopPropagation();
            dropzone.classList.add('border-primary');
        });
    });

    ['dragleave', 'dragend', 'drop'].forEach((eventName) => {
        dropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            event.stopPropagation();
            dropzone.classList.remove('border-primary');
        });
    });

    dropzone.addEventListener('drop', (event) => {
        const files = event.dataTransfer.files;
        if (files && files.length) {
            input.files = files;
            showFile(files[0]);
        }
    });
})();
</script>

@endsection
