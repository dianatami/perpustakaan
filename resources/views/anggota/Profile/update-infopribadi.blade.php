@extends('layout.anggota')
@section('title', 'Edit Informasi Pribadi')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))
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

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #e74c3c !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
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

    .button-group {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 30px;
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
        transition: all 0.3s ease;
    }

    .breadcrumb-custom .breadcrumb-item a:hover {
        color: #764ba2;
    }

    .alert-success {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 576px) {
        .button-group {
            flex-direction: column;
        }

        .button-group button, .button-group a {
            width: 100%;
        }

        .form-section {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route($portalPrefix . '.profil.detail') }}">Profil</a></li>
            <li class="breadcrumb-item active">Edit Informasi Pribadi</li>
        </ol>
    </nav>

    <!-- Success Notification -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert" 
         style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); border-left: 5px solid #4caf50; color: white; border-radius: 10px; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
        <i class="bi bi-check-circle"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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

    <form action="{{ route($portalPrefix . '.update.infopribadi') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Data Pribadi -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-info-circle"></i> Data Pribadi
            </h4>

            <div class="form-group">
                <label for="tempat_lahir" class="form-label">
                    <i class="bi bi-geo-alt"></i> Tempat Lahir
                    <span class="required">*</span>
                </label>
                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                       id="tempat_lahir" name="tempat_lahir" 
                       value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}" 
                       placeholder="Masukkan tempat lahir" required>
                @error('tempat_lahir')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_lahir" class="form-label">
                    <i class="bi bi-calendar2-event"></i> Tanggal Lahir
                    <span class="required">*</span>
                </label>
                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                       id="tanggal_lahir" name="tanggal_lahir" 
                       value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}" required>
                @error('tanggal_lahir')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">
                    <i class="bi bi-house"></i> Alamat Lengkap
                    <span class="required">*</span>
                </label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="4"
                          placeholder="Masukkan alamat lengkap" required>{{ old('alamat', Auth::user()->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Button Group -->
        <div class="button-group">
            <a href="{{ route($portalPrefix . '.profil.detail') }}" class="btn btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    // Auto-close success alert setelah 5 detik
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(function() {
                const alert = new bootstrap.Alert(successAlert);
                alert.close();
            }, 5000);
        }
    });
</script>
@endsection
