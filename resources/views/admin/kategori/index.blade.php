@extends('layout.admin')
@section('title','Data Kategori')
@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="mb-1 fw-bold">Kategori</h2>
                    <p class="text-muted mb-0">Kelola inventaris, anggota, dan transaksi perpustakaan.</p>
                </div>
                <div class="text-muted small">Total {{ $kategori->total() }} kategori</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="card-title mb-1">Tambah Kategori Baru</h5>
                                <p class="text-muted mb-0">Masukkan nama kategori untuk menambah data baru.</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">Baru</span>
                        </div>

                        <form action="{{ route('admin.kategori.store') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name_category" value="{{ old('name_category') }}" class="form-control rounded-start" placeholder="Masukkan nama kategori" required>
                                <button class="btn btn-success rounded-end" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="card-title mb-1">Cari Kategori</h5>
                                <p class="text-muted mb-0">Telusuri nama kategori untuk mempercepat pencarian.</p>
                            </div>
                            <i class="bi bi-search fs-4 text-secondary"></i>
                        </div>

                        <form action="{{ route('admin.kategori.index') }}" method="GET">
                            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
                            <div class="input-group">
                                <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control rounded-start" placeholder="Cari kategori...">
                                <button class="btn btn-outline-secondary rounded-end" type="submit">Cari</button>
                            </div>
                        </form>
                        @if(!empty($search))
                            <div class="mt-3">
                                <a href="{{ route('admin.kategori.index', ['per_page' => $perPage ?? 10]) }}" class="btn btn-sm btn-outline-secondary">Kembali ke semua</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center border-bottom px-4 py-3 bg-white">
                    <div>
                        <h5 class="mb-0">Daftar Kategori</h5>
                        <small class="text-muted">Kelola kategori buku yang tersedia.</small>
                    </div>
                    <div class="text-muted small">Menampilkan {{ $kategori->count() }} dari {{ $kategori->total() }} data</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">No.</th>
                                <th>Nama Kategori</th>
                                <th>Dibuat Pada</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $k)
                                <tr class="border-bottom">
                                    <td class="ps-4">{{ $loop->iteration + ($kategori->firstItem() ? $kategori->firstItem() - 1 : 0) }}</td>
                                    <td>
                                        <a href="{{ route('admin.kategori.edit', $k->id) }}" class="text-dark text-decoration-none fw-semibold">{{ $k->name_category }}</a>
                                    </td>
                                    <td class="text-muted">{{ $k->created_at->format('d M Y, H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.kategori.edit', $k->id) }}" class="btn btn-sm btn-outline-success me-2">Edit</a>
                                        <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-0 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Tampilkan</span>
                    <form action="{{ route('admin.kategori.index') }}" method="GET" class="d-inline">
                        <input type="hidden" name="search" value="{{ $search ?? '' }}">
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                    <span class="text-muted">data per halaman</span>
                </div>
                <div>
                    {{ $kategori->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
