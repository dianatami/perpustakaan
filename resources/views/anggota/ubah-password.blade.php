@extends('layout.anggota')
@section('title', 'Ubah Password')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))

<style>
    .password-card {
        max-width: 720px;
        margin: 0 auto;
        border-radius: 18px;
        border: 1px solid rgba(14, 81, 57, 0.16);
        background: #fff;
        box-shadow: 0 16px 26px rgba(17, 57, 43, 0.08);
        overflow: hidden;
    }

    .password-header {
        padding: 22px 24px;
        color: #fff;
        background: linear-gradient(140deg, #1f7a46 0%, #145d34 70%, #f3a530 160%);
    }

    .password-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .password-subtitle {
        margin: 6px 0 0;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .password-body {
        padding: 22px;
    }

    .password-body label {
        font-weight: 700;
        color: #1e3b2a;
        margin-bottom: 6px;
    }

    .password-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 12px;
    }

    @media (max-width: 576px) {
        .password-actions {
            flex-direction: column;
        }
    }
</style>

<div class="password-card">
    <div class="password-header">
        <h2 class="password-title">Perbarui Password Akun</h2>
        <p class="password-subtitle">Gunakan kombinasi password yang kuat agar akun tetap aman.</p>
    </div>

    <div class="password-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route($portalPrefix . '.store.password') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="password_lama" class="form-label">Password Lama</label>
                <input type="password" class="form-control" id="password_lama" name="password_lama" required>
            </div>

            <div class="mb-3">
                <label for="password_baru" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password_baru" name="password_baru" required>
            </div>

            <div class="mb-3">
                <label for="password_baru_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_baru_confirmation" name="password_baru_confirmation" required>
            </div>

            <div class="password-actions">
                <a href="{{ route($portalPrefix . '.profil.detail') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-success">Simpan Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
