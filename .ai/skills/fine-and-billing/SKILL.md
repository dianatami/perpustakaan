---
name: fine-and-billing
description: Gunakan skill ini untuk mengurus sistem denda keterlambatan pengembalian buku, pembuatan invoice, dan integrasi modul pembayaran denda.
---

# Fine & Billing Automation

1. **Daily Scheduler**: Perhitungan denda keterlambatan harus ditaruh di dalam Laravel Artisan Command (pp:calculate-fines) yang dijalankan otomatis setiap hari melalui Schedule.
2. **Formula**: Rumus denda bersifat dinamis mengacu pada konfigurasi global (misal: Rp 2.000 × jumlah hari terlambat).
3. **Invoice State**: Denda baru berstatus unpaid. Jangan ubah ke paid sebelum ada verifikasi sukses dari payment gateway atau admin.
