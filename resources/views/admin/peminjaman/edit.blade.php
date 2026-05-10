@extends('layout.admin')

@section('title','Edit Peminjaman')

@section('content')

<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a
            href="{{ route('admin.peminjaman.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Form Edit Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>
                                Terjadi Kesalahan!
                            </strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        action="{{ route('admin.peminjaman.update', $peminjaman->id) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')
                        {{-- PEMINJAM --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">

                                Nama Peminjam
                            </label>
                            <select
                                name="user_id"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    -- Pilih Peminjam --
                                </option>
                                @foreach ($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        @selected($peminjaman->user_id == $user->id)
                                    >
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DAFTAR BUKU --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Buku Dipinjam
                            </label>
                            @foreach($peminjaman->details as $detail)
                                <div class="border rounded p-3 mb-3 bg-light">

                                    <div class="row align-items-center">

                                        <div class="col-md-7">

                                            <div class="fw-bold">

                                                {{ $detail->book->title }}

                                            </div>

                                            <small class="text-muted">

                                                Kode:
                                                {{ $detail->book->book_code }}

                                            </small>

                                        </div>

                                        <div class="col-md-2">

                                            <span class="badge bg-primary">

                                                Qty:
                                                {{ $detail->qty }}

                                            </span>

                                        </div>

                                        <div class="col-md-3">

                                            {{-- kondisi buku --}}
                                            <select
                                                name="conditions[{{ $detail->id }}]"
                                                class="form-select"
                                            >
                                                <option
                                                    value="baik"
                                                    @selected($detail->condition == 'baik')
                                                >
                                                    Baik
                                                </option>
                                                <option
                                                    value="rusak"
                                                    @selected($detail->condition == 'rusak')
                                                >
                                                    Rusak
                                                </option>
                                                <option
                                                    value="hilang"
                                                    @selected($detail->condition == 'hilang')
                                                >
                                                    Hilang
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- TANGGAL --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Tanggal Pinjam
                                </label>
                                <input
                                    type="date"
                                    name="borrow_date"
                                    class="form-control"
                                    value="{{ \Carbon\Carbon::parse($peminjaman->borrow_date)->format('Y-m-d') }}"
                                    required
                                >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Tanggal Kembali
                                </label>
                                <input
                                    type="date"
                                    name="return_date"
                                    class="form-control"
                                    value="{{ \Carbon\Carbon::parse($peminjaman->return_date)->format('Y-m-d') }}"
                                >
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Status Peminjaman
                            </label>
                            <select
                                name="status"
                                class="form-select"
                                required
                            >
                                <option
                                    value="menunggu_acc"
                                    @selected($peminjaman->status == 'menunggu_acc')
                                >
                                    Menunggu ACC
                                </option>

                                <option
                                    value="dipinjam"
                                    @selected($peminjaman->status == 'dipinjam')
                                >
                                    Dipinjam
                                </option>

                                <option
                                    value="ditolak"
                                    @selected($peminjaman->status == 'ditolak')
                                >
                                    Ditolak
                                </option>

                                <option
                                    value="proses_kembali"
                                    @selected($peminjaman->status == 'proses_kembali')
                                >
                                    Proses Pengembalian
                                </option>

                                <option
                                    value="kembali"
                                    @selected($peminjaman->status == 'kembali')
                                >
                                    Sudah Dikembalikan
                                </option>
                            </select>
                        </div>

                        {{-- DENDA --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Denda
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    Rp
                                </span>
                                <input
                                    type="number"
                                    name="denda"
                                    class="form-control"
                                    value="{{ $peminjaman->denda ?? 0 }}"
                                >
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex gap-2">
                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="fas fa-save"></i>
                                Simpan Perubahan
                            </button>
                            <a
                                href="{{ route('admin.peminjaman.index') }}"
                                class="btn btn-secondary"
                            >
                                <i class="fas fa-times"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- SIDE INFO --}}
        <div class="col-lg-4">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Informasi Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">
                            ID Transaksi
                        </small>
                        <div class="fw-bold">
                            #{{ $peminjaman->id }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">
                            Total Buku
                        </small>
                        <div class="fw-bold">
                            {{ $peminjaman->details->count() }} Buku
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">
                            Total Qty
                        </small>
                        <div class="fw-bold">
                            {{ $peminjaman->details->sum('qty') }}
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">
                            Dibuat Pada
                        </small>
                        <div class="fw-bold">
                            {{
                                \Carbon\Carbon::parse(
                                    $peminjaman->created_at
                                )->format('d M Y H:i')
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection