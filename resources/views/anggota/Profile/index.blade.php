@extends('layout.anggota')
@section('title', request()->routeIs('anggota.*') ? 'Profil Murid' : 'Profil Saya')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))
<style>
    * {
        transition: all 0.25s ease;
    }

    .profile-header {
        background: linear-gradient(120deg, rgba(16, 23, 46, 0.94) 0%, rgba(29, 79, 120, 0.9) 42%, rgba(255, 122, 89, 0.88) 100%);
        position: relative;
        overflow: hidden;
        border-radius: 25px;
        padding: 3rem 2rem;
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.08);
    }

    .profile-header::before,
    .profile-header::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        opacity: 0.24;
        filter: blur(15px);
    }

    .profile-header::before {
        top: -30%;
        right: -15%;
        width: 260px;
        height: 260px;
        background: rgba(255, 201, 92, 0.24);
    }

    .profile-header::after {
        bottom: -30%;
        left: -10%;
        width: 220px;
        height: 220px;
        background: rgba(23, 143, 120, 0.3);
    }

    .profile-content {
        position: relative;
        z-index: 1;
    }

    .profile-photo {
        width: 190px;
        height: 190px;
        margin: 0 auto;
        position: relative;
        animation: photoFloat 4s ease-in-out infinite;
    }

    @keyframes photoFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.18);
    }

    .profile-info {
        color: white;
    }

    .profile-name {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 0 12px 30px rgba(15, 23, 42, 0.2);
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 999px;
        padding: 0.55rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.9rem;
    }

    .profile-motto {
        font-size: 1rem;
        opacity: 0.95;
        line-height: 1.8;
        max-width: 580px;
        margin-bottom: 1.4rem;
    }

    .profile-member-since {
        font-size: 0.95rem;
        opacity: 0.85;
        margin-bottom: 25px;
    }

    .info-box {
        background: rgba(247, 242, 232, 0.14);
        border: 1px solid rgba(247, 242, 232, 0.32);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        padding: 18px 22px;
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 0.8rem;
        opacity: 0.85;
        display: block;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 1.05rem;
        font-weight: 700;
    }

    .edit-btn {
        background: #f7f2e8;
        color: #0f172a;
        border: none;
        border-radius: 999px;
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s ease;
        margin-top: 20px;
        letter-spacing: 0.02em;
    }

    .edit-btn:hover {
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.16);
    }

    .card {
        box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
        border: none;
        transition: all 0.25s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.1);
    }

    .badge {
        font-size: 0.83rem;
        padding: 0.55rem 0.9rem;
    }

    .rounded-4 {
        border-radius: 1.2rem;
    }

    .rounded-3 {
        border-radius: 0.9rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f4f8ff;
    }

    .alert-success {
        animation: slideInDown 0.45s ease-out;
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

    @media (max-width: 768px) {
        .profile-name {
            font-size: 1.7rem;
            text-align: center;
        }

        .profile-photo {
            width: 130px;
            height: 130px;
        }

        .profile-header {
            padding: 1.8rem 1rem !important;
            border-radius: 20px;
        }

        .profile-info {
            text-align: center;
        }

        .profile-motto,
        .profile-member-since {
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
    }

    @media (max-width: 480px) {
        .profile-name {
            font-size: 1.45rem;
        }

        .edit-action-btns {
            flex-direction: column;
            width: 100%;
        }

        .edit-action-btns .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Success Notification -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" 
         style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); border: none; color: white; border-radius: 10px; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
        <i class="bi bi-check-circle"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header Profile Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="profile-header p-4 p-md-5">
                <div class="profile-content">
                    <div class="row align-items-center">
                        <!-- Foto Profil -->
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="profile-photo">
                                <img src="{{ ($user && $user->foto) ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama ?? 'User') . '&background=667eea&color=fff&size=200' }}" 
                                     alt="Foto Profil">
                            </div>
                            <div class="d-flex justify-content-center gap-2 mt-3 edit-action-btns">
                                <a href="{{ route($portalPrefix . '.edit.profil') }}" class="btn edit-btn btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit Profil
                                </a>
                                <a href="{{ route($portalPrefix . '.ubah.password') }}" class="btn edit-btn btn-sm" style="background: rgba(255, 255, 255, 0.2); color: #fff; border: 1px solid rgba(255, 255, 255, 0.4);">
                                    <i class="bi bi-key-fill"></i> Ubah Password
                                </a>
                            </div>
                        </div>
                        <!-- Info Profil -->
                        <div class="col-md-8 profile-info">
                            <h2 class="profile-name">{{ $user->nama ?? 'User' }}</h2>
                            <div class="profile-role-badge">
                                <i class="bi bi-star-fill"></i>
                                {{ request()->routeIs('anggota.*') ? 'Murid Aktif' : 'Anggota' }}
                            </div>
                            <p class="profile-motto">
                                Selamat datang di ruang profil siswa. Temukan buku inspiratif, cek riwayat pinjaman, dan jaga semangat belajarmu setiap hari.
                            </p>
                            <p class="profile-member-since">
                                <i class="bi bi-calendar-event"></i> Member sejak {{ optional($user->created_at)->format('d F Y') ?? '-' }}
                            </p>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-label"><i class="bi bi-envelope"></i> Email</span>
                                        <div class="info-value">{{ $user->email ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-label"><i class="bi bi-telephone"></i> Nomor HP</span>
                                        <div class="info-value">{{ $user->hp ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-label"><i class="bi bi-person"></i> Jenis Kelamin</span>
                                        <div class="info-value">
                                            @if($user->jenis_kelamin == 'Laki-laki' || $user->jenis_kelamin == 'M')
                                                <i class="bi bi-gender-ambiguous"></i> Laki-laki
                                            @elseif($user->jenis_kelamin == 'Perempuan' || $user->jenis_kelamin == 'F')
                                                <i class="bi bi-gender-ambiguous"></i> Perempuan
                                            @else
                                                {{ $user->jenis_kelamin ?? '-' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
<div class="row">

    <!-- Detail Informasi Pribadi -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden; background: white;">

            <div class="card-header border-0 pt-4 pb-3"
                style="background: linear-gradient(130deg, #1d4f78, #ff7a59);">

                <h5 class="card-title fw-bold mb-0 text-white">
                    <i class="bi bi-person-vcard"></i> Informasi Murid
                </h5>
            </div>

            <div class="card-body pb-4">

                <!-- Tempat Lahir -->
                <div class="mb-4">
                    <div class="text-muted small mb-2">
                        <i class="bi bi-geo-alt"></i> Tempat Lahir
                    </div>

                    <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                        {{ $user->tempat_lahir ?? '-' }}
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div class="mb-4">
                    <div class="text-muted small mb-2">
                        <i class="bi bi-calendar2-event"></i> Tanggal Lahir
                    </div>

                    <div class="fw-bold text-dark" style="font-size: 1.05rem;">

                        @if($user->tanggal_lahir)

                            {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}

                            <span class="badge bg-gradient"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-left: 8px;">

                                {{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} tahun
                            </span>

                        @else
                            -
                        @endif

                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <div class="text-muted small mb-2">
                        <i class="bi bi-house"></i> Alamat Lengkap
                    </div>

                    <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                        {{ $user->alamat ?? '-' }}
                    </div>
                </div>

                <!-- Button -->
                <div class="pt-3 border-top">
                    <a href="{{ route($portalPrefix . '.edit.profil') }}"
                        class="btn btn-sm w-100 rounded-3"
                        style="background: linear-gradient(135deg, #3b82f6 0%, #38bdf8 100%);
                               color: white;
                               border: none;
                               font-weight: 600;">

                        <i class="bi bi-pencil"></i> Sunting Data Murid
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
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
