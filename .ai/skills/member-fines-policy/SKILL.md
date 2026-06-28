---
name: member-fines-policy
description: Gunakan skill ini saat memodifikasi registrasi anggota perpustakaan, batasan maksimal peminjaman, dan aturan penangguhan akun (suspend).
---

# Member Policy & Restrictions

1. **Laravel Policy**: Buat policy BorrowPolicy untuk mengecek kelayakan member.
2. **Strict Block**: Blokir tombol atau API peminjaman baru jika member memiliki denda aktif (unpaid) yang melewati batas toleransi hari atau nominal tertentu.
3. **Max Limits**: Validasi batasan maksimal buku yang boleh dipinjam secara bersamaan (misal: maksimal 3 buku per akun mahasiswa).
