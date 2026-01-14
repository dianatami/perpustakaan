@extends('layout.anggota')
@section('title', 'Edit Informasi Pribadi')

@section('content')
<style>
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border-left: 5px solid #667eea;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #667eea;
        font-size: 1.5rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-label .required {
        color: #e74c3c;
    }

    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #999;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-secondary-custom {
        background: #f0f0f0;
        border: none;
        color: #333;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    .alert-custom {
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 25px;
        border-left: 5px solid;
    }

    .alert-success-custom {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(56, 142, 60, 0.1) 100%);
        border-left-color: #4caf50;
        color: #2e7d32;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
        border-left-color: #f44336;
        color: #c62828;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        pointer-events: none;
    }

    .input-icon .form-control {
        padding-left: 45px;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0 0 20px 0;
        margin-bottom: 30px;
    }

    .breadcrumb-custom .breadcrumb-item {
        color: #999;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #667eea;
        font-weight: 600;
    }

    .breadcrumb-custom .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }

    .breadcrumb-custom .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .button-group {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    @media (max-width: 576px) {
        .button-group {
            flex-direction: column;
        }

        .button-group button, .button-group a {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('anggota.profil.detail') }}">Profil</a></li>
            <li class="breadcrumb-item active">Edit Informasi Pribadi</li>
        </ol>
    </nav>

    <!-- Alert Messages -->
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

    @if(session('success'))
        <div class="alert alert-success-custom alert-custom">
            <i class="bi bi-check-circle"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('anggota.update.infopribadi') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Data Pribadi -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-calendar-event"></i> Data Pribadi
            </h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">
                            <i class="bi bi-geo-alt"></i> Tempat Lahir
                        </label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                               id="tempat_lahir" name="tempat_lahir" 
                               value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}" 
                               placeholder="Masukkan tempat lahir">
                        @error('tempat_lahir')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_lahir" class="form-label">
                            <i class="bi bi-calendar2"></i> Tanggal Lahir
                        </label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               id="tanggal_lahir" name="tanggal_lahir" 
                               value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}">
                        @error('tanggal_lahir')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">
                    <i class="bi bi-house"></i> Alamat Lengkap
                </label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="3" 
                          placeholder="Masukkan alamat lengkap">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Button Group -->
        <div class="button-group">
            <a href="{{ route('anggota.profil.detail') }}" class="btn btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
