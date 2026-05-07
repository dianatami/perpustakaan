# Dokumentasi Halaman Anggota / Customer

## Daftar Fitur yang Telah Dibuat

### 1. **Admin Dashboard - Manajemen Anggota**
Lokasi: `/admin/anggota`

#### Views:
- **Anggota Index** (`resources/views/admin/user/anggota.blade.php`)
  - Menampilkan daftar semua anggota dengan pagination
  - Fitur: Edit, Ubah Status, Hapus anggota
  - Tombol: Tambah Anggota Baru

- **Create Anggota** (`resources/views/admin/user/create.blade.php`)
  - Form untuk menambah anggota baru
  - Validasi: Nama, Email (unique), No. HP, Password

- **Edit Anggota** (`resources/views/admin/user/edit.blade.php`)
  - Form untuk mengedit data anggota
  - Fitur: Ubah nama, email, HP, password, dan status

#### Routes (Admin):
```
GET    /admin/anggota                  - admin.anggota.index        (Daftar anggota)
GET    /admin/anggota/create           - admin.anggota.create       (Form tambah)
POST   /admin/anggota                  - admin.anggota.store        (Simpan anggota)
GET    /admin/anggota/{id}/edit        - admin.anggota.edit         (Form edit)
PUT    /admin/anggota/{id}             - admin.anggota.update       (Simpan edit)
DELETE /admin/anggota/{id}             - admin.anggota.destroy      (Hapus)
PUT    /admin/anggota/{id}/status      - admin.anggota.toggleStatus (Ubah status)
```

---

### 2. **Anggota Dashboard - Profile & Data Pribadi**
Lokasi: `/anggota` (dengan middleware auth)

#### Views:
- **Dashboard Anggota** (`resources/views/anggota/dashboard.blade.php`)
  - Halaman selamat datang untuk anggota
  - Menampilkan greeting dan role user

- **Profil Anggota** (`resources/views/anggota/profil.blade.php`)
  - Menampilkan profil lengkap anggota
  - Informasi: Nama, Email, No. HP, Tanggal Terdaftar, Status
  - Tombol: Edit Profil, Ubah Password, Kembali

- **Edit Profil** (`resources/views/anggota/edit-profil.blade.php`)
  - Form untuk mengubah data profil
  - Fitur upload foto profil (JPG, PNG, GIF, max 2MB)
  - Field: Nama, Email, No. HP, Foto

- **Ubah Password** (`resources/views/anggota/ubah-password.blade.php`)
  - Form untuk mengubah password
  - Validasi: Password lama harus sesuai
  - Field: Password Lama, Password Baru, Konfirmasi Password Baru

- **Riwayat Peminjaman** (`resources/views/anggota/riwayat-peminjaman.blade.php`)
  - Menampilkan daftar semua peminjaman anggota
  - Informasi: Nama Buku, Tanggal Pinjam, Tanggal Kembali, Status, Denda
  - Aksi pengembalian buku untuk siswa dan guru
  - Status mengikuti alur approval admin (Menunggu ACC → Dipinjam → Proses Pengembalian → Sudah Dikembalikan)

#### Routes (Anggota):
```
GET    /anggota/profil                 - anggota.profil             (Lihat profil)
GET    /anggota/profil/edit            - anggota.edit.profil        (Form edit profil)
PUT    /anggota/profil                 - anggota.update.profil      (Simpan edit profil)
GET    /anggota/ubah-password          - anggota.ubah.password      (Form ubah password)
PUT    /anggota/ubah-password          - anggota.store.password     (Simpan password)
GET    /anggota/riwayat-peminjaman     - anggota.riwayat.peminjaman (Riwayat peminjaman)
POST   /anggota/pinjam/{book}          - anggota.pinjam             (Ajukan peminjaman)
POST   /anggota/pengembalian/{bookrent} - anggota.pengembalian.store (Ajukan pengembalian)
```

#### Routes (Admin Approval):
```
PUT    /admin/peminjaman/{peminjaman}/approve        - admin.peminjaman.approve (ACC peminjaman)
PUT    /admin/peminjaman/{peminjaman}/reject         - admin.peminjaman.reject (Tolak peminjaman)
PUT    /admin/peminjaman/{peminjaman}/confirm-return - admin.peminjaman.confirm-return (Konfirmasi pengembalian)
```

---

### 3. **Controller**

#### AnggotaController (`app/Http/Controllers/AnggotaController.php`)
- `index()` - Menampilkan daftar anggota
- `create()` - Tampilkan form tambah anggota
- `store()` - Simpan anggota baru
- `edit()` - Tampilkan form edit anggota
- `update()` - Update data anggota
- `destroy()` - Hapus anggota
- `toggleStatus()` - Ubah status anggota (aktif/nonaktif)

#### ProfileAnggotaController (`app/Http/Controllers/ProfileAnggotaController.php`)
- `profil()` - Tampilkan halaman profil
- `editProfil()` - Tampilkan form edit profil
- `updateProfil()` - Update profil anggota
- `ubahPassword()` - Tampilkan form ubah password
- `storePassword()` - Update password anggota
- `riwayatPeminjaman()` - Tampilkan riwayat peminjaman
- `borrow()` - Mengajukan peminjaman buku (status awal `menunggu_acc`)
- `returnBook()` - Mengajukan pengembalian buku (status `proses_kembali`)

---

### 4. **Model**

#### User Model (`app/Models/User.php`)
- Table: `user`
- Fillable fields: nama, email, password, hp, role, status, foto
- Role: 0 = Anggota, 1 = Admin
- Status: 1 = Aktif, 0 = Nonaktif

---

### 5. **Layout**

#### Layout Admin (`resources/views/layout/admin.blade.php`)
- Sidebar navigation dengan link ke manajemen anggota
- Responsive design untuk mobile
- Include Font Awesome & Bootstrap Icons

#### Layout Anggota (`resources/views/layout/anggota.blade.php`)
- Sidebar navigation dengan link profil dan riwayat peminjaman
- Responsive design untuk mobile
- Include Font Awesome & Bootstrap Icons

---

## Fitur Keamanan

✅ **Authentication Middleware** - Routes anggota dilindungi dengan middleware `auth`
✅ **Authorization** - Admin hanya bisa mengakses halaman admin
✅ **Validasi Input** - Semua form memiliki validasi server-side
✅ **CSRF Protection** - Semua form menggunakan token CSRF
✅ **Password Hashing** - Password di-hash dengan bcrypt
✅ **Email Unique** - Email anggota harus unik di database

---

## Cara Penggunaan

### Admin - Mengelola Anggota:
1. Login sebagai admin
2. Klik menu "Anggota" di sidebar
3. Lihat daftar anggota, tambah baru, edit, atau hapus

### Anggota - Kelola Profil:
1. Login sebagai anggota
2. Klik menu "Profil Saya" di sidebar
3. Lihat profil, edit data, atau ubah password

### Anggota - Lihat Riwayat Peminjaman:
1. Login sebagai anggota
2. Klik menu "Riwayat Peminjaman" di sidebar
3. Lihat daftar semua peminjaman dengan status dan denda

---

## Database Schema (User Table)

```sql
CREATE TABLE user (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('0', '1') DEFAULT '0',
    status BOOLEAN,
    password VARCHAR(255) NOT NULL,
    hp VARCHAR(13) NOT NULL,
    foto VARCHAR(255) NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

- role: 0 = Anggota, 1 = Admin
- status: 1 = Aktif, 0 = Nonaktif

---

## File yang Dibuat/Dimodifikasi

### File Baru:
- `resources/views/anggota/profil.blade.php`
- `resources/views/anggota/edit-profil.blade.php`
- `resources/views/anggota/ubah-password.blade.php`
- `resources/views/anggota/riwayat-peminjaman.blade.php`
- `app/Http/Controllers/ProfileAnggotaController.php`

### File Dimodifikasi:
- `routes/web.php` - Tambah routes untuk anggota
- `app/Models/User.php` - Update fillable fields
- `resources/views/layout/anggota.blade.php` - Update menu sidebar
- `resources/views/layout/admin.blade.php` - Tambah Font Awesome CDN
