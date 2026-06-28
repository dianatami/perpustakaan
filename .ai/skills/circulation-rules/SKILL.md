---
name: circulation-rules
description: Gunakan skill ini saat menangani logika transaksi peminjaman buku, pengembalian, perpanjangan durasi, dan manajemen stok fisik buku.
---

# Library Circulation Logic

1. **Race Condition Prevention**: Selalu bungkus logika peminjaman buku di dalam DB::transaction() dan gunakan lockForUpdate() pada row buku yang ingin dipinjam agar stok tidak minus.
2. **Validation**: Pastikan memeriksa sisa kuota pinjam user sebelum mengurangi vailable_copies pada tabel books.
3. **Status Tracking**: Gunakan Enum atau status string yang konsisten (orrowed, eturned, overdue) pada model Borrowing.
