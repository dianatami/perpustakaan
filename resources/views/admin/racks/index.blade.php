@extends('layout.admin')
@section('title','Rak Buku')
@section('content')
<style>
    :root {
        --rack-ink: #102a32;
        --rack-muted: #6b8088;
        --rack-surface: #ffffff;
        --rack-soft: #f6faf9;
        --rack-accent: #ff8a3d;
        --rack-primary: #0f8c80;
        --rack-border: rgba(16, 42, 50, 0.12);
        --rack-shadow: 0 18px 40px rgba(16, 42, 50, 0.12);
    }

    .rack-hero {
        background: linear-gradient(120deg, rgba(15, 140, 128, 0.95), rgba(29, 79, 120, 0.92), rgba(255, 138, 61, 0.88));
        color: #f7f5f0;
        padding: 28px 32px;
        border-radius: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--rack-shadow);
        margin-bottom: 24px;
    }

    .rack-hero::before,
    .rack-hero::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        opacity: 0.2;
    }

    .rack-hero::before {
        width: 220px;
        height: 220px;
        right: -90px;
        top: -90px;
        background: rgba(255, 255, 255, 0.22);
    }

    .rack-hero::after {
        width: 180px;
        height: 180px;
        left: -70px;
        bottom: -70px;
        background: rgba(255, 255, 255, 0.18);
    }

    .rack-hero h1 {
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 6px;
    }

    .rack-hero p {
        margin: 0;
        color: rgba(247, 245, 240, 0.9);
        max-width: 520px;
    }

    .rack-card {
        background: var(--rack-surface);
        border-radius: 18px;
        border: 1px solid var(--rack-border);
        box-shadow: 0 12px 28px rgba(16, 42, 50, 0.08);
    }

    .rack-card-header {
        padding: 18px 20px 0;
    }

    .rack-card-title {
        font-weight: 700;
        color: var(--rack-ink);
        margin-bottom: 4px;
    }

    .rack-card-subtitle {
        color: var(--rack-muted);
        font-size: 0.9rem;
    }

    .rack-card-body {
        padding: 18px 20px 20px;
    }

    .rack-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.16);
        color: #fff;
    }

    .rack-form .form-control,
    .rack-form .form-select,
    .rack-form textarea {
        border-radius: 12px;
        border: 1px solid var(--rack-border);
        padding: 10px 14px;
    }

    .rack-table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #54636a;
        background: #f1f6f6;
    }

    .rack-table tbody tr {
        border-bottom: 1px solid rgba(16, 42, 50, 0.08);
    }

    .rack-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .rack-badge.active {
        background: rgba(15, 140, 128, 0.12);
        color: #0f8c80;
    }

    .rack-badge.inactive {
        background: rgba(255, 122, 89, 0.12);
        color: #d65b3a;
    }

    .rack-code {
        font-weight: 700;
        color: var(--rack-ink);
    }

    .rack-meta {
        color: var(--rack-muted);
        font-size: 0.9rem;
    }

    .rack-actions .btn {
        border-radius: 10px;
        font-weight: 700;
    }

    .rack-search {
        max-width: 380px;
        width: 100%;
    }

    @media (max-width: 991px) {
        .rack-hero {
            padding: 22px;
        }

        .rack-hero h1 {
            font-size: 1.7rem;
        }
    }
</style>

<div class="rack-hero">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 52px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));">
            <div>
                <span class="rack-pill"><i class="bi bi-archive"></i> Manajemen Rak</span>
                <h1 class="mb-0 fw-bold">Atur rak buku dengan rapi</h1>
                <p class="mb-0">Kelompokkan koleksi buku berdasarkan rak agar pencarian lebih cepat dan tampilannya selalu tertata.</p>
            </div>
        </div>
        <div class="text-end">
            <div class="rack-pill"><i class="bi bi-layers"></i> {{ $racks->total() }} Rak</div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="rack-card">
            <div class="rack-card-header d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 40px; width: auto; object-fit: contain;">
                <div>
                    <h5 class="rack-card-title mb-0">Buat Rak Baru</h5>
                    <p class="rack-card-subtitle mb-0">Simpan kode rak agar buku mudah ditata dan ditemukan.</p>
                </div>
            </div>
            <div class="rack-card-body">
                <form action="{{ route('admin.racks.store') }}" method="POST" class="rack-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Rak</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: R-A01" value="{{ old('code') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Rak</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Rak Fiksi Utama" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="location" class="form-control" placeholder="Contoh: Lantai 1 - Timur" value="{{ old('location') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kapasitas</label>
                        <input type="number" min="0" name="capacity" class="form-control" placeholder="Jumlah buku" value="{{ old('capacity') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Catatan tambahan">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="rackActive" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="rackActive">Rak aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Rak</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="rack-card">
            <div class="rack-card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h5 class="rack-card-title">Daftar Rak Buku</h5>
                    <p class="rack-card-subtitle">Pantau jumlah koleksi dan posisi rak.</p>
                </div>
                <form action="{{ route('admin.racks.index') }}" method="GET" class="rack-search">
                    <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" placeholder="Cari kode, nama, lokasi..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary" type="submit" style="background: #0f8c80; border-color: #0f8c80;">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        @if(!empty($search))
                            <a href="{{ route('admin.racks.index', ['per_page' => $perPage ?? 10]) }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="rack-card-body">
                <div class="table-responsive">
                    <table class="table rack-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Kapasitas</th>
                                <th>Buku</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($racks as $rack)
                                <tr>
                                    <td class="rack-code">{{ $rack->code }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $rack->name }}</div>
                                        @if($rack->description)
                                            <div class="rack-meta">{{ $rack->description }}</div>
                                        @endif
                                    </td>
                                    <td class="rack-meta">{{ $rack->location ?? '-' }}</td>
                                    <td class="rack-meta">{{ $rack->capacity ?? '-' }}</td>
                                    <td class="rack-meta">{{ $rack->books_count }}</td>
                                    <td>
                                        <span class="rack-badge {{ $rack->is_active ? 'active' : 'inactive' }}">
                                            <i class="bi {{ $rack->is_active ? 'bi-check-circle-fill' : 'bi-slash-circle' }}"></i>
                                            {{ $rack->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-end rack-actions">
                                        <a href="{{ route('admin.racks.edit', $rack->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.racks.destroy', $rack->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rak ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada rak.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 px-4 py-3">
                <form action="{{ route('admin.racks.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                    <span class="text-muted">Tampilkan</span>
                    <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-muted">data per halaman</span>
                </form>
                <div>
                    {{ $racks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
