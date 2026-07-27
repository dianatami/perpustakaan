@extends('layout.admin')

@section('title','Proses Pengembalian')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Proses Pengembalian</h3>
            <small class="text-muted">Terima pengembalian dan periksa kondisi buku</small>
        </div>
        <div>
            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white d-flex align-items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 44px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                    <h5 class="mb-0 text-white fw-bold">Terima Pengembalian Buku</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.peminjaman.confirm-return', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <small class="text-muted">ID Transaksi</small>
                            <div class="fw-bold">#{{ $peminjaman->id }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Buku Dikembalikan</label>
                            @foreach($peminjaman->details as $detail)
                                <div class="border rounded p-3 mb-3 bg-light">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-8 col-md-7">
                                            <div class="fw-bold">{{ $detail->book->title }}</div>
                                            <small class="text-muted">Kode: {{ $detail->book->book_code }}</small>
                                        </div>
                                        <div class="col-4 col-md-2 text-md-center">
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label fw-semibold">Tanggal Kembali</label>
                                <input id="return_date" type="date" name="return_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="denda" class="form-label fw-semibold">Denda</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input id="denda" type="number" name="denda" class="form-control" value="{{ $peminjaman->denda ?? 0 }}" min="0" step="1000">
                                </div>
                                <div class="form-text">Ubah nilai denda jika perlu, atau biarkan sistem menghitung otomatis.</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Terima Pengembalian</button>
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Nama Peminjam</small>
                        <div class="fw-bold">{{ $peminjaman->user->nama ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Pinjam</small>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($peminjaman->borrow_date)->format('d M Y') }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Total Qty</small>
                        <div class="fw-bold">{{ $peminjaman->details->sum('qty') }}</div>
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
            if (!returnDate) return;

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
                console.error('calculateFine error', err);
            }
        }

        document.querySelectorAll('select[name^="conditions["]').forEach(s => {
            s.addEventListener('change', updateFine);
        });

        if (returnInput) returnInput.addEventListener('change', updateFine);

        // initial
        updateFine();
    })();
</script>

@endsection
