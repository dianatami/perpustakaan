@extends('layout.admin')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Manajemen Anggota</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Anggota
            </a>
        </div>
        <div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.anggota.index') }}">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama, email, NISN atau NIP..."
                    value="{{ request('search') }}"
                >

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Tabel Siswa -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Siswa ({{ $siswa->total() }} terdaftar)</h5>
        </div>
        <div class="card-body">
            @if ($siswa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>No.Induk</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Status</th>
                                <th>Tanggal Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswa as $item)
                                <tr>
                                    <td>{{ ($siswa->currentPage() - 1) * $siswa->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->nisn ?? '-' }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->hp }}</td>
                                    <td>
                                        <span class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.anggota.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data siswa terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($siswa->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $siswa->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    Belum ada data siswa terdaftar. <a href="{{ route('admin.anggota.create') }}">Tambah siswa sekarang</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Guru -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Data Guru ({{ $guru->total() }} terdaftar)</h5>
        </div>
        <div class="card-body">
            @if ($guru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>No. Induk</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>NIP</th>
                                <th>Nomor HP</th>
                                <th>Status</th>
                                <th>Tanggal Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guru as $item)
                                <tr>
                                    <td>{{ ($guru->currentPage() - 1) * $guru->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->nip ?? '-' }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->nip ?? '-' }}</td>
                                    <td>{{ $item->hp }}</td>
                                    <td>
                                        <span class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.anggota.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada data guru terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($guru->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $guru->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    Belum ada data guru terdaftar.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection