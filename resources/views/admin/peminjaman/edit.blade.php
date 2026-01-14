@extends('layout.admin')
@section('title','Data Peminjaman')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Peminjaman Buku</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi Kesalahan!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Nama Peminjam *</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Pilih Peminjam --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($peminjaman->user_id == $user->id)>
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="book_id" class="form-label">Judul Buku *</label>
                            <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}" @selected($peminjaman->book_id == $book->id)>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="borrow_date" class="form-label">Tanggal Pinjam *</label>
                            <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" 
                                   id="borrow_date" name="borrow_date" value="{{ $peminjaman->borrow_date }}" required>
                            @error('borrow_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="return_date" class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control @error('return_date') is-invalid @enderror" 
                                   id="return_date" name="return_date" value="{{ $peminjaman->return_date }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="dipinjam" @selected($peminjaman->status == 'dipinjam')>Dipinjam</option>
                                <option value="dikembalikan" @selected($peminjaman->status == 'dikembalikan')>Dikembalikan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                        <label>Kondisi Buku</label>
                        <select name="condition" class="form-control" required>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                             <option value="hilang">Hilang</option>
                        </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
