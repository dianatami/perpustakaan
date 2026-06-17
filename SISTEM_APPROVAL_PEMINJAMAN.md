# 📚 Sistem Approval Peminjaman Buku

## 🎯 Ringkasan Fitur

Sistem ini memungkinkan **admin** untuk menerima atau menolak permintaan peminjaman buku dari **murid dan guru**. Proses approval lengkap dengan notifikasi status dan manajemen peminjaman yang komprehensif.

---

## 📊 Status Peminjaman

| Status | Deskripsi | Aksi yang Tersedia |
|--------|-----------|-------------------|
| **menunggu_acc** | Pengajuan baru menunggu persetujuan admin | ✅ Setujui / ❌ Tolak |
| **dipinjam** | Sudah disetujui, buku sedang dipinjam | 📝 Edit / 📦 Terima Pengembalian |
| **proses_kembali** | Murid melakukan pengembalian buku | 📋 Proses Pengembalian |
| **kembali** | Peminjaman selesai, buku sudah dikembalikan | ✓ Selesai |
| **ditolak** | Permintaan ditolak oleh admin | - |

---

## 🔄 Alur Proses Peminjaman

### **1️⃣ Murid/Guru Mengajukan Peminjaman**

**Lokasi:** Halaman Profil → Riwayat Peminjaman → Pinjam Buku

```
1. Pilih buku yang ingin dipinjam
2. Masukkan jumlah buku
3. Klik tombol "Ajukan"
4. Status berubah menjadi "Menunggu ACC"
```

**Batasan:**
- Maksimal 3 buku aktif bersamaan
- Tidak boleh meminjam buku yang sama dua kali
- Stok harus tersedia

---

### **2️⃣ Admin Menerima/Menolak Pengajuan**

**Lokasi:** Admin Dashboard → Kelola Peminjaman

#### **✅ Menerima Pengajuan**

1. Klik tombol **"Setujui"** pada baris pengajuan
2. Tentukan **lama peminjaman** (1-30 hari)
3. Sistem otomatis menghitung **tanggal pengembalian**
4. Klik **"Konfirmasi Persetujuan"**
5. Murid akan diminta datang ke perpustakaan untuk mengambil buku

**Fitur Unggulan:**
- 📅 Preview tanggal pengembalian
- 🔒 Validasi stok otomatis
- 📌 Persetujuan bersamaan untuk multiple items

#### **❌ Menolak Pengajuan**

1. Klik tombol **"Tolak"** pada baris pengajuan
2. Konfirmasi penolakan
3. Status berubah menjadi "Ditolak"
4. Stok buku tidak berkurang

---

### **3️⃣ Murid Mengambil Buku**

Setelah persetujuan admin, murid harus datang ke perpustakaan untuk mengambil buku secara fisik.

---

### **4️⃣ Pengembalian Buku**

**Dari Sisi Murid:**
1. Klik tombol **"Kembalikan"** pada buku yang sedang dipinjam
2. Status berubah menjadi "Proses Pengembalian"

**Dari Sisi Admin:**
1. Halaman Peminjaman → Cari status "Proses Pengembalian"
2. Klik **"Terima Pengembalian"**
3. Tentukan kondisi buku:
   - ✅ **Baik** → Stok +1
   - ⚠️ **Rusak** → Denda Rp 50.000
   - ❌ **Hilang** → Denda Rp 50.000
4. Hitung denda jika terlambat:
   - Periode peminjaman: 7 hari
   - Denda terlambat: Rp 5.000/hari

---

## 🎛️ Fitur Filter & Pencarian Admin

### **Filter Status**
Dropdown untuk menampilkan peminjaman berdasarkan status:
- ⏳ Menunggu Persetujuan
- ✅ Sudah Disetujui
- 📦 Proses Pengembalian
- ✓ Sudah Dikembalikan
- ❌ Ditolak

### **Pencarian Real-time**
Cari berdasarkan:
- 👤 Nama murid/guru
- 📖 Judul buku
- 📊 Status peminjaman

---

## 📈 Dashboard Admin - Statistik

Halaman Kelola Peminjaman menampilkan:
- 📌 **Menunggu Persetujuan** → Jumlah request baru
- ✅ **Sudah Disetujui** → Buku yang sedang dipinjam
- ❌ **Ditolak** → Total penolakan
- 📊 **Total Peminjaman** → Semua riwayat

---

## 💰 Perhitungan Denda

### **Denda Keterlambatan**
```
Jika (hari pengembalian - 7 hari) > 0:
   Denda = (selisih hari) × Rp 5.000
Else:
   Denda = 0
```

### **Denda Kerusakan/Kehilangan**
```
Kondisi Rusak / Hilang = Rp 50.000 per buku
```

### **Total Denda**
```
Total = Denda Keterlambatan + Denda Kerusakan
```

---

## 📋 Validasi Sistem

### **Saat Mengajukan Peminjaman**
- ✅ User hanya bisa meminjam buku dengan stok > 0
- ✅ Tidak boleh meminjam buku yang sudah dipinjam (status aktif)
- ✅ Maksimal 3 peminjaman aktif sekaligus
- ✅ Tidak boleh pilih buku yang sama dalam satu form

### **Saat Menyetujui Peminjaman**
- ✅ Validasi stok real-time
- ✅ Durasi peminjaman 1-30 hari
- ✅ Tanggal pengembalian otomatis terhitung

### **Saat Penerimaan Pengembalian**
- ✅ Kondisi buku tersimpan (Baik/Rusak/Hilang)
- ✅ Denda otomatis dihitung
- ✅ Stok buku dipulihkan sesuai kondisi

---

## 🔔 Notifikasi & Informasi

| Kejadian | Pesan | Warna |
|---------|-------|-------|
| Persetujuan Sukses | "Pengajuan peminjaman berhasil disetujui" | ✅ Hijau |
| Penolakan Sukses | "Peminjaman berhasil ditolak" | ❌ Merah |
| Stok Tidak Cukup | "Stok buku hanya tersedia X" | ⚠️ Kuning |
| Peminjaman Terlambat | "Denda: Rp X.XXX" | 💰 Merah |

---

## 🎨 Warna Status Badge

| Status | Warna | Keterangan |
|--------|-------|-----------|
| Menunggu ACC | 🟡 Kuning | Perlu tindakan |
| Disetujui | 🔵 Biru | Aktif/Berjalan |
| Proses Kembali | 🟡 Kuning | Perlu konfirmasi |
| Dikembalikan | 🟢 Hijau | Selesai |
| Ditolak | 🔴 Merah | Tertolak |

---

## 🛠️ Tips Admin

### **Manajemen Efisien**
1. Cek statistik di dashboard untuk prioritas approval
2. Filter "Menunggu Persetujuan" untuk lihat request baru
3. Gunakan search untuk menemukan peminjaman spesifik
4. Tentukan durasi sesuai kebutuhan murid (default 7 hari)

### **Penanganan Kerusakan Buku**
1. Dokumentasikan kondisi buku saat pengembalian
2. Catat denda sesuai tingkat kerusakan
3. Update stok untuk buku yang hilang/rusak

### **Monitoring**
1. Review peminjaman terlambat secara berkala
2. Follow-up dengan murid yang belum mengembalikan
3. Verifikasi denda sesuai perhitungan otomatis

---

## 📱 Interface untuk Murid/Guru

### **Halaman Profil**
- ✅ Form pinjam buku dengan dropdown pilihan
- 📊 Statistik peminjaman (Total, Sedang Dipinjam, Selesai)
- 📜 Riwayat peminjaman 5 item terbaru
- 🔄 Tombol "Kembalikan" untuk buku yang sedang dipinjam

### **Status Pengajuan**
- ⏳ Menunggu ACC
- ✅ Dipinjam
- ✓ Dikembalikan
- ❌ Ditolak

---

## 🔐 Keamanan & Validasi

- 🔒 CSRF token pada semua form
- 🔐 User hanya bisa melihat peminjaman miliknya
- 🛡️ Admin role check di setiap aksi
- 📝 Lock transaksi untuk mencegah race condition

---

## 📊 Database Schema

### **Tabel bookrent**
```sql
- id (PK)
- user_id (FK) → user.id
- borrow_date (date)
- return_date (date)
- status (enum)
- denda (int) - dalam rupiah
- created_at
- updated_at
```

### **Tabel detail_bookrent**
```sql
- id (PK)
- bookrent_id (FK) → bookrent.id
- book_id (FK) → books.id
- qty (int)
- condition (enum: baik, rusak, hilang)
```

---

## 🚀 Fitur Tambahan yang Dapat Dikembangkan

- 📧 Notifikasi email saat approval
- 📱 Notifikasi SMS ke murid
- 📅 Kalender visual untuk tanggal pengembalian
- 📊 Laporan peminjaman per bulan
- 🏆 Statistik murid paling rajin membaca
- ⏰ Reminder otomatis untuk pengembalian

---

**Versi:** 1.0  
**Last Updated:** {{ date('Y-m-d') }}  
**Status:** ✅ Production Ready
