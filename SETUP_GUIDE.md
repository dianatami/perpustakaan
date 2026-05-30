# 📚 Panduan Setup Perpustakaan Sekolah

Panduan lengkap untuk setup dan menjalankan aplikasi Perpustakaan Sekolah secara lokal.

## ✅ Requirements

- PHP 8.1+ dengan extensions: mysql, pdo, ctype, fileinfo, json, mbstring, openssl, tokenizer, xml
- MySQL/MariaDB 5.7+
- Composer 2.0+
- Node.js 14+ (untuk asset compilation)
- Git

## 🚀 Quick Start

### 1. Clone Repository

```bash
git clone https://github.com/dianatami/perpustakaan.git
cd perpustakaan
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit `.env` dan sesuaikan dengan database lokal:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Jika menggunakan Laragon, biasanya:
- Host: `127.0.0.1`
- Database: `perpustakaan` (buat terlebih dahulu atau biarkan migration mengbuatnya)
- Username: `root`
- Password: kosong

### 5. Run Migrations

```bash
php artisan migrate
```

**Alternatif (jika ada error pada migration):**

```bash
# Fresh migration (reset database)
php artisan migrate:fresh

# Seed database (jika ada)
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server

```bash
php artisan serve --host 127.0.0.1 --port 8000
```

Buka browser: `http://127.0.0.1:8000`

---

## 📋 Test Akun

Setelah setup selesai, gunakan akun berikut untuk login:

### Admin
- **Email**: `admin@smkn1tirtamulya.sch.id`
- **Password**: `password`

### Member/Siswa
- **Email**: `siswa@smkn1tirtamulya.sch.id`
- **Password**: `password`

### Guru
- **Email**: `guru@smkn1tirtamulya.sch.id`
- **Password**: `password`

### Kepala Sekolah
- **Email**: `kepala@smkn1tirtamulya.sch.id`
- **Password**: `password`

---

## 🛠️ Troubleshooting

### Error: "Call to undefined method..."
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Error: SQLSTATE[HY000]: General error
Pastikan database sudah dibuat dan migration sudah dijalankan:
```bash
php artisan migrate:reset
php artisan migrate
```

### Error: "No such file or directory" saat npm run
```bash
npm install
npm run dev
```

### Port 8000 sudah terpakai
```bash
php artisan serve --port 8001
# atau port lain yang belum terpakai
```

---

## 📁 Struktur Project

```
perpustakaan/
├── app/
│   ├── Http/
│   │   └── Controllers/      # Controller untuk semua route
│   ├── Models/               # Eloquent models
│   └── ...
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── views/                # Blade templates
│   ├── css/                  # Custom CSS
│   └── js/                   # Custom JavaScript
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
├── .env.example             # Environment template
├── composer.json            # PHP dependencies
├── package.json             # Node dependencies
└── README.md                # Project documentation
```

---

## 🔄 Git Workflow

### Pull Latest Changes
```bash
git pull origin main
```

### Jika ada perubahan database (migrations)
```bash
git pull origin main
composer install
npm install
php artisan migrate
npm run dev
```

### Commit dan Push Changes
```bash
# Lihat perubahan
git status

# Add changes
git add .

# Commit dengan pesan deskriptif
git commit -m "feat: deskripsi fitur yang ditambahkan"

# Push ke repository
git push origin main
```

---

## 📚 Fitur Utama

### Admin Panel
- ✅ Dashboard dengan statistik real-time
- ✅ Manajemen Data Buku (Create, Read, Update, Delete)
- ✅ Manajemen Kategori Buku
- ✅ Manajemen Rak/Shelf untuk tata letak buku
- ✅ Manajemen Peminjaman & Pengembalian
- ✅ Manajemen Member/Siswa
- ✅ Laporan dan Analitik

### Member Portal
- ✅ Browse dan cari buku
- ✅ Filter berdasarkan kategori dan rak
- ✅ Lihat detail buku
- ✅ Ajukan peminjaman
- ✅ Track status peminjaman
- ✅ Manage profil personal

### Guru Portal
- ✅ Monitor pola baca peserta didik
- ✅ Rekomendasi buku untuk kelas
- ✅ Analytics literasi

### Kepala Sekolah
- ✅ Executive dashboard
- ✅ Analitik perpustakaan
- ✅ Laporan comprehensive

---

## 🎨 Design System

Project menggunakan:
- **Bootstrap 5.3** untuk responsive layout
- **Custom CSS Variables** untuk theming
- **Tailwind CSS** untuk utility classes
- **Bootstrap Icons** untuk iconography

---

## 🚦 Development Tips

### Hot Reload CSS/JS
Jika menggunakan Vite (development):
```bash
npm run dev
# CSS dan JS akan auto-reload saat ada perubahan
```

### Debug Mode
Edit `.env`:
```env
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

### Database Browser
Buka database menggunakan tools seperti:
- **phpMyAdmin** (jika menggunakan Laragon)
- **MySQL Workbench**
- **DBeaver**
- **TablePlus**

---

## 📞 Bantuan Teknis

Jika ada error atau pertanyaan teknis:

1. **Cek dokumentasi**: Baca file `.md` yang tersedia
2. **Check GitHub Issues**: https://github.com/dianatami/perpustakaan/issues
3. **Run tests**: `php artisan test`
4. **Check logs**: `storage/logs/laravel.log`

---

## 📝 Notes

- Jangan commit file `.env` (sudah di `.gitignore`)
- Jangan push folder `node_modules` dan `vendor`
- Selalu jalankan `composer install` dan `npm install` setelah pull
- Test migration baru sebelum push ke repository
- Tulis commit message yang deskriptif dan dalam bahasa Inggris

---

**Happy Coding! 🚀**

Perpustakaan Sekolah v1.0.0 — Made with ❤️ by Development Team
