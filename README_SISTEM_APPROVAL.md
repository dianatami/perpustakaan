# 📚 SISTEM APPROVAL PEMINJAMAN BUKU - DOKUMENTASI LENGKAP

## 🎯 Ringkas

Sistem approval peminjaman buku yang **production-ready** memungkinkan:
- ✅ **Murid/Guru** mengajukan permintaan peminjaman buku
- ✅ **Admin** menerima/menolak dengan validasi stok real-time
- ✅ **Murid/Guru** melacak status dan pengembalian
- ✅ **Admin** mengelola denda dan inventori buku

---

## 📌 Status Implementasi

### ✨ Fitur yang Sudah Ada & Diperbaiki

**Admin Dashboard - Kelola Peminjaman:**
```
✅ Daftar peminjaman dengan tabel responsif
✅ Filter status (Menunggu ACC, Dipinjam, Ditolak, dll)
✅ Search real-time nama murid/buku
✅ Statistics cards (4 metrik)
✅ Modal approve dengan durasi input
✅ Auto-calculate tanggal pengembalian
✅ Tombol tolak dengan konfirmasi
✅ Terima pengembalian dengan kondisi buku
✅ Hitung denda otomatis
✅ Edit peminjaman
✅ Pagination
```

**Murid/Guru Profile:**
```
✅ Form pinjam buku dengan dropdown
✅ Tambah/hapus buku dalam form
✅ Tabel riwayat peminjaman
✅ Statistics (Total, Sedang Dipinjam, Selesai)
✅ Status badge dengan 5 warna
✅ Tombol kembalikan buku
✅ Validasi duplikat & stok
✅ Limit 3 peminjaman aktif
```

---

## 🚀 Quick Start

### **Untuk Admin**

1. **Buka Dashboard**
   ```
   URL: /admin/peminjaman
   ```

2. **Lihat Permintaan Baru**
   - Filter: "Menunggu Persetujuan"
   - Lihat statistics: X permintaan menunggu

3. **Approve Permintaan**
   - Klik tombol "SETUJUI" (Hijau)
   - Tentukan durasi (1-30 hari)
   - Klik "KONFIRMASI PERSETUJUAN"

4. **Monitor Pengembalian**
   - Filter: "Proses Pengembalian"
   - Klik "TERIMA PENGEMBALIAN"
   - Set kondisi buku & hitung denda

### **Untuk Murid/Guru**

1. **Buka Profil**
   ```
   URL: /anggota/profil-detail atau /guru/profil-detail
   ```

2. **Pinjam Buku**
   - Pilih buku dari dropdown
   - Masukkan jumlah (qty)
   - Klik "AJUKAN"
   - Status: "Menunggu ACC"

3. **Lihat Status**
   - Buka profil
   - Lihat tabel riwayat peminjaman
   - Status berubah saat admin approve

4. **Kembalikan Buku**
   - Klik tombol "KEMBALIKAN"
   - Status: "Proses Pengembalian"
   - Tunggu admin konfirmasi

---

## 📖 Dokumentasi

### **Panduan Lengkap (Download/Baca):**

1. **[PANDUAN_CEPAT_ADMIN.md](./PANDUAN_CEPAT_ADMIN.md)** ⭐ **MULAI DARI SINI**
   - Quick start 3 langkah
   - Skenario penggunaan
   - Tombol & fitur
   - Troubleshooting

2. **[PANDUAN_MURID_GURU.md](./PANDUAN_MURID_GURU.md)**
   - Cara mengajukan peminjaman
   - Melihat status
   - Mengembalikan buku
   - FAQ untuk pengguna

3. **[SISTEM_APPROVAL_PEMINJAMAN.md](./SISTEM_APPROVAL_PEMINJAMAN.md)**
   - Dokumentasi lengkap
   - Alur proses
   - Perhitungan denda
   - Validasi sistem

4. **[IMPLEMENTASI_SUMMARY.md](./IMPLEMENTASI_SUMMARY.md)**
   - Ringkasan teknis
   - Database schema
   - Routes & controllers
   - Business logic

5. **[TESTING_CHECKLIST.md](./TESTING_CHECKLIST.md)**
   - Pre-launch checklist
   - Test cases
   - Security testing
   - Performance testing

---

## 📊 Status Peminjaman

```
┌─────────────────────────────────────────────────────┐
│                 FLOW STATUS                         │
├─────────────────────────────────────────────────────┤
│                                                     │
│  [Murid Ajukan] ──────────────────┐               │
│        ↓                           │               │
│  menunggu_acc                      │               │
│   (Kuning)                         │               │
│        │                           │               │
│        ├─[Admin Setujui]──────┐   │               │
│        │        ↓             │   │               │
│        │    dipinjam          │   │               │
│        │    (Biru)            │   │               │
│        │        │             │   │               │
│        │        └─[Kembalikan]→┐  │               │
│        │             ↓        │   │               │
│        │        proses_kembali│   │               │
│        │        (Kuning)      │   │               │
│        │             │        │   │               │
│        │      [Admin Terima]  │   │               │
│        │             ↓        │   │               │
│        │          kembali     │   │               │
│        │          (Hijau)     │   │               │
│        │                      │   │               │
│        └──[Admin Tolak]───────┘   │               │
│                 ↓                 │               │
│            ditolak ───────────────┘               │
│            (Merah)                                │
│                                                   │
└─────────────────────────────────────────────────────┘
```

---

## 💰 Perhitungan Denda

### **Denda Keterlambatan**
```
Periode: 7 hari
Jika terlambat: Rp 5.000 × (hari terlambat)

Contoh:
- Pinjam 7 hari, kembali 8 hari → Denda: Rp 5.000
- Pinjam 7 hari, kembali 10 hari → Denda: Rp 15.000
- Pinjam 7 hari, kembali tepat waktu → Denda: Rp 0
```

### **Denda Kerusakan/Kehilangan**
```
- Rusak: Rp 50.000
- Hilang: Rp 50.000
- Baik: Rp 0
```

### **Total Denda**
```
Total = Denda Keterlambatan + Denda Kerusakan
```

---

## 🔍 Fitur Admin

### **Dashboard Kelola Peminjaman**

#### **Statistics (4 Kartu)**
| Kartu | Warna | Deskripsi |
|-------|-------|-----------|
| Menunggu Persetujuan | 🟡 Kuning | Jumlah request baru |
| Sudah Disetujui | 🟢 Hijau | Buku sedang dipinjam |
| Ditolak | 🔴 Merah | Total penolakan |
| Total Peminjaman | 🟠 Orange | Semua riwayat |

#### **Filter Status**
```
- Semua Status
- Menunggu Persetujuan
- Sudah Disetujui
- Proses Pengembalian
- Sudah Dikembalikan
- Ditolak
```

#### **Action Buttons**
| Tombol | Kondisi | Aksi |
|--------|---------|------|
| ✅ SETUJUI | Status = Menunggu | Approve + durasi |
| ❌ TOLAK | Status = Menunggu | Reject request |
| 📦 TERIMA PENGEMBALIAN | Status = Proses Kembali | Confirm + kondisi |
| 📝 EDIT | Semua | Edit data |

---

## 🎛️ Fitur Murid/Guru

### **Profil - Form Pinjam Buku**

```
┌─────────────────────────────────────┐
│    FORM PINJAM BUKU                 │
├─────────────────────────────────────┤
│                                     │
│  Pilih Buku      Qty      Hapus    │
│  [Dropdown]  [Input]  [Tombol]     │
│                                     │
│  + Tambah Buku                      │
│                                     │
│  [AJUKAN] [BATAL]                   │
│                                     │
└─────────────────────────────────────┘
```

### **Statistik & Riwayat**

```
Statistik:
- Total Peminjaman: X buku
- Sedang Dipinjam: X buku
- Selesai Dipinjam: X buku

Riwayat Terbaru:
| Buku | Tgl Pinjam | Tgl Kembali | Denda | Status | Aksi |
```

---

## 🔐 Keamanan

### **Authorization**
- ✅ Admin only: `/admin/*`
- ✅ Murid only: `/anggota/*`
- ✅ Guru only: `/guru/*`
- ✅ CSRF token di semua form
- ✅ Lock for update saat approve

### **Validasi**
- ✅ Server-side + Client-side
- ✅ Stok real-time check
- ✅ Duplikat buku prevention
- ✅ Limit 3 active borrowings
- ✅ Status enum validation

---

## 📱 Responsif Design

```
Desktop (1200px+)
│ Tabel lengkap | Statistics | Modal besar
│
Tablet (768px - 1199px)
│ Tabel stack | Statistics 2x2 | Modal medium
│
Mobile (< 768px)
│ Tabel scroll | Statistics stack | Modal full
│ Action buttons full width
```

---

## 🛠️ Customization

### **Durasi Peminjaman**
- File: `app/Http/Controllers/Admin/PeminjamanController.php`
- Line: ~330 (validate rule)
- Edit: `'min:1|max:30'` untuk change limit

### **Denda Per Hari**
- File: `app/Http/Controllers/Admin/PeminjamanController.php`
- Line: ~550 (calculateFine method)
- Edit: `* 5000` untuk change denda/hari

### **Limit Peminjaman Aktif**
- File: `app/Http/Controllers/ProfileAnggotaController.php`
- Line: ~75 (borrow method)
- Edit: `>= 3` untuk change limit

### **Warna & Styling**
- File: `resources/views/admin/peminjaman/index.blade.php`
- CSS classes: `.btn-approve`, `.btn-reject`, etc
- Edit color gradients & styling

---

## 🐛 Troubleshooting

### **Tombol tidak responsif**
```
Solution:
1. Refresh browser (Ctrl+F5)
2. Check browser console (F12)
3. Clear cache & cookies
4. Try different browser
```

### **Status tidak berubah**
```
Solution:
1. Refresh halaman
2. Check database directly
3. Check CSRF token valid
4. Check role/authorization
```

### **Stok tidak berkurang**
```
Solution:
1. Check migration ran
2. Check approval successful
3. Check database columns
4. Check Book model relationship
```

### **Denda salah**
```
Solution:
1. Check date format (Y-m-d)
2. Check timezone setting
3. Manual calculate: (hari - 7) × 5000
4. Check condition value (baik/rusak/hilang)
```

---

## 📊 Database

### **Key Tables**

**bookrent**
```sql
- id (PK)
- user_id (FK)
- borrow_date
- return_date
- status (enum)
- denda
- created_at, updated_at
```

**detail_bookrent**
```sql
- id (PK)
- bookrent_id (FK)
- book_id (FK)
- qty
- condition
```

---

## 🚀 Deployment

### **Pre-Deployment**
```bash
# Run migration
php artisan migrate

# Seed data (if needed)
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear

# Optimize
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

### **Post-Deployment**
```bash
# Monitor logs
tail -f storage/logs/laravel.log

# Check database
php artisan tinker
>>> Bookrent::count()
>>> DetailBookrent::count()
```

---

## 📞 Support

### **For Admin**
- 📖 Read: [PANDUAN_CEPAT_ADMIN.md](./PANDUAN_CEPAT_ADMIN.md)
- ⚙️ Technical: [IMPLEMENTASI_SUMMARY.md](./IMPLEMENTASI_SUMMARY.md)

### **For Murid/Guru**
- 📖 Read: [PANDUAN_MURID_GURU.md](./PANDUAN_MURID_GURU.md)

### **For Developer**
- 🔧 Technical: [IMPLEMENTASI_SUMMARY.md](./IMPLEMENTASI_SUMMARY.md)
- ✅ Testing: [TESTING_CHECKLIST.md](./TESTING_CHECKLIST.md)

---

## 🎓 Training Resources

### **Admin Training**
```
Duration: ~30 menit
Content:
- Interface walkthrough
- Approve workflow
- Reject workflow
- Return workflow
- Error handling
```

### **Murid/Guru Training**
```
Duration: ~15 menit
Content:
- Form submission
- Status tracking
- Return process
- FAQ
```

---

## 📈 Future Enhancements

- [ ] Email notification
- [ ] SMS reminder
- [ ] Monthly reports
- [ ] Excel export
- [ ] QR code tracking
- [ ] Mobile app
- [ ] Reward system
- [ ] Wishlist feature
- [ ] Reserve books
- [ ] Auto-fine calculation

---

## ✅ Production Checklist

- [x] Database migrations ✅
- [x] Models created ✅
- [x] Controllers implemented ✅
- [x] Routes configured ✅
- [x] Views created ✅
- [x] Validation added ✅
- [x] Security implemented ✅
- [x] Testing completed ✅
- [x] Documentation written ✅
- [x] Ready for deployment ✅

---

## 📝 Version Info

**Version:** 1.0  
**Status:** ✅ Production Ready  
**Last Updated:** 2024  
**License:** [Perpustakaan]  

---

## 🎉 Ready to Use!

Sistem sudah **production-ready** dan siap digunakan!

**Next Steps:**
1. ✅ Baca: [PANDUAN_CEPAT_ADMIN.md](./PANDUAN_CEPAT_ADMIN.md)
2. ✅ Training staff & users
3. ✅ Go live!
4. ✅ Monitor & support

---

**Selamat menggunakan sistem approval peminjaman buku! 📚✨**
