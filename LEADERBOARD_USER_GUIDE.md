# 🏆 Panduan Leaderboard Peminjam Buku

## Apa itu Leaderboard?

Leaderboard adalah sistem peringkat otomatis yang mencatat **siapa yang paling banyak meminjam buku** di perpustakaan. Sistem ini:

✅ **Otomatis Tercatat** - Setiap kali Anda meminjam atau mengembalikan buku
✅ **Terurut Otomatis** - Peminjam terbanyak selalu di peringkat atas
✅ **Real-time Update** - Data selalu fresh, tidak ada delay
✅ **Terlihat Dimana-mana** - Di dashboard anggota, halaman landing, dan halaman leaderboard khusus

---

## 📍 Tempat Leaderboard Bisa Dilihat

### 1️⃣ Dashboard Anggota/Guru
**Lokasi**: Setelah login, di halaman `/anggota/beranda` atau `/guru/beranda`

Anda bisa lihat:
- **Widget "🏆 Top Peminjam Buku"** dengan top 3 peminjam
- Jika Anda di top 3, akan highlight dengan background berbeda
- Update otomatis setiap 1 menit

### 2️⃣ Halaman Leaderboard Penuh
**Lokasi**: `/leaderboard`

Anda bisa akses:
- Tanpa login (public)
- Lihat top 50 peminjam terbanyak
- Lihat peringkat Anda sendiri (jika sudah login)
- Statistik: Total peserta, total peminjaman, peminjaman tertinggi
- Update otomatis setiap 30 detik

### 3️⃣ Halaman Landing/Homepage
**Lokasi**: `/` (homepage)

Bisa ada widget leaderboard yang menampilkan top 3 peminjam.

---

## 🎯 Cara Kerja Leaderboard

### Peminjaman Dihitung Jika:
✅ Status peminjaman = `dipinjam` (Buku sedang dipinjam)
✅ Status peminjaman = `kembali` (Buku sudah dikembalikan)

### Peminjaman TIDAK Dihitung Jika:
❌ Status = `menunggu_acc` (Masih menunggu persetujuan admin)
❌ Status = `ditolak` (Permintaan ditolak)
❌ Status = `proses_kembali` (Sedang proses pengembalian)

---

## 📊 Contoh Skenario

### Skenario 1: Meminjam Buku (Admin approve)
```
WAKTU 10:00 - Anda mengajukan peminjaman buku "Harry Potter"
└─> Status: menunggu_acc
└─> Leaderboard: TIDAK DIHITUNG ❌

WAKTU 10:15 - Admin approve peminjaman
└─> Status: dipinjam
└─> Leaderboard: DIHITUNG ✅ (total_peminjaman +1)
└─> Anda muncul di leaderboard dengan 1 peminjaman

WAKTU 10:30 - Anda cek leaderboard
└─> Leaderboard: Menampilkan Anda di peringkat tertentu
```

### Skenario 2: Mengembalikan Buku
```
WAKTU 14:00 - Anda mengembalikan buku "Harry Potter"
└─> Status: proses_kembali
└─> Leaderboard: TETAP DIHITUNG (status sebelumnya 'dipinjam')

WAKTU 14:10 - Admin confirm return
└─> Status: kembali
└─> Leaderboard: TETAP DIHITUNG ✅ (total_dikembalikan +1)
└─> Anda tetap di peringkat yang sama
└─> Total peminjaman: 1, Dikembalikan: 1
```

### Skenario 3: Permintaan Ditolak
```
WAKTU 11:00 - Anda mengajukan peminjaman buku
└─> Status: menunggu_acc
└─> Leaderboard: TIDAK DIHITUNG ❌

WAKTU 11:20 - Admin REJECT peminjaman
└─> Status: ditolak
└─> Leaderboard: TIDAK DIHITUNG ❌
└─> Tidak mempengaruhi ranking Anda
```

---

## 🔍 Bagaimana Ranking Ditentukan?

Ranking ditentukan oleh **TOTAL PEMINJAMAN** (baik yang sedang dipinjam maupun sudah dikembalikan).

### Contoh Data Leaderboard:
```
Rank  | Nama             | Peminjaman | Dikembalikan | Status
------|------------------|------------|-------------|--------
1 🥇  | Ahmad Ridho      | 15         | 10          | Top Performer
2 🥈  | Siti Nurhaliza   | 12         | 8           | Top Performer
3 🥉  | Budi Santoso     | 10         | 7           | Top Performer
4     | Diana Kusuma     | 8          | 5           | Aktif
5     | Eka Putra        | 6          | 4           | Aktif
```

**Ahmad Ridho** di rank 1 karena dia punya **15 total peminjaman** (paling banyak).

---

## 🚀 Fitur Leaderboard

### 🎖️ Medal Badges
- 🥇 **Gold** - Rank 1 (Peminjam Terbanyak)
- 🥈 **Silver** - Rank 2 (Peminjam Terbanyak Kedua)
- 🥉 **Bronze** - Rank 3 (Peminjam Terbanyak Ketiga)

### 👤 Role Badges
- **Anggota** - Badge biru (murid/siswa)
- **Guru** - Badge ungu (tenaga pendidik)

### 📈 Progress Bar
- Menunjukkan persentase peminjaman Anda vs peminjam terbanyak
- Semakin panjang bar = semakin banyak peminjaman

### ⏰ Auto Update
- **Dashboard Widget**: Update setiap 1 menit
- **Leaderboard Page**: Update setiap 30 detik
- **Data Real-time**: Automatic fetch dari server

---

## 💡 Tips untuk Naik Ranking

1. **Pinjam Buku Lebih Banyak** - Setiap peminjaman yang disetujui akan dihitung
2. **Kembalikan Tepat Waktu** - Status berubah dari `dipinjam` → `kembali`, tapi tetap dihitung
3. **Hindari Ditolak** - Permintaan yang ditolak tidak akan dihitung
4. **Rutin Meminjam** - Semakin sering meminjam = ranking semakin tinggi

---

## 🎯 Leaderboard API (Untuk Developer)

Jika Anda ingin integrate leaderboard ke aplikasi lain:

### Endpoint 1: Get Live Data (Top 10)
```
GET /leaderboard/live
```

**Response**:
```json
{
  "updated_at": "17 Jun 2026 14:30:45",
  "total_peserta": 25,
  "total_peminjaman": 150,
  "peminjaman_tertinggi": 15,
  "items": [
    {
      "id": 1,
      "nama": "Ahmad Ridho",
      "role": "Anggota",
      "total_peminjaman": 15,
      "total_dikembalikan": 10
    }
  ]
}
```

### Endpoint 2: Get Top 3 (Widget)
```
GET /leaderboard/top3
```

**Response**:
```json
{
  "items": [
    {
      "rank": 1,
      "nama": "Ahmad Ridho",
      "total_peminjaman": 15,
      "medal": "🥇"
    }
  ]
}
```

---

## ❓ FAQ (Pertanyaan Umum)

### Q: Berapa lama data leaderboard ter-update?
**A**: Real-time! Setiap kali admin confirm peminjaman/pengembalian, leaderboard otomatis terupdate.

### Q: Apakah admin bisa masuk leaderboard?
**A**: Tidak. Admin di-exclude dari leaderboard. Hanya Anggota dan Guru yang ter-ranking.

### Q: Bagaimana jika ada 2 orang dengan total peminjaman sama?
**A**: Mereka akan di-sort berdasarkan nama (A-Z).

### Q: Apakah pengembalian buku mempengaruhi ranking?
**A**: Tidak. Ranking tetap sama karena berdasarkan TOTAL PEMINJAMAN. Baik yang dipinjam atau dikembalikan, tetap dihitung.

### Q: Bisa kah saya naik ranking dengan cepat?
**A**: Bisa! Semakin banyak Anda meminjam buku (dan admin approve), semakin cepat ranking naik. Pastikan permintaan Anda tidak ditolak.

### Q: Apakah data leaderboard tersimpan permanen?
**A**: Ya! Data tersimpan di database dan tidak akan hilang.

---

## 🔐 Privacy & Security

✅ Nama dan ranking publik (bisa dilihat siapa saja)
✅ Data personal (email, alamat) TIDAK ditampilkan di leaderboard
✅ Hanya total peminjaman yang ditampilkan
✅ Data tidak bisa di-manipulasi oleh user biasa

---

## 📞 Bantuan & Support

Jika leaderboard tidak menampilkan data atau ada masalah:

1. **Refresh halaman** (Ctrl+F5)
2. **Clear browser cache** (Ctrl+Shift+Delete)
3. **Cek koneksi internet** - Pastikan online
4. **Report ke admin** jika masih error

---

**Last Updated**: 17 Jun 2026
**Status**: ✅ Ready to Use

Selamat berkompetisi dan banyak membaca! 📚📊🏆
