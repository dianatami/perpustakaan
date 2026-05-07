@extends('layout.admin')
@section('title','Data Peminjaman')
@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>📚 Data Peminjaman Buku</h2>
        </div>
    </div>

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Tambah Peminjaman -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Form Peminjaman Buku Baru</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.peminjaman.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">Nama Peminjam *</label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">-- Pilih Peminjam --</option>
                            @if (isset($users))
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="book_id" class="form-label">Judul Buku *</label>
                        <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                            <option value="">-- Pilih Buku --</option>
                            @if (isset($books))
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                        {{ $book->title }} (Stok: {{ $book->stock }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('book_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="borrow_date" class="form-label">Tanggal Pinjam *</label>
                        <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" 
                               id="borrow_date" name="borrow_date" value="{{ old('borrow_date', now()->format('Y-m-d')) }}" required>
                        @error('borrow_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="return_date" class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control @error('return_date') is-invalid @enderror" 
                               id="return_date" name="return_date" value="{{ old('return_date') }}">
                        @error('return_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Ajukan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Peminjaman -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
        @if ($peminjaman->count() > 0)
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $item)
                        <tr>
                            <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->user->nama ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $item->book->title ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/Y') }}</td>
                            <td>
                                @if ($item->return_date)
                                    {{ \Carbon\Carbon::parse($item->return_date)->format('d/m/Y') }}
                                @else
                                    <span class="badge bg-warning">Belum Dikembalikan</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->status === 'menunggu_acc')
                                    <span class="badge bg-warning text-dark">Menunggu ACC</span>
                                @elseif ($item->status === 'dipinjam')
                                    <span class="badge bg-info">Dipinjam</span>
                                @elseif ($item->status === 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif ($item->status === 'proses_kembali')
                                    <span class="badge bg-primary">Proses Pengembalian</span>
                                @elseif ($item->status === 'kembali')
                                    <span class="badge bg-success">Sudah Dikembalikan</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                            @if ($item->denda > 0)
                                Rp {{ number_format($item->denda, 0, ',', '.') }}
                            @else
                                -
                            @endif
                            </td>
                            <td>
                                @if ($item->status === 'menunggu_acc')
                                    <form action="{{ route('admin.peminjaman.approve', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui peminjaman ini?')">
                                            <i class="fas fa-check"></i> ACC
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.peminjaman.reject', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Tolak peminjaman ini?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                @elseif ($item->status === 'proses_kembali')
                                    <form action="{{ route('admin.peminjaman.confirm-return', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Konfirmasi pengembalian buku?')">
                                            <i class="fas fa-clipboard-check"></i> Konfirmasi
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.peminjaman.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.peminjaman.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    {{ $peminjaman->links() }}
                </ul>
            </nav>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Belum ada data peminjaman buku
            </div>
        @endif
            </div>
        </div>
    </div>
</div>
@endsection
