@extends('layout.admin')
@section('title','Edit Buku')
@section('content')
<style>
    .book-form-shell {
        max-width: 960px;
        margin: 0 auto;
    }

    .book-form-card {
        border-radius: 20px;
        border: 1px solid rgba(16, 42, 50, 0.12);
        box-shadow: 0 16px 32px rgba(16, 42, 50, 0.12);
        background: #ffffff;
    }

    .book-form-card .form-control,
    .book-form-card .form-select,
    .book-form-card textarea {
        border-radius: 12px;
        border: 1px solid rgba(16, 42, 50, 0.12);
        padding: 10px 14px;
    }
</style>

<div class="book-form-shell">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 fw-bold">Edit Buku</h3>
            <div class="text-muted">Perbarui detail buku dan rak penyimpanannya.</div>
        </div>
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="book-form-card p-4">

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
        <label class="fw-semibold">Kategori</label>
        <select name="category_id" class="form-select">
            <option value="">Pilih Kategori</option>
            @isset($categories)
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $book->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->name_category }}</option>
                @endforeach
            @endisset
        </select>
    </div>

    <div class="form-group">
        <label class="fw-semibold">Rak Buku</label>
        <select name="rack_id" class="form-select">
            <option value="">Pilih Rak</option>
            @isset($racks)
                @foreach($racks as $rack)
                    <option value="{{ $rack->id }}" {{ (old('rack_id', $book->rack_id) == $rack->id) ? 'selected' : '' }}>{{ $rack->code }} - {{ $rack->name }}</option>
                @endforeach
            @endisset
        </select>
    </div>

    <div class="form-group">
        <label class="fw-semibold">Kode Buku</label>
        <input type="text" name="book_code" class="form-control" value="{{ old('book_code', $book->book_code) }}" required>
    </div>

    <div class="form-group">
        <label class="fw-semibold">Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
    </div>

    <div class="form-group">
        <label class="fw-semibold">Pengarang</label>
        <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}" required>
    </div>

    <div class="form-group">
        <label class="fw-semibold">Penerbit</label>
        <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher) }}">
    </div>

    <div class="form-group">
        <label class="fw-semibold">Tahun</label>
        <input type="number" name="year" class="form-control" value="{{ old('year', $book->year) }}">
    </div>

    <div class="form-group">
        <label class="fw-semibold">Cover (ganti jika perlu)</label>
        <div id="cover-dropzone" class="border rounded p-4 text-center bg-light" style="cursor:pointer; border-style:dashed !important;">
            <input type="file" name="cover" id="cover-input" class="d-none" accept="image/*">
            <div id="cover-dropzone-text">
                <div class="font-weight-bold">Seret foto baru ke sini atau klik untuk mengganti cover</div>
                <div class="text-muted small mt-1">Format: JPG, PNG, GIF. Maksimal 2 MB.</div>
            </div>
            <img id="cover-preview" src="{{ $book->cover ? asset('storage/'.$book->cover) : '' }}" alt="Preview cover" class="img-fluid {{ $book->cover ? '' : 'd-none' }} mt-3" style="max-height:220px; object-fit:contain;">
            <div id="cover-filename" class="small text-muted mt-2 d-none"></div>
        </div>
        @if($book->cover)
            <div class="small text-muted mt-2">Cover saat ini akan tetap digunakan jika tidak ada file baru yang dipilih.</div>
        @endif
    </div>

    <div class="form-group">
        <label class="fw-semibold">Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
    </div>

    <div class="form-group">
        <label class="fw-semibold">Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock) }}" required min="0">
    </div>

    <div class="form-group">
        <label class="fw-semibold">Stok Rusak</label>
        <input type="number" name="damaged" class="form-control" value="{{ old('damaged', $book->damaged) }}" required min="0">
    </div>

    <div class="form-group">
        <label class="fw-semibold">Stok Hilang</label>
        <input type="number" name="lost" class="form-control" value="{{ old('lost', $book->lost) }}" required min="0">
    </div>

    <button class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
</form>

    </div>
</div>

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
            if (!preview.getAttribute('src')) {
                preview.classList.add('d-none');
            }
            filename.classList.add('d-none');
            hint.classList.remove('d-none');
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
