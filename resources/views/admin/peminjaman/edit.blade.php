@extends('layout.admin')

@section('title','Edit Peminjaman')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Edit Peminjaman</h3>
            <small class="text-muted">Ubah detail peminjaman dan kondisi buku</small>
        </div>
        <div>
            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Form Edit Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    {{-- GLOBAL ERRORS --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi Kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- PEMINJAM --}}
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">Nama Peminjam</label>
                            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required aria-describedby="userHelp">
                                <option value="">-- Pilih Peminjam --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($peminjaman->user_id == $user->id)>
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="userHelp" class="form-text">Pilih anggota yang meminjam buku.</div>
                        </div>

                        {{-- DAFTAR BUKU --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Buku Dipinjam</label>
                            @foreach($peminjaman->details as $detail)
                                <div class="border rounded p-3 mb-3 bg-light" data-detail-id="{{ $detail->id }}" data-qty="{{ $detail->qty }}">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-9 col-md-7">
                                            <div class="fw-bold">{{ $detail->book->title }}</div>
                                            <small class="text-muted">Kode: {{ $detail->book->book_code }}</small>
                                        </div>
                                        <div class="col-3 col-md-2 text-md-center">
                                            <span class="badge bg-primary">{{ $detail->qty }}x</span>
                                        </div>
                                        <div class="col-12 col-md-3 mt-2 mt-md-0">
                                            <label class="form-label visually-hidden" for="cond-{{ $detail->id }}">Kondisi</label>
                                            <select id="cond-{{ $detail->id }}" name="conditions[{{ $detail->id }}]" class="form-select">
                                                <option value="baik" @selected($detail->condition == 'baik')>Baik</option>
                                                <option value="rusak" @selected($detail->condition == 'rusak')>Rusak</option>
                                                <option value="hilang" @selected($detail->condition == 'hilang')>Hilang</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- TANGGAL --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="borrow_date" class="form-label fw-semibold">Tanggal Pinjam</label>
                                <input id="borrow_date" type="date" name="borrow_date" class="form-control @error('borrow_date') is-invalid @enderror" value="{{ \Carbon\Carbon::parse($peminjaman->borrow_date)->format('Y-m-d') }}" required>
                                @error('borrow_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label fw-semibold">Tanggal Kembali</label>
                                <input id="return_date" type="date" name="return_date" class="form-control @error('return_date') is-invalid @enderror" value="{{ \Carbon\Carbon::parse($peminjaman->return_date)->format('Y-m-d') }}">
                                @error('return_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Status Peminjaman</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="dipinjam" @selected($peminjaman->status == 'dipinjam')>Dipinjam</option>
                                <option value="kembali" @selected($peminjaman->status == 'kembali')>Sudah Dikembalikan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DENDA --}}
                        <div class="mb-4">
                            <label for="denda" class="form-label fw-semibold">Denda</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input id="denda" type="number" name="denda" class="form-control @error('denda') is-invalid @enderror" value="{{ $peminjaman->denda ?? 0 }}" min="0" step="1000" aria-label="Jumlah denda">
                                @error('denda')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Isi 0 jika tidak ada denda.</div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- SIDE INFO --}}
        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">ID Transaksi</small>
                        <div class="fw-bold">#{{ $peminjaman->id }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Buku</small>
                        <div class="fw-bold">{{ $peminjaman->details->count() }} Buku</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Qty</small>
                        <div class="fw-bold">{{ $peminjaman->details->sum('qty') }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            @if($peminjaman->status == 'dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Sudah Dikembalikan</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Dibuat Pada</small>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const calculateUrl = '{{ route('admin.peminjaman.calculate-fine', $peminjaman->id) }}';
        const token = '{{ csrf_token() }}';
        const dendaInput = document.getElementById('denda');
        const returnInput = document.getElementById('return_date');
        const borrowInput = document.getElementById('borrow_date');

        function gatherConditions() {
            const selects = document.querySelectorAll('select[name^="conditions["]');
            const data = {};
            selects.forEach(s => {
                const idMatch = s.name.match(/conditions\[(\d+)\]/);
                if (!idMatch) return;
                const id = idMatch[1];
                data[id] = s.value;
            });
            return data;
        }

        async function updateFine() {
            const returnDate = returnInput.value;
            const borrowDate = borrowInput.value;
            if (!returnDate || !borrowDate) return;

            const body = {
                return_date: returnDate,
                conditions: gatherConditions()
            };

            try {
                const res = await fetch(calculateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });
                if (!res.ok) return;
                const json = await res.json();
                if (dendaInput) {
                    dendaInput.value = json.denda ?? 0;
                }
            } catch (err) {
                // fail silently
                console.error('calculateFine error', err);
            }
        }

        // wire events
        document.querySelectorAll('select[name^="conditions["]').forEach(s => {
            s.addEventListener('change', updateFine);
        });

        if (returnInput) returnInput.addEventListener('change', updateFine);
        if (borrowInput) borrowInput.addEventListener('change', updateFine);

        // initial calculation
        updateFine();
    })();
</script>

@endsection

