@extends('layout.anggota')
@section('title', 'Profil Saya')

@section('content')
@php($portalPrefix = $portalPrefix ?? (request()->routeIs('guru.*') ? 'guru' : 'anggota'))
<style>
    * {
        transition: all 0.3s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        padding: 3rem;
    }

    .profile-content {
        position: relative;
        z-index: 1;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        position: relative;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .profile-info {
        color: white;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .profile-member-since {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .info-box {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        padding: 12px 18px;
        margin-bottom: 10px;
    }

    .info-label {
        font-size: 0.8rem;
        opacity: 0.8;
        display: block;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
    }

    .edit-btn {
        background: white;
        color: #667eea;
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 12px;
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
                            <a href="{{ route($portalPrefix . '.edit.profil') }}" class="btn edit-btn btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit Profil
                            </a>
                        </div>
                        <!-- Info Profil -->
                        <div class="col-md-8 profile-info">
                            <h2 class="profile-name">{{ $user->nama ?? 'User' }}</h2>
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
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-4" style="overflow: hidden; background: white;">

            <div class="card-header border-0 pt-4 pb-3"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

                <h5 class="card-title fw-bold mb-0 text-white">
                    <i class="bi bi-person-vcard"></i> Informasi Pribadi
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
                    <a href="{{ route($portalPrefix . '.edit.infopribadi') }}"
                        class="btn btn-sm w-100 rounded-3"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                               color: white;
                               border: none;
                               font-weight: 600;">

                        <i class="bi bi-pencil"></i> Ubah Informasi
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Statistik & Peminjaman -->
    <div class="col-lg-8 mb-4">

        <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden;">

            <div class="card-header border-0 pt-4 pb-3"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

                <h5 class="card-title fw-bold mb-0 text-white">
                    <i class="bi bi-book"></i> Riwayat Peminjaman
                </h5>
            </div>

            <div class="card-body pb-4">

                <!-- FORM PINJAM BUKU -->
                <div class="mb-5">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-journal-plus"></i> Pinjam Buku
                        </h5>

                        <span class="badge bg-primary">
                            {{ $availableBooks->count() ?? 0 }} Buku
                        </span>
                    </div>

                    @if(isset($availableBooks) && $availableBooks->count() > 0)

                        <form action="{{ route($portalPrefix . '.pinjam.store') }}"
    method="POST">

    @csrf

    <div id="book-wrapper">

        <div class="row g-2 mb-2 book-item">

            <div class="col-md-7">

                <select name="books[0][book_id]"
                    class="form-select"
                    required>

                    <option value="">
                        -- Pilih Buku --
                    </option>

                    @foreach($availableBooks as $b)

                    <option value="{{ $b->id }}"
                        {{ $b->stock <= 0 ? 'disabled' : '' }}>

                        {{ $b->title }}
                        (stok: {{ $b->stock }})

                        {{ $b->stock <= 0 ? '- Stok Habis' : '' }}

                    </option>

                @endforeach
                </select>

            </div>

            <div class="col-md-3">

                <input type="number"
                    name="books[0][qty]"
                    class="form-control"
                    min="1"
                    value="1"
                    placeholder="Qty"
                    required>

            </div>

            <div class="col-md-2 d-grid">

                <button type="button"
                    class="btn btn-danger remove-book">

                    <i class="bi bi-trash"></i>

                </button>

            </div>

        </div>

    </div>

    <div class="d-flex gap-2 mt-3">

        <button type="button"
            class="btn btn-outline-primary"
            id="add-book">

            <i class="bi bi-plus-circle"></i>
            Tambah Buku

        </button>

        <button type="submit"
            class="btn btn-primary fw-bold">

            <i class="bi bi-send"></i>
            Ajukan

        </button>

    </div>

</form>
                        <div class="text-muted small mt-2">
                            Pengajuan peminjaman akan diproses admin.
                        </div>

                    @else

                        <div class="alert alert-warning mb-0">
                            Tidak ada buku tersedia untuk dipinjam saat ini.
                        </div>

                    @endif

                </div>

                @php(
                    $activeBorrowings =
                    $activeBorrowings ??
                    ($bookrents ?? collect())->where('status', 'dipinjam')
                )

                <!-- Statistik -->
                <div class="row g-3 mb-4">

                    <div class="col-4">
                        <div class="p-4 rounded-4"
                            style="background: linear-gradient(135deg,
                                   rgba(102, 126, 234, 0.1) 0%,
                                   rgba(118, 75, 162, 0.1) 100%);
                                   border-left: 5px solid #667eea;">

                            <div class="text-muted small fw-bold mb-2">
                                Total Peminjaman
                            </div>

                            <div class="fw-bold"
                                style="color: #667eea; font-size: 2rem;">

                                {{ $bookrents->count() ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-4 rounded-4"
                            style="background: linear-gradient(135deg,
                                   rgba(255, 152, 0, 0.1) 0%,
                                   rgba(255, 193, 7, 0.1) 100%);
                                   border-left: 5px solid #ff9800;">

                            <div class="text-muted small fw-bold mb-2">
                                Sedang Dipinjam
                            </div>

                            <div class="fw-bold"
                                style="color: #f79a0e; font-size: 2rem;">

                                {{ $bookrents->where('status', 'dipinjam')->count() ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-4 rounded-4"
                            style="background: linear-gradient(135deg,
                                   rgba(76, 175, 80, 0.1) 0%,
                                   rgba(56, 142, 60, 0.1) 100%);
                                   border-left: 5px solid #4caf50;">

                            <div class="text-muted small fw-bold mb-2">
                                Selesai Dipinjam
                            </div>

                            <div class="fw-bold"
                                style="color: #4caf50; font-size: 2rem;">

                                {{ $bookrents->where('status', 'kembali')->count() ?? 0 }}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tabel Riwayat -->
                <div class="table-responsive">

                    @if($bookrents && $bookrents->count() > 0)

                        <table class="table table-hover">

                            <thead class="table-light">
                                <tr>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($bookrents->sortByDesc('created_at')->take(5) as $rent)

                                    <tr>

                                            <td>
                                            @foreach($rent->details as $detail)

                                                <div class="mb-1">

                                                    <span class="fw-bold">
                                                        {{ $detail->book->title }}
                                                    </span>

                                                    <span class="badge bg-secondary">
                                                        Qty: {{ $detail->qty }}
                                                    </span>

                                                </div>

                                            @endforeach
                                        </td>

                                        <td>
                                            {{ $rent->borrow_date
                                                ? \Carbon\Carbon::parse($rent->borrow_date)->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            {{ $rent->return_date
                                                ? \Carbon\Carbon::parse($rent->return_date)->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>

                                            @if($rent->status == 'menunggu_acc')

                                                <span class="badge bg-warning text-dark">
                                                    Menunggu ACC
                                                </span>

                                            @elseif($rent->status == 'dipinjam')

                                                <span class="badge bg-primary">
                                                    Dipinjam
                                                </span>

                                            @elseif($rent->status == 'kembali')

                                                <span class="badge bg-success">
                                                    Dikembalikan
                                                </span>

                                            @elseif($rent->status == 'ditolak')

                                                <span class="badge bg-danger">
                                                    Ditolak
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    {{ $rent->status }}
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    @else

                        <div class="alert alert-info mb-0">
                            Belum ada riwayat peminjaman buku.
                        </div>

                    @endif

                </div>

            </div>
        </div>
    </div>
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
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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
        .profile-name {
            font-size: 1.5rem;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
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

        const timers = Array.from(document.querySelectorAll('[data-countdown]'));

        if (timers.length > 0) {
            const formatCountdown = (totalSeconds) => {
                const safeSeconds = Math.max(0, totalSeconds);
                const days = Math.floor(safeSeconds / 86400);
                const hours = Math.floor((safeSeconds % 86400) / 3600);
                const minutes = Math.floor((safeSeconds % 3600) / 60);
                const seconds = safeSeconds % 60;

                return `${days}h ${String(hours).padStart(2, '0')}j ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}d`;
            };

            const updateTimers = () => {
                const now = Date.now();

                timers.forEach((timer) => {
                    const expiresAt = timer.getAttribute('data-expires-at');

                    if (!expiresAt) {
                        timer.textContent = '-';
                        return;
                    }

                    const expiresMs = new Date(expiresAt).getTime();
                    const remaining = Math.max(0, Math.floor((expiresMs - now) / 1000));

                    if (remaining <= 0) {
                        timer.textContent = 'Terlambat';
                        timer.classList.remove('text-bg-warning');
                        timer.classList.add('text-bg-danger');
                        return;
                    }

                    timer.textContent = formatCountdown(remaining);
                });
            };

            updateTimers();
            setInterval(updateTimers, 1000);
        }
    });
    let indexBook = 1;

document.getElementById('add-book').addEventListener('click', function () {

    let wrapper = document.getElementById('book-wrapper');

    let html = `
        <div class="row g-2 mb-2 book-item">

            <div class="col-md-7">

                <select name="books[${indexBook}][book_id]"
                    class="form-select"
                    required>

                    <option value="">
                        -- Pilih Buku --
                    </option>

                    @foreach($availableBooks as $b)

                        <option value="{{ $b->id }}">
                            {{ $b->title }} (stok: {{ $b->stock }})
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-3">

                <input type="number"
                    name="books[${indexBook}][qty]"
                    class="form-control"
                    min="1"
                    value="1"
                    placeholder="Qty"
                    required>

            </div>

            <div class="col-md-2 d-grid">

                <button type="button"
                    class="btn btn-danger remove-book">

                    <i class="bi bi-trash"></i>

                </button>

            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    indexBook++;
});

document.addEventListener('click', function(e){

    if(e.target.closest('.remove-book')){

        const items = document.querySelectorAll('.book-item');

        if(items.length > 1){
            e.target.closest('.book-item').remove();
        }

    }

});
</script>
@endsection
