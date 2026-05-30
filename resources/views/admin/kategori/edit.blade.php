@extends('layout.admin')
@section('title','Data Kategori')
@section('content')
    <div class="container p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0 fw-bold">Edit Kategori</h1>
            <div>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-secondary me-2">Kembali ke Kategori</a>
                <a href="{{ route('admin.beranda') }}" class="btn btn-sm btn-outline-primary">Kembali ke Beranda</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        {{-- FORM UPDATE NAMA KATEGORI --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="name_category" class="form-control" value="{{ old('name_category', $kategori->name_category) }}" required>
                    </div>
                    <button class="btn btn-primary px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div class="row">
            {{-- FORM TAMBAH BUKU --}}
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4 fw-bold text-primary">Tambah Buku ke: {{ $kategori->name_category }}</h5>
                        
                        <form action="{{ route('admin.kategori.book.store', $kategori->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="book_code" class="form-control" placeholder="Kode Buku" required>
                            <input type="text" name="title" class="form-control" placeholder="Judul" required>
                            <input type="text" name="author" class="form-control" placeholder="Pengarang" required>
                            <input type="text" name="publisher" class="form-control" placeholder="Penerbit">
                            <input type="text" name="year" class="form-control" placeholder="Tahun">

                            <select name="rack_id" class="form-select">
                                <option value="">Pilih Rak</option>
                                @foreach($racks ?? [] as $rack)
                                    <option value="{{ $rack->id }}">{{ $rack->code }} - {{ $rack->name }}</option>
                                @endforeach
                            </select>
                            
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Upload Cover Buku (File Gambar)</label>
                                <input type="file" name="cover" class="form-control" accept="image/*">
                            </div>

                            <textarea name="description" class="form-control" placeholder="Deskripsi" rows="3" required></textarea>
                            <input type="number" min="0" name="stock" class="form-control" placeholder="Stok" required>
                            
                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-success fw-bold py-2">Simpan Buku</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DAFTAR BUKU --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4 fw-bold">Daftar Buku</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Cover</th>
                                        <th>Kode</th>
                                        <th>Judul</th>
                                        <th>Rak</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kategori->books as $b)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($b->cover)
                                                <img src="{{ asset('storage/' . $b->cover) }}" width="40" class="rounded shadow-sm">
                                            @else
                                                <span class="text-muted small">No Cover</span>
                                            @endif
                                        </td>
                                        <td class="small">{{ $b->book_code }}</td>
                                        <td class="fw-semibold">{{ $b->title }}</td>
                                        <td class="small text-muted">{{ $b->rack->code ?? '-' }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $b->stock }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada buku di kategori ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection