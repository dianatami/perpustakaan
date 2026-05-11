@extends('layout.anggota')
@section('title', 'Edit Profil & Data Diri')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))

<style>
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 5px solid #667eea;
    }

    .section-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #667eea;
        font-size: 1.3rem;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 10px;
        border: 2px solid #ddd;
    }

    .alert-custom {
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }

    .alert-danger-custom {
        background: #fff5f5;
        border-left-color: #e53e3e;
        color: #742a2a;
    }

    .btn-group-custom {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    @media (max-width: 576px) {
        .btn-group-custom {
            flex-direction: column;
        }
        .btn-group-custom .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route($portalPrefix . '.profil.detail') }}" class="text-decoration-none">Profil</a></li>
            <li class="breadcrumb-item active">Edit Data</li>
        </ol>
    </nav>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger-custom alert-custom">
            <i class="bi bi-exclamation-circle"></i>
            <strong>Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Foto Profil Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="bi bi-image"></i> Foto Profil
        </h5>
        <form action="{{ route($portalPrefix . '.update.profil') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-4 text-center">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="photo-preview">
                    @else
                        <div class="photo-preview bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-circle" style="font-size: 3rem; color: #ddd;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Upload Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                               id="foto" name="foto" accept="image/*">
                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG, GIF. Max 2MB</small>
                    </div>
                    @if(Auth::user()->foto)
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusFoto()">
                            <i class="bi bi-trash"></i> Hapus Foto
                        </button>
                    @endif
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-upload"></i> Update Foto
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Informasi Dasar Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="bi bi-person"></i> Informasi Dasar
        </h5>
        <form action="{{ route($portalPrefix . '.update.profil') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" name="nama" value="{{ old('nama', Auth::user()->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" 
                            id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control @error('hp') is-invalid @enderror" 
                           id="hp" name="hp" value="{{ old('hp', Auth::user()->hp) }}" required>
                    @error('hp')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-check-circle"></i> Simpan Informasi Dasar
            </button>
        </form>
    </div>

    <!-- Data Pribadi Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="bi bi-calendar-event"></i> Data Pribadi
        </h5>
        <form action="{{ route($portalPrefix . '.update.infopribadi') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                           id="tempat_lahir" name="tempat_lahir" 
                           value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}" 
                           placeholder="Masukkan tempat lahir">
                    @error('tempat_lahir')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                           id="tanggal_lahir" name="tanggal_lahir" 
                           value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}">
                    @error('tanggal_lahir')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="3" 
                          placeholder="Masukkan alamat lengkap">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-check-circle"></i> Simpan Data Pribadi
            </button>
        </form>
    </div>

    <!-- Tombol Kembali -->
    <div class="btn-group-custom">
        <a href="{{ route($portalPrefix . '.profil.detail') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Profil
        </a>
    </div>

    @if(Auth::user()->foto)
        <form id="delete-foto-form" action="{{ route($portalPrefix . '.delete.foto') }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>

<script>
function hapusFoto() {
    if (confirm('Yakin ingin menghapus foto profil?')) {
        document.getElementById('delete-foto-form').submit();
    }
}
</script>

@endsection
