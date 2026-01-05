@extends('layout.anggota')
@section('title', 'Profil Saya')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profil Saya</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="profile-image mb-3">
                                @if($anggota->foto)
                                    <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama }}" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                                        <i class="fas fa-user" style="font-size: 60px; color: #ccc;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h4>{{ $anggota->nama }}</h4>
                            <p class="text-muted">
                                <span class="badge bg-info">Anggota</span>
                                @if($anggota->status == 1)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </p>
                            <table class="table table-sm">
                                <tr>
                                    <td style="width: 30%"><strong>Email</strong></td>
                                    <td>{{ $anggota->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP</strong></td>
                                    <td>{{ $anggota->hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Terdaftar</strong></td>
                                    <td>{{ $anggota->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="mt-4">
                        <a href="{{ route('anggota.edit.profil') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                        <a href="{{ route('anggota.ubah.password') }}" class="btn btn-warning">
                            <i class="fas fa-key"></i> Ubah Password
                        </a>
                        <a href="{{ route('anggota.beranda') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
