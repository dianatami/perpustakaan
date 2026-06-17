# 📌 PANDUAN CEPAT ADMIN - APPROVAL PEMINJAMAN BUKU

## ⚡ Quick Start (3 Langkah)

### **MENERIMA PERMINTAAN PEMINJAMAN**

#### Step 1: Buka Kelola Peminjaman
- Klik: **Admin Dashboard** → **Kelola Peminjaman**
- Lihat statistics: Berapa banyak permintaan menunggu ACC

#### Step 2: Lihat Permintaan yang Menunggu
```
Gunakan dropdown filter:
📍 Pilih "Menunggu Persetujuan"
📍 Lihat daftar dengan tombol HIJAU "Setujui"
```

#### Step 3: Setujui Permintaan
```
1️⃣ Klik tombol "SETUJUI" (hijau)
2️⃣ Modal muncul → tentukan durasi (contoh: 7 hari)
3️⃣ Preview tanggal kembali otomatis update
4️⃣ Klik "KONFIRMASI PERSETUJUAN"
5️⃣ Status berubah → Murid datang ambil buku
```

---

## 🎯 Skenario Penggunaan

### **SKENARIO 1: Murid Mengajukan Pinjam 3 Buku**

**Status:** Menunggu ACC (KUNING)

**Action:**
- Klik "SETUJUI"
- Tentukan durasi: 7 hari
- Tanggal kembali: 7 hari dari hari ini
- Klik "KONFIRMASI"

**Hasil:**
- ✅ Status berubah → DIPINJAM (BIRU)
- ✅ Stok buku berkurang 3
- ✅ Murid notifikasi untuk datang ke perpus

---

### **SKENARIO 2: Stok Buku Tidak Cukup**

**Kondisi:**
- Murid minta 5 buku
- Stok hanya 3

**Sistem akan:**
- ❌ Tampilkan error: "Stok hanya tersedia 3"
- 🔄 Tombol "SETUJUI" akan error jika dipaksa
- 💡 Admin bisa tolak atau komunikasi dengan murid

**Action:**
- Klik "TOLAK" atau hubungi murid
- Atau arahkan murid kurangi jumlah buku

---

### **SKENARIO 3: Pengembalian Buku**

**A. Dari Sisi Murid:**
```
1. Buka profil
2. Lihat "Sedang Dipinjam"
3. Klik "KEMBALIKAN"
4. Status → PROSES PENGEMBALIAN (KUNING)
```

**B. Dari Sisi Admin:**
```
1. Filter: "Proses Pengembalian"
2. Klik "TERIMA PENGEMBALIAN"
3. Pilih kondisi buku:
   ✅ Baik → Rp 0
   ⚠️ Rusak → Rp 50.000
   ❌ Hilang → Rp 50.000
4. Jika terlambat, denda otomatis terhitung
5. Klik SUBMIT
6. Status → KEMBALI (HIJAU)
```

---

## 🚨 Kondisi PENTING

### **JANGAN SETUJUI JIKA:**

❌ **Stok tidak cukup**
- Sistem akan reject otomatis
- Tampilkan error di popup

❌ **Format durasi tidak valid**
- Hanya terima 1-30 hari
- Default: 7 hari

❌ **Status bukan "menunggu_acc"**
- Contoh: Sudah "DITOLAK" → tidak bisa disetujui lagi
- Jika perlu, EDIT untuk ubah status

### **SELALU LAKUKAN:**

✅ **Validasi sebelum approve:**
```
1. Cek stok tersedia?
2. Cek user valid (murid/guru)?
3. Cek tidak ada duplikat peminjaman?
```

✅ **Dokumentasi kondisi buku saat kembali:**
```
- Baik? (Kembalikan stok)
- Rusak? (Denda + catat lokasi rusak)
- Hilang? (Denda + laporkan)
```

---

## 📊 Dashboard Statistics

```
┌─────────────────────────────────────────┐
│   KELOLA PEMINJAMAN - STATISTICS        │
├─────────────────────────────────────────┤
│  ⏳ Menunggu Persetujuan    : X item    │
│  ✅ Sudah Disetujui         : X item    │
│  ❌ Ditolak                 : X item    │
│  📊 Total Peminjaman        : X item    │
└─────────────────────────────────────────┘
```

**Gunakan untuk:**
- 🎯 Prioritas kerja harian
- 📈 Monitoring performa
- 📌 Reminder ada item pending

---

## 🔧 Troubleshooting

### **Masalah: Tombol SETUJUI tidak responsif**
```
✓ Refresh halaman
✓ Cek stok buku sudah update?
✓ Cek internet connection
✓ Coba browser lain (Firefox/Chrome)
```

### **Masalah: Error "Stok tidak cukup" padahal ada stok**
```
✓ Stok berkurang karena peminjaman aktif lain
✓ Cek kolom "stock" di inventory buku
✓ Mungkin buku rusak/hilang di peminjaman sebelumnya
```

### **Masalah: Denda tidak terhitung**
```
✓ Pastikan tanggal kembali lebih besar dari tanggal pinjam
✓ Jika hilang/rusak: denda Rp 50.000 + keterlambatan
✓ Denda otomatis: Rp 5.000/hari setelah 7 hari
```

---

## 💡 TIPS & TRIK

### **⚡ Cepat Approve Batch**
1. Filter "Menunggu Persetujuan"
2. Approve satu per satu dengan durasi sama
3. Refresh untuk melihat status update

### **🔍 Cari Peminjaman Spesifik**
```
Gunakan search box:
- Nama murid: "Ahmad"
- Judul buku: "Laskar Pelangi"
- Status: "menunggu"
```

### **📋 Monitoring Terlambat**
```
1. Filter: "Dipinjam"
2. Cek return_date sudah lewat
3. Hubungi murid untuk pengembalian
4. Ingatkan tentang denda
```

---

## ✨ Fitur Button

| Tombol | Warna | Aksi | Hasil |
|--------|-------|------|-------|
| **SETUJUI** | 🟢 Hijau | Approve permintaan | Status → DIPINJAM |
| **TOLAK** | 🔴 Merah | Reject permintaan | Status → DITOLAK |
| **TERIMA PENGEMBALIAN** | 🟠 Orange | Terima buku kembali | Status → KEMBALI |
| **EDIT** | 🟡 Kuning | Ubah data peminjaman | Buka form edit |

---

## 📱 Mobile Access

Sistem responsive untuk:
- ✅ Desktop (Optimal)
- ✅ Tablet
- ✅ Mobile (tombol menjadi full width)

---

## 🔐 Hak Akses

Hanya **Admin** (Role = 1) yang bisa:
- ✅ Lihat daftar peminjaman
- ✅ Approve/Reject permintaan
- ✅ Edit data peminjaman
- ✅ Konfirmasi pengembalian
- ✅ Hitung denda

**Murid/Guru** bisa:
- ✅ Mengajukan peminjaman
- ✅ Lihat status permintaan mereka
- ✅ Kembalikan buku

---

## 📞 Support & FAQ

**Q: Berapa lama waktu maximum peminjaman?**
A: Max 30 hari, default 7 hari, min 1 hari

**Q: Apakah bisa ubah status setelah approve?**
A: Ya, buka halaman EDIT untuk ubah

**Q: Bagaimana jika buku hilang?**
A: Mark kondisi "Hilang" → Denda Rp 50.000

**Q: Apakah ada notifikasi ke murid?**
A: Notifikasi melalui sistem (in-app), email otomatis dapat dikembangkan

---

**💼 Admin Dashboard:** `/admin/beranda`  
**📚 Kelola Peminjaman:** `/admin/peminjaman`  
**📊 Laporan:** Dikembangkan di versi berikutnya

**Versi:** 1.0  
**Last Updated:** 2024
