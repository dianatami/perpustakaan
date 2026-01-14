@extends('layout.anggota')
@section('title', 'Profil Saya')

@section('content')
<style>
    * {
        transition: all 0.3s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        position: relative;
        overflow: hidden;
        border-radius: 25px;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(20px); }
    }

    .profile-content {
        position: relative;
        z-index: 1;
    }

    .profile-photo {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        position: relative;
        animation: photoFloat 3s ease-in-out infinite;
    }

    @keyframes photoFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(10px); }
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid white;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .profile-info {
        color: white;
    }

    .profile-name {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-member-since {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 25px;
    }

    .info-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 0.85rem;
        opacity: 0.85;
        display: block;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .edit-btn {
        background: white;
        color: #667eea;
        border: none;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .edit-btn:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="container-fluid py-5">
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
    <div class="row mb-5">
        <div class="col-12">
            <div class="profile-header p-5">
                <div class="profile-content">
                    <div class="row align-items-center">
                        <!-- Foto Profil -->
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="profile-photo">
                                <img src="{{ ($user && $user->foto) ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama ?? 'User') . '&background=667eea&color=fff&size=200' }}" 
                                     alt="Foto Profil">
                            </div>
                            <a href="{{ route('anggota.edit.profil') }}" class="btn edit-btn btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit Profil
                            </a>
                        </div>

                        <!-- Info Profil -->
                        <div class="col-md-8 profile-info">
                            <h2 class="profile-name">{{ $user->nama ?? 'User' }}</h2>
                            <p class="profile-member-since">
                                <i class="bi bi-calendar-event"></i> Member sejak {{ $user->created_at->format('d F Y') ?? '-' }}
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
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4" style="overflow: hidden; background: white;">
                <div class="card-header border-0 pt-4 pb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="card-title fw-bold mb-0 text-white">
                        <i class="bi bi-person-vcard"></i> Informasi Pribadi
                    </h5>
                </div>
                <div class="card-body pb-4">
                    <div class="mb-4">
                        <div class="text-muted small mb-2">
                            <i class="bi bi-geo-alt"></i> Tempat Lahir
                        </div>
                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                            {{ $user->tempat_lahir ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar2-event"></i> Tanggal Lahir
                        </div>
                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                            @if($user->tanggal_lahir)
                                {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}
                                <span class="badge bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); ms: 8px;">
                                    {{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} tahun
                                </span>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted small mb-2">
                            <i class="bi bi-house"></i> Alamat Lengkap
                        </div>
                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                            {{ $user->alamat ?? '-' }}
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <a href="{{ route('anggota.edit.infopribadi') }}" class="btn btn-sm w-100 rounded-3" 
                           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; font-weight: 600;">
                            <i class="bi bi-pencil"></i> Ubah Informasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Peminjaman -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden;">
                <div class="card-header border-0 pt-4 pb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="card-title fw-bold mb-0 text-white">
                        <i class="bi bi-book"></i> Riwayat Peminjaman
                    </h5>
                </div>
                <div class="card-body pb-4">
                    <!-- Statistik -->
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="p-4 rounded-4" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 5px solid #667eea;">
                                <div class="text-muted small fw-bold mb-2">Total Peminjaman</div>
                                <div class="fw-bold" style="color: #667eea; font-size: 2rem;">
                                    {{ $bookrents->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-4 rounded-4" style="background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 193, 7, 0.1) 100%); border-left: 5px solid #ff9800;">
                                <div class="text-muted small fw-bold mb-2">Sedang Dipinjam</div>
                                <div class="fw-bold" style="color: #ff9800; font-size: 2rem;">
                                    {{ $bookrents->where('status', 'borrowed')->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-4 rounded-4" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(56, 142, 60, 0.1) 100%); border-left: 5px solid #4caf50;">
                                <div class="text-muted small fw-bold mb-2">Selesai Dipinjam</div>
                                <div class="fw-bold" style="color: #4caf50; font-size: 2rem;">
                                    {{ $bookrents->where('status', 'returned')->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Buku -->
                    <div class="table-responsive">
                        @if($bookrents && $bookrents->count() > 0)
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="fw-bold text-dark">
                                            <i class="bi bi-book"></i> Judul Buku
                                        </th>
                                        <th scope="col" class="fw-bold text-dark">
                                            <i class="bi bi-calendar"></i> Pinjam
                                        </th>
                                        <th scope="col" class="fw-bold text-dark">
                                            <i class="bi bi-calendar-check"></i> Kembali
                                        </th>
                                        <th scope="col" class="fw-bold text-dark">
                                            <i class="bi bi-tag"></i> Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookrents->sortByDesc('created_at')->take(5) as $rent)
                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                            <td class="fw-bold text-dark py-3">
                                                <i class="bi bi-book-half" style="color: #667eea;"></i>
                                                {{ $rent->book->judul ?? '-' }}
                                            </td>
                                            <td class="text-muted py-3">
                                                {{ $rent->borrow_date ? \Carbon\Carbon::parse($rent->borrow_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="text-muted py-3">
                                                {{ $rent->return_date ? \Carbon\Carbon::parse($rent->return_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="py-3">
                                                @if($rent->status == 'borrowed')
                                                    <span class="badge" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); padding: 8px 12px; border-radius: 50px;">
                                                        <i class="bi bi-hourglass-split"></i> Sedang Dipinjam
                                                    </span>
                                                @elseif($rent->status == 'returned')
                                                    <span class="badge" style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); padding: 8px 12px; border-radius: 50px;">
                                                        <i class="bi bi-check-circle"></i> Dikembalikan
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary" style="padding: 8px 12px; border-radius: 50px;">{{ $rent->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if($bookrents->count() > 5)
                                <div class="text-center pt-3">
                                    <a href="#" class="btn btn-sm rounded-3" 
                                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; font-weight: 600;">
                                        Lihat Semua Peminjaman
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info mb-0 rounded-3" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border: 1px solid rgba(102, 126, 234, 0.3);">
                                <i class="bi bi-info-circle"></i> <strong>Belum ada data peminjaman.</strong> 
                                <a href="#" class="alert-link" style="color: #667eea;">Mulai pinjam buku sekarang</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Sedang Dipinjam -->
    @if($bookrents && $bookrents->where('status', 'borrowed')->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden;">
                <div class="card-header border-0 pt-4 pb-3" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                    <h5 class="card-title fw-bold mb-0 text-white">
                        <i class="bi bi-bookmark-check"></i> Buku yang Sedang Dipinjam
                    </h5>
                </div>
                <div class="card-body pb-4">
                    <div class="row g-3">
                        @foreach($bookrents->where('status', 'borrowed') as $rent)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden" 
                                 style="background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%); cursor: pointer;">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="flex: 1;">
                                            <h6 class="card-title fw-bold mb-1">{{ $rent->book->judul ?? '-' }}</h6>
                                            <p class="card-text text-muted small mb-0">
                                                <i class="bi bi-pen"></i> {{ $rent->book->penulis ?? '-' }}
                                            </p>
                                        </div>
                                        <span class="badge" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); padding: 8px 10px; border-radius: 50px;">
                                            <i class="bi bi-exclamation-circle"></i>
                                        </span>
                                    </div>

                                    <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(245, 124, 0, 0.1) 100%); border-left: 4px solid #ff9800;">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-calendar-check"></i> Dipinjam pada
                                        </small>
                                        <small class="fw-bold d-block" style="color: #ff9800;">
                                            {{ $rent->borrow_date ? \Carbon\Carbon::parse($rent->borrow_date)->format('d F Y') : '-' }}
                                        </small>
                                    </div>

                                    @if($rent->return_date)
                                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 4px solid #667eea;">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-calendar-x"></i> Kembali sebelum
                                        </small>
                                        <small class="fw-bold d-block" style="color: #667eea;">
                                            {{ \Carbon\Carbon::parse($rent->return_date)->format('d F Y') }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    * {
        transition: all 0.3s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        position: relative;
        overflow: hidden;
        border-radius: 25px;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(20px); }
    }

    .profile-content {
        position: relative;
        z-index: 1;
    }

    .profile-photo {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        position: relative;
        animation: photoFloat 3s ease-in-out infinite;
    }

    @keyframes photoFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(10px); }
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid white;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .profile-info {
        color: white;
    }

    .profile-name {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-member-since {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 25px;
    }

    .info-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 0.85rem;
        opacity: 0.85;
        display: block;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .edit-btn {
        background: white;
        color: #667eea;
        border: none;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .edit-btn:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .card {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    .rounded-4 {
        border-radius: 1rem;
    }

    .rounded-3 {
        border-radius: 0.75rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
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

    @media (max-width: 768px) {
        .col-md-3 {
            margin-bottom: 1rem;
        }

        .profile-name {
            font-size: 1.8rem;
        }
    }
</style>

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
