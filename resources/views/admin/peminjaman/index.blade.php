{{-- ====================================================== --}}
{{-- resources/views/admin/peminjaman/index.blade.php --}}
{{-- ====================================================== --}}

@extends('layout.admin')

@section('title','Data Peminjaman')

@section('content')

<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                📚 Data Peminjaman Buku
            </h2>

            <p class="text-muted mb-0">
                Kelola data transaksi peminjaman buku
            </p>

        </div>

        <a
            href="{{ route('admin.peminjaman.create') }}"
            class="btn btn-primary"
        >

            <i class="fas fa-plus"></i>
            Tambah Peminjaman

        </a>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-times-circle"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- CARD --}}
    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="fas fa-list"></i>
                Daftar Peminjaman
            </h5>

        </div>

        <div class="card-body">

            {{-- SEARCH BAR PUNYA TEMEN --}}
            <div class="row mb-3">
                <div class="col-md-6">

                    <label for="peminjam_search" class="form-label">
                        Cari Nama Peminjam
                    </label>

                    <div class="input-group">

                        <input
                            type="text"
                            class="form-control"
                            id="peminjam_search"
                            placeholder="Ketik nama peminjam"
                        >

                        <button
                            class="btn btn-outline-primary"
                            type="button"
                            id="peminjam_search_button"
                        >

                            <i class="fas fa-search"></i> Cari

                        </button>

                        <button
                            class="btn btn-outline-secondary"
                            type="button"
                            id="peminjam_clear_button"
                        >

                            <i class="fas fa-times"></i> Reset

                        </button>

                    </div>

                </div>
            </div>

            @if($peminjaman->count() > 0)

                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">

                            <tr>

                                <th width="50">No</th>

                                <th>Peminjam</th>

                                <th width="350">Buku Dipinjam</th>

                                <th>Tgl Pinjam</th>

                                <th>Tgl Kembali</th>

                                <th>Status</th>

                                <th>Denda</th>

                                <th width="230">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($peminjaman as $item)

                                <tr>

                                    {{-- NOMOR --}}
                                    <td>
                                        {{
                                            ($peminjaman->currentPage() - 1)
                                            *
                                            $peminjaman->perPage()
                                            +
                                            $loop->iteration
                                        }}
                                    </td>

                                    {{-- PEMINJAM --}}
                                    <td>

                                        <div class="fw-bold peminjam-nama">
                                            {{ $item->user->nama ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $item->user->email ?? '-' }}
                                        </small>

                                    </td>

                                    {{-- BUKU --}}
                                    <td>

                                        @if($item->details->count() > 0)

                                            @foreach($item->details as $detail)

                                                <div class="border rounded p-2 mb-2 bg-light">

                                                    <div class="fw-bold">
                                                        {{ $detail->book->title ?? '-' }}
                                                    </div>

                                                    <small class="text-muted">
                                                        Kode:
                                                        {{ $detail->book->book_code ?? '-' }}
                                                    </small>

                                                    <br>

                                                    <span class="badge bg-primary mt-1">
                                                        Qty:
                                                        {{ $detail->qty }}
                                                    </span>

                                                </div>

                                            @endforeach

                                        @else

                                            <span class="text-muted">
                                                Tidak ada buku
                                            </span>

                                        @endif

                                    </td>

                                    {{-- TANGGAL PINJAM --}}
                                    <td>
                                        {{
                                            \Carbon\Carbon::parse(
                                                $item->borrow_date
                                            )->format('d M Y')
                                        }}
                                    </td>

                                    {{-- TANGGAL KEMBALI --}}
                                    <td>
                                        {{
                                            \Carbon\Carbon::parse(
                                                $item->return_date
                                            )->format('d M Y')
                                        }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td>

                                        @if($item->status == 'menunggu_acc')

                                            <span class="badge bg-warning text-dark">
                                                Menunggu ACC
                                            </span>

                                        @elseif($item->status == 'dipinjam')

                                            <span class="badge bg-info">
                                                Dipinjam
                                            </span>

                                        @elseif($item->status == 'ditolak')

                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>

                                        @elseif($item->status == 'proses_kembali')

                                            <span class="badge bg-primary">
                                                Proses Pengembalian
                                            </span>

                                        @elseif($item->status == 'kembali')

                                            <span class="badge bg-success">
                                                Sudah Kembali
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                    {{-- DENDA --}}
                                    <td>

                                        @if($item->denda > 0)

                                            <span class="text-danger fw-bold">

                                                Rp
                                                {{
                                                    number_format(
                                                        $item->denda,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- AKSI --}}
                                    <td>

                                        <div class="d-flex flex-wrap gap-1">

                                            <a
                                                href="{{ route('admin.peminjaman.edit', $item->id) }}"
                                                class="btn btn-primary btn-sm"
                                            >

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <form
                                                action="{{ route('admin.peminjaman.destroy', $item->id) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Hapus data ini?')"
                                                >

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $peminjaman->links() }}
                </div>

            @else

                <div class="alert alert-info text-center mb-0">

                    <i class="fas fa-info-circle"></i>

                    Belum ada data peminjaman

                </div>

            @endif

        </div>

    </div>

</div>

{{-- SCRIPT SEARCH --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('peminjam_search');
        const searchButton = document.getElementById('peminjam_search_button');
        const clearButton = document.getElementById('peminjam_clear_button');
        const rows = Array.from(document.querySelectorAll('table tbody tr'));

        const filterRows = () => {

            const query = searchInput.value.toLowerCase().trim();

            rows.forEach((row) => {

                const nameCell = row.querySelector('.peminjam-nama');

                const nameText = nameCell
                    ? nameCell.textContent.toLowerCase()
                    : '';

                row.style.display =
                    query === '' || nameText.includes(query)
                    ? ''
                    : 'none';

            });

        };

        searchInput.addEventListener('input', filterRows);

        searchButton.addEventListener('click', filterRows);

        clearButton.addEventListener('click', function () {

            searchInput.value = '';
            filterRows();

        });

    });
</script>

@endsection