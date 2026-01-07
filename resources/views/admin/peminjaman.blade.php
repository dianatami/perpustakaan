<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            width: 400px;
            margin: 80px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Peminjaman Buku</h2>
    <form action="{{ route('admin.peminjaman.store') }}" method="POST">
        @csrf
        
        <label>Nama Peminjam</label>
        <select name="user_id" required>
            <option value="">-- Pilih Peminjam --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->nama }}</option>
            @endforeach
        </select>

        <label>Judul Buku</label>
        <select name="book_id" required>
            <option value="">-- Pilih Buku --</option>
            @foreach ($books as $book)
                <option value="{{ $book->id }}">{{ $book->title }}</option>
            @endforeach
        </select>

        <label>Tanggal Pinjam</label>
        <input type="date" name="borrow_date" value="{{ now()->format('Y-m-d') }}" required>

        <label>Tanggal Kembali (Opsional)</label>
        <input type="date" name="return_date">

        <button type="submit">Pinjam Buku</button>
    </form>
</div>

</body>
</html>
        }
        tbody tr:hover {
            background-color: #f8f9fa;
        }

