@extends('layout.admin')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 44px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));">
            <div>
                <h2 class="mb-0 fw-bold" style="color: #0f172a;">Manajemen Anggota</h2>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Template Download Buttons -->
            <a href="{{ route('admin.anggota.templateGuru') }}" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Download Template Excel Guru">
                <i class="fas fa-file-excel me-1"></i> Template Guru
            </a>
            <a href="{{ route('admin.anggota.templateSiswa') }}" class="btn btn-sm btn-outline-info rounded-pill px-3" title="Download Template Excel Siswa">
                <i class="fas fa-file-excel me-1"></i> Template Siswa
            </a>

            <!-- Import Excel Buttons -->
            <button type="button" class="btn btn-sm btn-success rounded-pill px-3" data-toggle="modal" data-target="#importGuruModal" data-bs-toggle="modal" data-bs-target="#importGuruModal">
                <i class="fas fa-file-import me-1"></i> Import Guru
            </button>
            <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3" data-toggle="modal" data-target="#importSiswaModal" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
                <i class="fas fa-file-import me-1"></i> Import Siswa
            </button>

            <!-- Manual Add Member -->
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary rounded-pill px-3 fw-bold" style="background: #0f8c80; border-color: #0f8c80;">
                <i class="fas fa-plus me-1"></i> Tambah Anggota
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.anggota.index') }}">
                <div class="input-group">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama, email, NISN atau NIP..."
                        value="{{ request('search') }}"
                    >

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>

                    @if(request('search'))
                        <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Tabel Siswa -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Siswa ({{ $siswa->total() }} terdaftar)</h5>
        </div>
        <div class="card-body">
            @if ($siswa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>No.Induk</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Status</th>
                                <th>Tanggal Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswa as $item)
                                <tr>
                                    <td>{{ ($siswa->currentPage() - 1) * $siswa->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->nisn ?? '-' }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->kelas ?? '-' }}</span></td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->hp }}</td>
                                    <td>
                                        <span class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data siswa terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($siswa->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $siswa->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    Belum ada data siswa terdaftar. <a href="{{ route('admin.anggota.create') }}">Tambah siswa sekarang</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Guru -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Data Guru ({{ $guru->total() }} terdaftar)</h5>
        </div>
        <div class="card-body">
            @if ($guru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>No. Induk</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>NIP</th>
                                <th>Nomor HP</th>
                                <th>Status</th>
                                <th>Tanggal Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guru as $item)
                                <tr>
                                    <td>{{ ($guru->currentPage() - 1) * $guru->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->nip ?? '-' }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->nip ?? '-' }}</td>
                                    <td>{{ $item->hp }}</td>
                                    <td>
                                        <span class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada data guru terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($guru->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $guru->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    Belum ada data guru terdaftar.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Custom Style Fix: Hilangkan modal-backdrop hitam yang menghalangi klik -->
<style>
    .modal-backdrop {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }
    .modal.show {
        background-color: rgba(0, 0, 0, 0.4) !important;
    }
</style>

<!-- Modal Import Guru Excel -->
<div class="modal fade" id="importGuruModal" tabindex="-1" data-backdrop="false" data-bs-backdrop="false" aria-labelledby="importGuruModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 38px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                <h5 class="modal-title font-weight-bold mb-0 text-white" id="importGuruModalLabel"><i class="fas fa-file-excel"></i> Import Data Guru dari Excel</h5>
                <button type="button" class="close text-white ms-auto" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.anggota.importGuru') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">Unggah file Excel berisi data Guru (NIP, Nama, Email, No HP). Sistem otomatis membuatkan akun login dengan password default: <strong>SmkTirtamulya2026</strong>.</p>
                    <div class="form-group mb-3">
                        <label for="fileGuru" class="form-label font-weight-bold text-dark">Pilih File Excel / CSV:</label>
                        <input type="file" name="file" id="fileGuru" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="defaultPasswordGuru" class="form-label font-weight-bold text-dark">Password Default Akun Baru:</label>
                        <input type="text" name="default_password" id="defaultPasswordGuru" value="SmkTirtamulya2026" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-upload"></i> Import & Pindah ke Sistem</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Siswa Excel -->
<div class="modal fade" id="importSiswaModal" tabindex="-1" data-backdrop="false" data-bs-backdrop="false" aria-labelledby="importSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-info text-white d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 38px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                <h5 class="modal-title font-weight-bold mb-0 text-white" id="importSiswaModalLabel"><i class="fas fa-file-excel"></i> Import Data Siswa dari Excel</h5>
                <button type="button" class="close text-white ms-auto" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.anggota.importSiswa') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">Unggah file Excel berisi data Siswa (NISN, Nama, Email, No HP). Sistem otomatis membuatkan akun login dengan password default: <strong>SmkTirtamulya2026</strong>.</p>
                    <div class="form-group mb-3">
                        <label for="fileSiswa" class="form-label font-weight-bold text-dark">Pilih File Excel / CSV:</label>
                        <input type="file" name="file" id="fileSiswa" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="defaultPasswordSiswa" class="form-label font-weight-bold text-dark">Password Default Akun Baru:</label>
                        <input type="text" name="default_password" id="defaultPasswordSiswa" value="SmkTirtamulya2026" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white font-weight-bold"><i class="fas fa-upload"></i> Import & Pindah ke Sistem</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection