@extends('layout.anggota')
@section('title', 'Edit Profil Murid')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))

<style>
    .form-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 32px;
        margin-bottom: 30px;
        border-left: 6px solid #ff7a59;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #10172e;
    }

    .section-title i {
        color: #ff7a59;
    }

    .form-label {
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        display: block;
    }

    .form-control,
    .form-select {
        border-radius: 16px;
        border: 1px solid rgba(15, 23, 42, 0.12);
        padding: 14px 16px;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff7a59;
        box-shadow: 0 0 0 4px rgba(255, 122, 89, 0.12);
        outline: none;
    }

    .photo-preview {
        width: 140px;
        height: 140px;
        border-radius: 24px;
        object-fit: cover;
        margin-bottom: 18px;
        border: 3px solid rgba(255, 122, 89, 0.2);
    }

    .photo-placeholder {
        width: 140px;
        height: 140px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fff4e4 0%, #ffe8d3 100%);
        color: #374151;
        margin-bottom: 18px;
        border: 3px solid rgba(255, 122, 89, 0.2);
    }

    .btn-save {
        background: linear-gradient(135deg, #ff7a59 0%, #ffc95c 100%);
        border: none;
        color: #10172e;
        border-radius: 999px;
        padding: 14px 30px;
        font-weight: 700;
        transition: transform 0.25s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.12);
    }

    .btn-cancel {
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.08);
        color: #334155;
        border-radius: 999px;
        padding: 14px 30px;
        font-weight: 700;
    }

    .alert-custom {
        border-radius: 18px;
        padding: 18px 22px;
        margin-bottom: 30px;
        border-left: 6px solid #ff7a59;
        background: rgba(255, 245, 238, 0.9);
    }

    .alert-danger-custom {
        border-left-color: #ef4444;
        background: rgba(254, 226, 226, 0.9);
        color: #7f1d1d;
    }

    .alert-success-custom {
        border-left-color: #16a34a;
        background: rgba(209, 250, 229, 0.9);
        color: #065f46;
    }

    .button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 28px;
    }

    @media (max-width: 576px) {
        .button-row {
            justify-content: stretch;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="form-card">
        <h2 class="section-title"><i class="bi bi-pencil-square"></i> Edit Profil Murid</h2>

        @if($errors->any())
            <div class="alert alert-danger-custom alert-custom">
                <i class="bi bi-exclamation-circle"></i>
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0 mt-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success-custom alert-custom">
                <i class="bi bi-check-circle"></i>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route($portalPrefix . '.update.profil') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4 text-center">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="photo-preview">
                    @else
                        <div class="photo-placeholder">
                            <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <div class="mb-3 text-start">
                        <label for="foto" class="form-label">Upload Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">JPG, PNG, GIF. Max 2MB</small>
                    </div>

                    @if(Auth::user()->foto)
                        <button type="button" class="btn-cancel w-100 mb-2" onclick="hapusFoto()">
                            <i class="bi bi-trash"></i> Hapus Foto
                        </button>
                    @endif
                </div>

                <div class="col-lg-8">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', Auth::user()->nama) }}" required>
                            @error('nama')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="hp" class="form-label">Nomor HP</label>
                            <input type="tel" id="hp" name="hp" class="form-control @error('hp') is-invalid @enderror" value="{{ old('hp', Auth::user()->hp) }}" required>
                            @error('hp')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}">
                            @error('tempat_lahir')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}">
                            @error('tanggal_lahir')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <input type="text" id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat', Auth::user()->alamat) }}" placeholder="Alamat lengkap">
                            @error('alamat')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="button-row">
                <a href="{{ route($portalPrefix . '.profil.detail') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
            </div>
        </form>
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
