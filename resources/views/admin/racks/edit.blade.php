@extends('layout.admin')
@section('title','Edit Rak Buku')
@section('content')
<style>
    .rack-edit-shell {
        max-width: 920px;
        margin: 0 auto;
    }

    .rack-edit-card {
        border-radius: 20px;
        border: 1px solid rgba(16, 42, 50, 0.12);
        box-shadow: 0 18px 40px rgba(16, 42, 50, 0.12);
        background: #ffffff;
    }

    .rack-edit-header {
        padding: 22px 26px 10px;
    }

    .rack-edit-title {
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .rack-edit-subtitle {
        color: #6b8088;
        margin-bottom: 0;
    }

    .rack-edit-body {
        padding: 0 26px 26px;
    }

    .rack-edit-body .form-control,
    .rack-edit-body textarea {
        border-radius: 12px;
        border: 1px solid rgba(16, 42, 50, 0.12);
        padding: 10px 14px;
    }
</style>

<div class="rack-edit-shell">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="rack-edit-title">Edit Rak</h3>
            <p class="rack-edit-subtitle">Perbarui detail lokasi dan kode rak buku.</p>
        </div>
        <a href="{{ route('admin.racks.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="rack-edit-card">
        <div class="rack-edit-header d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 44px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));">
            <div>
                <h5 class="rack-edit-title mb-0">Informasi Rak</h5>
                <p class="rack-edit-subtitle mb-0">Pastikan kode rak tetap konsisten.</p>
            </div>
        </div>
        <div class="rack-edit-body">
            <form action="{{ route('admin.racks.update', $rack->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kode Rak</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $rack->code) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Rak</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $rack->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $rack->location) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kapasitas</label>
                        <input type="number" min="0" name="capacity" class="form-control" value="{{ old('capacity', $rack->capacity) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $rack->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="rackActive" name="is_active" value="1" {{ old('is_active', $rack->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="rackActive">Rak aktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.racks.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
