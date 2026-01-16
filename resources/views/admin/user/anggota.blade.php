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
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if ($anggota->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Status</th>
                                <th>Tanggal Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($anggota as $item)
                                <tr>
                                    <td>{{ ($anggota->currentPage() - 1) * $anggota->perPage() + $loop->iteration }}</td>
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
                                            <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
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
                                    <td colspan="7" class="text-center">Data anggota tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $anggota->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada data anggota. <a href="{{ route('admin.anggota.create') }}">Tambah anggota sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection