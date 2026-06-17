# 📚 PANDUAN MURID/GURU - CARA PINJAM BUKU

## 🎯 Tujuan Sistem

Memudahkan murid dan guru untuk:
- ✅ Mengajukan permintaan peminjaman buku
- ✅ Melihat status pengajuan
- ✅ Melacak riwayat peminjaman
- ✅ Mengembalikan buku

---

## 📋 LANGKAH-LANGKAH MENGAJUKAN PEMINJAMAN

### **STEP 1: Buka Halaman Profil**

```
Klik pada menu profile (biasanya di kanan atas)
atau pergi ke: /anggota/profil atau /guru/profil
```

**Apa yang Anda lihat:**
- 👤 Foto profil Anda
- 📊 Statistik peminjaman (Total, Sedang Dipinjam, Selesai)
- 📜 Riwayat peminjaman terbaru
- 📝 **Form Pinjam Buku** ← Mulai dari sini!

---

### **STEP 2: Isi Form Pinjam Buku**

#### **2.1 Pilih Buku Pertama**

```
Klik dropdown "-- Pilih Buku --"
Cari atau scroll buku yang ingin dipinjam

Format tampilan:
📖 Judul Buku (stok: X) - Stok Habis?
```

**Tips:**
- ✅ Hanya buku dengan stok > 0 bisa dipilih
- ⚠️ Stok Habis = buku tidak bisa dipilih
- 💡 Gunakan Ctrl+F untuk cari cepat

#### **2.2 Masukkan Jumlah**

```
Input field "Qty" (Jumlah)
Default: 1
Ubah sesuai kebutuhan
```

**Batasan:**
- Min: 1 buku
- Max: Tergantung stok
- ✅ Validasi otomatis jika lebih dari stok

#### **2.3 Tambah Buku Lagi (Opsional)**

```
Klik tombol "+ Tambah Buku"
Form baru akan muncul untuk pilihan buku kedua
Ulangi sampai semua buku terpilih
```

**Contoh Form:**
```
┌─ Buku 1 ─────┬─ Qty ─┬─ Hapus ─┐
│ Laskar Pelangi│  1   │  🗑️    │
├─ Buku 2 ─────┼─ Qty ─┼─ Hapus ─┤
│ Anak Semua    │  2   │  🗑️    │
├─ Buku 3 ─────┼─ Qty ─┼─ Hapus ─┤
│ Filosofi Pendidikan│ 1 │ 🗑️ │
└───────────────┴──────┴────────┘
```

#### **2.4 Hapus Buku (Jika Perlu)**

```
Klik tombol "🗑️ Hapus" pada buku yang tidak perlu
Form buku akan terhapus dari daftar
```

**Catatan:**
- Minimal harus ada 1 buku dalam form
- Tombol hapus hanya aktif jika ada > 1 buku

---

### **STEP 3: Ajukan Permintaan**

#### **Review Final**

```
Pastikan sebelum submit:
✅ Semua buku yang dipilih benar
✅ Jumlah sesuai kebutuhan
✅ Tidak ada duplikat buku yang sama
```

#### **Klik Tombol AJUKAN**

```
Tombol biru bertulisan "📤 Ajukan"
Klik untuk submit form
```

**Tunggu response:**
- ⏳ Sistem memproses...
- ✅ Success! → "Pengajuan peminjaman berhasil dikirim"
- ❌ Error → Baca pesan error dan perbaiki

---

## ✅ VALIDASI SISTEM

### **Akan Ditolak Jika:**

❌ **Meminjam buku yang sama lebih dari 1x**
```
Contoh error: "Tidak boleh memilih buku yang sama"
Solusi: Hapus duplikat, atau ubah quantity
```

❌ **Jumlah lebih dari stok yang tersedia**
```
Contoh error: "Stok buku hanya tersedia 2"
Solusi: Kurangi quantity sesuai stok
```

❌ **Sudah meminjam 3 buku aktif**
```
Contoh error: "Anda sudah meminjam 3 buku"
Solusi: Kembalikan 1 buku dulu, kemudian ajukan lagi
```

❌ **Sudah meminjam buku yang sama (status aktif)**
```
Contoh error: "Anda sudah meminjam buku Laskar Pelangi"
Solusi: Kembalikan buku dulu sebelum pinjam lagi
```

### **Akan Diterima Jika:**

✅ Semua validasi terpenuhi
✅ Buku belum dipinjam (atau sudah dikembalikan)
✅ Total peminjaman aktif < 3
✅ Stok tersedia

---

## 📊 MELIHAT STATUS PERMINTAAN

### **Lokasi: Tabel Riwayat Peminjaman**

Pada halaman Profil, lihat tabel dengan kolom:

```
| Judul Buku | Tgl Pinjam | Tgl Kembali | Denda | STATUS | Aksi |
```

### **Status Peminjaman Anda**

| Status | Warna | Arti |
|--------|-------|------|
| **Menunggu ACC** | 🟡 Kuning | Admin sedang review |
| **Dipinjam** | 🔵 Biru | Buku sudah diambil, sedang dipinjam |
| **Proses Pengembalian** | 🟡 Kuning | Anda submit pengembalian, menunggu konfirmasi admin |
| **Dikembalikan** | 🟢 Hijau | Buku sudah dikembalikan dengan sukses |
| **Ditolak** | 🔴 Merah | Admin menolak permintaan |

### **Apa Artinya?**

#### **🟡 MENUNGGU ACC**
```
❓ Apa ini?
Permintaan baru Anda sedang diperiksa admin

📌 Apa yang harus Anda lakukan?
Tunggu 1-2 hari kerja
Jika lama, hubungi admin

✅ Apa selanjutnya?
Status akan berubah menjadi "Dipinjam" atau "Ditolak"
```

#### **🔵 DIPINJAM**
```
❓ Apa ini?
Admin sudah approve, Anda perlu datang ke perpus untuk ambil buku

📌 Apa yang harus Anda lakukan?
Datang ke perpustakaan dengan nama Anda
Ambil buku ke petugas perpus
Catat tanggal batas kembali

⏰ Berapa lama boleh dipinjam?
Default 7 hari (bisa berbeda sesuai keputusan admin)

💰 Apakah ada denda?
- Tidak ada denda jika kembali tepat waktu
- Denda Rp 5.000/hari jika terlambat
- Denda Rp 50.000 jika buku rusak/hilang
```

#### **🟡 PROSES PENGEMBALIAN**
```
❓ Apa ini?
Anda sudah submit pengembalian, menunggu admin konfirmasi

📌 Apa yang harus Anda lakukan?
Datang ke perpus untuk serahkan buku
Tunggu admin konfirmasi

📋 Apa yang admin lakukan?
- Cek kondisi buku (baik/rusak/hilang)
- Hitung denda (jika ada)
- Update status ke "Dikembalikan"
```

#### **🟢 DIKEMBALIKAN**
```
✅ Artinya: Proses selesai dengan sukses!
📊 Info:
- Tanggal pengembalian tercatat
- Denda (jika ada) sudah dihitung
- Stok buku sudah dikembalikan
```

#### **🔴 DITOLAK**
```
❌ Artinya: Admin menolak permintaan Anda
🤔 Kemungkinan alasan:
- Stok tidak cukup
- Ada hambatan lainnya

💡 Solusi:
- Hubungi admin untuk konfirmasi alasan
- Ajukan kembali dengan pilihan buku berbeda
- Tunggu stok tersedia
```

---

## 🔄 CARA MENGEMBALIKAN BUKU

### **Step 1: Klik Tombol KEMBALIKAN**

**Lokasi:** Kolom "Aksi" pada buku yang sedang dipinjam

```
Status: DIPINJAM (Biru)
Tombol: "🔄 KEMBALIKAN" (Hijau)
Klik tombol tersebut
```

### **Step 2: Konfirmasi Pengembalian**

```
Dialog confirmation akan muncul:
"Yakin ingin mengembalikan buku ini?"

Klik "OK" untuk confirm
atau "BATAL" untuk cancel
```

### **Step 3: Status Berubah ke PROSES PENGEMBALIAN**

```
Status baru: "Proses Pengembalian" (Kuning)
Status lama: "Dipinjam" (Biru)

Artinya: Admin akan confirm pengembalian Anda
```

### **Step 4: Tunggu Konfirmasi Admin**

```
Admin akan:
1. Menerima buku dari Anda
2. Cek kondisi buku
3. Hitung denda (jika ada)
4. Ubah status ke "Dikembalikan"

Waktu: 1-2 hari kerja
```

### **Step 5: Selesai!**

```
Status: DIKEMBALIKAN (Hijau) ✅
Riwayat tercatat
Bisa pinjam buku lagi
```

---

## 📈 STATISTIK PEMINJAMAN ANDA

Pada profil, ada 3 kartu statistik:

```
┌─────────────────────────────────────────┐
│  📊 STATISTIK PEMINJAMAN ANDA           │
├─────────────────────────────────────────┤
│  📌 Total Peminjaman        : X buku    │
│     (Semua peminjaman sejak daftar)     │
│                                         │
│  📖 Sedang Dipinjam         : X buku    │
│     (Status DIPINJAM + PROSES KEMBALI)  │
│                                         │
│  ✅ Selesai Dipinjam        : X buku    │
│     (Status DIKEMBALIKAN)               │
└─────────────────────────────────────────┘
```

---

## 💡 TIPS & TRIK

### **✅ Tips Sukses Pinjam**

1. **Cek Stok Dulu**
   ```
   Sebelum ajukan, pastikan buku ada stok
   Lihat kolom "stok: X" di dropdown
   ```

2. **Jangan Pinjam Lebih dari 3**
   ```
   Limit 3 peminjaman aktif
   Kalau sudah 3, kembalikan 1 dulu
   ```

3. **Catat Tanggal Batas Kembali**
   ```
   Admin akan bilang tanggal kembali
   Pinjam default 7 hari
   Setelah itu ada denda Rp 5.000/hari
   ```

4. **Jaga Kondisi Buku**
   ```
   Buku rusak = Denda Rp 50.000
   Buku hilang = Denda Rp 50.000
   
   Solusi: Hati-hati saat baca
   ```

### **⚠️ Hal yang Harus Dihindari**

❌ Jangan pinjam buku yang sama 2x dalam form
❌ Jangan minta lebih dari stok yang ada
❌ Jangan terlambat kembalikan buku
❌ Jangan asal-asalan dengan kondisi buku

---

## ❓ PERTANYAAN UMUM (FAQ)

**Q: Berapa lama proses approval?**
A: Biasanya 1-2 hari kerja. Cek status di profil.

**Q: Bisa ubah jumlah setelah ajukan?**
A: Tidak, harus cancel dan ajukan ulang. Hubungi admin.

**Q: Bagaimana jika ingin pinjam lebih dari 3 buku?**
A: Maksimal 3 peminjaman aktif. Kembalikan 1 buku dulu.

**Q: Denda berapa jika terlambat?**
A: Rp 5.000 per hari setelah 7 hari peminjaman.

**Q: Bagaimana jika buku rusak?**
A: Denda Rp 50.000 + bayar ganti buku. Bilang ke admin.

**Q: Bisa pinjam buku yang sama lagi setelah kembalikan?**
A: Ya, bisa. Tunggu sampai status "DIKEMBALIKAN", baru ajukan lagi.

**Q: Dimana lihat daftar buku?**
A: Menu **Daftar Buku** atau di halaman profil.

---

## 📞 HUBUNGI ADMIN

Jika ada pertanyaan atau masalah:

```
👤 Nama Admin: [Nama Admin Perpustakaan]
📧 Email: [email admin]
📞 HP: [nomor admin]
📍 Lokasi: [Ruang Perpustakaan]
⏰ Jam Kerja: [Jam Kerja]
```

---

## 🎓 Video Tutorial (Jika Ada)

Tonton tutorial video cara:
- Mengajukan peminjaman
- Melihat status
- Mengembalikan buku

Link: [akan ditambahkan]

---

**Versi:** 1.0  
**Last Updated:** 2024  
**Selamat membaca! 📚✨**
