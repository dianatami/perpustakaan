@extends('layout.anggota')
@section('title', 'Edit Profil')

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

    .foto-preview {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 20px;
    }

    .foto-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #667eea;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
    }
</style>

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('anggota.profil.detail') }}">Profil</a></li>
            <li class="breadcrumb-item active">Edit Profil</li>
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

    <form action="{{ route('anggota.update.profil') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Data Profil -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-person-badge"></i> Data Profil
            </h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama" class="form-label">
                            <i class="bi bi-person"></i> Nama Lengkap
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" 
                               value="{{ old('nama', Auth::user()->nama) }}" 
                               placeholder="Masukkan nama lengkap" required>
                        @error('nama')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email
                            <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               value="{{ old('email', Auth::user()->email) }}" 
                               placeholder="Masukkan email" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="hp" class="form-label">
                    <i class="bi bi-telephone"></i> Nomor HP
                    <span class="required">*</span>
                </label>
                <input type="tel" class="form-control @error('hp') is-invalid @enderror" 
                       id="hp" name="hp" 
                       value="{{ old('hp', Auth::user()->hp) }}" 
                       placeholder="Masukkan nomor HP" required>
                @error('hp')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jenis_kelamin" class="form-label">
                    <i class="bi bi-person-fill"></i> Jenis Kelamin
                    <span class="required">*</span>
                </label>
                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                        id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Foto Profil -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-image"></i> Foto Profil
            </h4>

            <div class="text-center">
                <div class="foto-preview">
                    <img id="fotoPreview" 
                         src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama) . '&background=667eea&color=fff&size=180' }}" 
                         alt="Foto Profil">
                </div>
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">
                    <i class="bi bi-upload"></i> Pilih Foto Baru
                </label>
                <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                       id="foto" name="foto" accept="image/*">
                <small class="text-muted d-block mt-2">
                    Format: JPG, PNG, GIF (Max: 2MB)
                </small>
                @error('foto')
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

<script>
    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('fotoPreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Format nomor HP
    document.getElementById('hp').addEventListener('keyup', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 13) {
            this.value = value.substring(0, 13);
        } else {
            this.value = value;
        }
    });

    // Auto close success alert setelah 5 detik
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
