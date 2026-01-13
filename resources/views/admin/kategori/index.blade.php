@extends('layout.admin')
@section('title','Data Kategori')
@section('content')
    <div class="container p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Kategori</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input type="text" name="name_category" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ route('admin.kategori.edit', $k->id) }}">{{ $k->name_category }}</a></td>
                            <td>
                                <a href="{{ route('admin.kategori.edit', $k->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
