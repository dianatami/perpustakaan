{{-- ===================================================== --}}
{{-- resources/views/admin/peminjaman/create.blade.php --}}
{{-- ===================================================== --}}

@extends('layout.admin')

@section('title','Tambah Peminjaman')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-4">

    <div class="card shadow border-0">

        <div class="card-header text-white d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #0f8c80 0%, #116b64 100%);">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" style="max-height: 48px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                <h4 class="mb-0 text-white fw-bold">
                    Form Peminjaman Buku
                </h4>
            </div>
            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold" style="color: #0f8c80;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card-body p-4">

            {{-- ERROR --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- VALIDATION --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.peminjaman.store') }}" method="POST">

                @csrf

                <div class="row">

                    {{-- USER --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold text-dark">
                            Peminjam
                        </label>

                        <select
                            name="user_id"
                            class="form-select select2"
                            required
                        >

                            <option value="">
                                Cari peminjam...
                            </option>

                            @foreach ($users as $user)
                                @php
                                    $idNum = !empty($user->nisn) ? 'NISN: ' . $user->nisn : (!empty($user->nip) ? 'NIP: ' . $user->nip : '');
                                @endphp
                                <option
                                    value="{{ $user->id }}"
                                    {{ $user->status == 0 ? 'disabled' : '' }}
                                >
                                    {{ $user->nama }}
                                    @if($idNum) - {{ $idNum }} @endif
                                    {{ $user->status == 0 ? '(Nonaktif)' : '' }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold text-dark">
                            Tanggal Pinjam
                        </label>

                        <input
                            type="date"
                            name="borrow_date"
                            class="form-control"
                            value="{{ now()->format('Y-m-d') }}"
                            required
                        >

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold text-dark">
                            Tanggal Kembali
                        </label>

                        <input
                            type="date"
                            name="return_date"
                            class="form-control"
                            required
                        >

                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-book me-2" style="color: #0f8c80;"></i> Daftar Buku
                    </h5>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold"
                        style="color: #0f8c80; border-color: #0f8c80;"
                        id="add-book"
                    >
                        <i class="fas fa-plus me-1"></i> Tambah Buku
                    </button>
                </div>

                {{-- WRAPPER --}}
                <div id="book-wrapper">

                    {{-- ITEM --}}
                    <div class="book-item row mb-3 align-items-start">

                        {{-- SELECT BUKU --}}
                        <div class="col-md-7">

                            <select
                                name="books[0][book_id]"
                                class="form-select select2 book-select"
                                required
                            >

                                <option value="">
                                    Cari buku...
                                </option>

                                @foreach ($books as $book)

                                    <option
                                        value="{{ $book->id }}"
                                        {{ $book->stock <= 0 ? 'disabled' : '' }}
                                        data-stock="{{ $book->stock }}"
                                    >

                                        {{ $book->book_code }}
                                        -
                                        {{ $book->title }}
                                        (stok: {{ $book->stock }})

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- QTY --}}
                        <div class="col-md-3">

                            <input
                                type="number"
                                name="books[0][qty]"
                                class="form-control"
                                placeholder="Jumlah"
                                min="1"
                                required
                            >

                            <small class="text-muted stock-text d-block mt-1">
                                Stok tersedia: -
                            </small>

                        </div>

                        {{-- BUTTON --}}
                        <div class="col-md-2">

                            <button
                                type="button"
                                class="btn btn-outline-danger remove-book w-100"
                            >
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>

                        </div>

                    </div>

                </div>

                <div class="d-flex align-items-center gap-2 pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold" style="background: #0f8c80; border-color: #0f8c80;">
                        <i class="fas fa-save me-2"></i> Simpan Peminjaman
                    </button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                        Batal
                    </a>
                </div>

            </form>

        </div>

    </div>

</div>

{{-- JQUERY --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

{{-- SELECT2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function(){

    let index = 1;

    // =========================================
    // INIT SELECT2
    // =========================================

    function initSelect2(){
        $('select[name="user_id"]').select2({
            width: '100%',
            placeholder: 'Cari peminjam...'
        });
        $('.book-select').select2({
            width: '100%',
            placeholder: 'Cari buku...'
        });
    }

    initSelect2();

    // Set return_date to 7 days after borrow_date
    function formatDateYMD(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function setReturnDateFromBorrow() {
        const borrowVal = $('input[name="borrow_date"]').val();
        if (!borrowVal) return;
        const b = new Date(borrowVal + 'T00:00:00');
        b.setDate(b.getDate() + 7);
        $('input[name="return_date"]').val(formatDateYMD(b));
    }

    // initialize return date on load
    setReturnDateFromBorrow();

    // update when borrow date changes
    $(document).on('change', 'input[name="borrow_date"]', function(){
        setReturnDateFromBorrow();
    });

    // =========================================
    // UPDATE OPTION AGAR TIDAK DUPLIKAT
    // =========================================

    function updateBookOptions(){

        let selectedBooks = [];

        $('.book-select').each(function(){

            let value = $(this).val();

            if(value){
                selectedBooks.push(value);
            }

        });

        $('.book-select').each(function(){

            let currentSelect = $(this);

            let currentValue = currentSelect.val();

            currentSelect.find('option').each(function(){

                let optionValue = $(this).val();

                if(
                    optionValue &&
                    selectedBooks.includes(optionValue) &&
                    optionValue !== currentValue
                ){
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }

            });

        });

    }

    // =========================================
    // TAMBAH BUKU
    // =========================================

    $('#add-book').click(function(){

        let html = `
            <div class="book-item row mb-3 align-items-start">

                <div class="col-md-7">

                    <select
                        name="books[${index}][book_id]"
                        class="form-select select2 book-select"
                        required
                    >

                        <option value="">
                            Cari buku...
                        </option>

                        @foreach ($books as $book)

                            <option
                                value="{{ $book->id }}"
                                data-stock="{{ $book->stock }}"
                            >

                                {{ $book->book_code }}
                                -
                                {{ $book->title }}
                                (stok: {{ $book->stock }})

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3">

                    <input
                        type="number"
                        name="books[${index}][qty]"
                        class="form-control qty-input"
                        placeholder="Jumlah"
                        min="1"
                        required
                    >

                    <small class="text-muted stock-text">
                        Stok tersedia: -
                    </small>

                </div>

                <div class="col-md-2">

                    <button
                        type="button"
                        class="btn btn-danger remove-book w-100"
                    >
                        Hapus
                    </button>

                </div>

            </div>
        `;

        $('#book-wrapper').append(html);

        initSelect2();

        updateBookOptions();

        index++;

    });

    // =========================================
    // HAPUS
    // =========================================

    $(document).on('click', '.remove-book', function(){

        if($('.book-item').length > 1){

            $(this).closest('.book-item').remove();

            updateBookOptions();

        }

    });

    // =========================================
    // CHANGE BUKU
    // =========================================

    $(document).on('change', '.book-select', function(){

        updateBookOptions();

        let stock = $(this)
            .find(':selected')
            .data('stock');

        $(this)
            .closest('.book-item')
            .find('.stock-text')
            .text('Stok tersedia: ' + stock);

    });

});

</script>

@endsection