<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Edit Kategori</h1>
            <div>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-secondary me-2">Kembali ke Kategori</a>
                <a href="{{ route('admin.beranda') }}" class="btn btn-sm btn-outline-primary">Kembali ke Beranda</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="name_category" class="form-control" value="{{ old('name_category', $kategori->name_category) }}" required>
                    </div>
                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Tambah Buku ke: {{ $kategori->name_category }}</h5>
                        <form action="{{ route('admin.kategori.book.store', $kategori->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <input type="text" name="book_code" class="form-control" placeholder="Kode Buku" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="title" class="form-control" placeholder="Judul" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="author" class="form-control" placeholder="Pengarang" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="publisher" class="form-control" placeholder="Penerbit">
                            </div>
                            <div class="mb-2">
                                <input type="text" name="year" class="form-control" placeholder="Tahun">
                            </div>
                            <div class="mb-2">
                                <input type="text" name="cover" class="form-control" placeholder="URL cover (opsional)">
                            </div>
                            <div class="mb-2">
                                <textarea name="description" class="form-control" placeholder="Deskripsi" required></textarea>
                            </div>
                            <div class="mb-2">
                                <input type="number" min="0" name="stock" class="form-control" placeholder="Stok" required>
                            </div>
                            <button class="btn btn-success">Tambahkan Buku</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Daftar Buku</h5>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori->books as $b)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $b->book_code }}</td>
                                    <td>{{ $b->title }}</td>
                                    <td>{{ $b->author }}</td>
                                    <td>{{ $b->stock }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Belum ada buku di kategori ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
