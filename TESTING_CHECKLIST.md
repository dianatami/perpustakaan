# ✨ CHECKLIST IMPLEMENTASI & VERIFIKASI SISTEM APPROVAL

## 🎯 Pre-Launch Checklist

### **A. Database Verification**

- [ ] Migration `create_bookrent_table` sudah run
- [ ] Migration `create_detail_bookrent_table` sudah run
- [ ] Migration `update_bookrent_status_enum` sudah run
- [ ] Status enum punya nilai: `menunggu_acc`, `dipinjam`, `ditolak`, `proses_kembali`, `kembali`
- [ ] Tabel `bookrent` punya kolom: `id`, `user_id`, `borrow_date`, `return_date`, `status`, `denda`
- [ ] Tabel `detail_bookrent` punya kolom: `id`, `bookrent_id`, `book_id`, `qty`, `condition`
- [ ] Foreign keys sudah set dengan cascade delete

**Verifikasi SQL:**
```sql
-- Check status enum
SHOW COLUMNS FROM bookrent WHERE Field = 'status';

-- Check table structure
DESC bookrent;
DESC detail_bookrent;

-- Check data
SELECT * FROM bookrent LIMIT 1;
```

---

### **B. Backend Verification**

#### **Routes**
- [ ] Route admin peminjaman: GET `/admin/peminjaman`
- [ ] Route admin approve: POST `/admin/peminjaman/{id}/approve`
- [ ] Route admin reject: POST `/admin/peminjaman/{id}/reject`
- [ ] Route admin process return: GET `/admin/peminjaman/{id}/process-return`
- [ ] Route murid pinjam: POST `/anggota/pinjam`
- [ ] Route murid pengembalian: POST `/anggota/pengembalian/{id}`
- [ ] Route guru pinjam: POST `/guru/pinjam`
- [ ] Route guru pengembalian: POST `/guru/pengembalian/{id}`

**Test via Artisan:**
```bash
php artisan route:list | grep peminjaman
php artisan route:list | grep pinjam
```

#### **Models**
- [ ] Model `Bookrent` exists dengan relationships
- [ ] Model `DetailBookrent` exists dengan relationships
- [ ] Model `User` punya ROLE constants
- [ ] Model `Book` punya stock management

**Test:**
```bash
php artisan tinker
>>> Bookrent::first()
>>> DetailBookrent::first()
```

#### **Controllers**
- [ ] `PeminjamanController` exists di `app/Http/Controllers/Admin/`
- [ ] `ProfileAnggotaController` exists di `app/Http/Controllers/`
- [ ] Method `approve()` exist di PeminjamanController
- [ ] Method `reject()` exist di PeminjamanController
- [ ] Method `borrow()` exist di ProfileAnggotaController
- [ ] Method `confirmReturn()` exist di PeminjamanController

**Test:**
```bash
php artisan tinker
>>> app('App\Http\Controllers\Admin\PeminjamanController')
>>> app('App\Http\Controllers\ProfileAnggotaController')
```

---

### **C. Frontend Verification**

#### **Views Exist**
- [ ] `/resources/views/admin/peminjaman/index.blade.php`
- [ ] `/resources/views/admin/peminjaman/create.blade.php`
- [ ] `/resources/views/admin/peminjaman/edit.blade.php`
- [ ] `/resources/views/admin/peminjaman/return.blade.php`
- [ ] `/resources/views/anggota/Profile/index.blade.php`

#### **UI Elements**
- [ ] Statistics cards terlihat di admin
- [ ] Filter dropdown ada di admin
- [ ] Search box ada di admin
- [ ] Modal approve ada
- [ ] Tombol Setujui/Tolak ada
- [ ] Tombol Kembalikan ada
- [ ] Form pinjam buku ada di profil murid

**Check in Browser:**
- [ ] Open `/admin/peminjaman` → Lihat tabel
- [ ] Open `/anggota/profil-detail` → Lihat form pinjam

---

### **D. Authorization & Security**

- [ ] Admin role check di PeminjamanController
- [ ] Murid role check di profil murid
- [ ] Guru role check di profil guru
- [ ] CSRF token di semua form
- [ ] User hanya bisa edit data sendiri
- [ ] Middleware `['auth', 'role:1']` di admin routes
- [ ] Middleware `['auth', 'role:0']` di anggota routes
- [ ] Middleware `['auth', 'role:2']` di guru routes

**Test via Browser:**
```
1. Logout
2. Coba akses /admin/peminjaman → harus redirect login
3. Login sebagai murid
4. Coba akses /admin/peminjaman → harus denied
5. Login sebagai admin
6. Buka /admin/peminjaman → harus bisa
```

---

### **E. Validation Testing**

#### **Validation Rules**
- [ ] `borrow_duration` harus integer 1-30
- [ ] `books` array minimal 1 item
- [ ] `book_id` harus exist di DB
- [ ] `qty` harus > 0
- [ ] `return_date` >= `borrow_date`

#### **Business Logic Validation**
- [ ] Tidak bisa pinjam buku sama 2x
- [ ] Tidak bisa pinjam > stok yang ada
- [ ] Tidak bisa pinjam > 3 buku aktif
- [ ] Tidak bisa approve jika status != 'menunggu_acc'
- [ ] Tidak bisa approve jika stok < qty

**Manual Test:**
```
1. Try submit form tanpa pilih buku
2. Try pinjam buku stok 0
3. Try pinjam buku yang sudah dipinjam
4. Try pinjam 4 buku (limit 3)
5. Try pinjam buku sama dalam 1 form
→ Semua harus error dengan pesan jelas
```

---

## 🧪 Functional Testing

### **Test Case 1: Admin Approve Peminjaman**

```
SETUP:
- Pastikan ada buku dengan stok >= 1
- Pastikan ada user murid

STEPS:
1. Login sebagai murid
2. Buka profil
3. Pilih 1 buku, qty 1
4. Klik "AJUKAN"
5. Logout

6. Login sebagai admin
7. Buka /admin/peminjaman
8. Lihat peminjaman baru dengan status "Menunggu ACC"
9. Klik tombol "SETUJUI" (hijau)
10. Modal muncul
11. Set durasi = 7
12. Klik "KONFIRMASI PERSETUJUAN"

EXPECTED RESULT:
✅ Modal close
✅ Page reload
✅ Status berubah menjadi "DIPINJAM" (BIRU)
✅ Notifikasi success
✅ Stok buku berkurang 1
✅ Tanggal pinjam = hari ini
✅ Tanggal kembali = hari ini + 7
```

### **Test Case 2: Admin Reject Peminjaman**

```
SETUP:
- Buat peminjaman baru (status: menunggu_acc)

STEPS:
1. Login sebagai admin
2. Buka /admin/peminjaman
3. Lihat peminjaman dengan status "Menunggu ACC"
4. Klik tombol "TOLAK" (merah)
5. Confirm dialog
6. Klik "OK"

EXPECTED RESULT:
✅ Status berubah menjadi "DITOLAK" (MERAH)
✅ Notifikasi success
✅ Stok buku TIDAK berkurang
✅ Tombol action hilang
```

### **Test Case 3: Pengembalian Buku**

```
SETUP:
- Ada peminjaman dengan status "DIPINJAM"

STEPS (MURID SIDE):
1. Login sebagai murid
2. Buka profil
3. Lihat buku yang sedang dipinjam
4. Klik tombol "KEMBALIKAN"
5. Confirm dialog
6. Klik "OK"

EXPECTED RESULT:
✅ Status berubah menjadi "PROSES PENGEMBALIAN" (KUNING)
✅ Tombol "KEMBALIKAN" hilang

STEPS (ADMIN SIDE):
1. Login sebagai admin
2. Buka /admin/peminjaman
3. Filter status: "Proses Pengembalian"
4. Klik "TERIMA PENGEMBALIAN"
5. Set kondisi buku: "Baik"
6. Submit

EXPECTED RESULT:
✅ Status berubah menjadi "KEMBALI" (HIJAU)
✅ Stok buku bertambah 1
✅ Denda = 0 (jika tidak terlambat)
✅ Notifikasi success
```

### **Test Case 4: Denda Perhitungan**

```
SETUP:
- Ada peminjaman dengan borrow_date 8 hari lalu

STEPS:
1. Admin submit pengembalian dengan return_date hari ini
2. Admin set kondisi: "Baik"
3. Submit

EXPECTED RESULT:
✅ Denda dihitung: (8 - 7) × 5000 = Rp 5.000
✅ Denda tersimpan di DB
✅ Display denda di tabel
```

### **Test Case 5: Filter & Search**

```
STEPS:
1. Login sebagai admin
2. Buka /admin/peminjaman

TEST FILTER:
1. Klik dropdown filter
2. Pilih "Menunggu Persetujuan"
3. Tabel hanya show status "Menunggu ACC" ✅
4. Ubah filter ke "Sudah Disetujui"
5. Tabel hanya show status "Dipinjam" ✅

TEST SEARCH:
1. Type nama murid di search box
2. Tabel filter real-time ✅
3. Clear search
4. Type judul buku
5. Tabel filter berdasarkan judul ✅
```

---

## 🔄 Integration Testing

### **Full Flow Test**

```
1. MURID SUBMIT REQUEST
   ✓ Form pinjam buku accessible
   ✓ Validation bekerja
   ✓ Request tersimpan
   ✓ Status = "menunggu_acc"
   ✓ Stok belum berkurang

2. ADMIN APPROVE
   ✓ Admin lihat di dashboard
   ✓ Statistics update
   ✓ Approval berhasil
   ✓ Status = "dipinjam"
   ✓ Stok berkurang

3. MURID LIHAT STATUS
   ✓ Lihat status "dipinjam"
   ✓ Lihat tanggal kembali
   ✓ Tombol "kembalikan" active

4. MURID RETURN
   ✓ Submit pengembalian
   ✓ Status = "proses_kembali"

5. ADMIN CONFIRM RETURN
   ✓ Lihat di admin
   ✓ Set kondisi
   ✓ Hitung denda
   ✓ Status = "kembali"
   ✓ Stok bertambah

6. MURID BISA PINJAM LAGI
   ✓ Buku sudah bisa dipilih lagi
   ✓ Daftar buku update
```

---

## 🎨 UI/UX Testing

### **Admin UI**
- [ ] Modal approve readable dan clear
- [ ] Return date preview akurat
- [ ] Button colors meaningful (green=approve, red=reject)
- [ ] Filter dropdown working
- [ ] Search real-time responsive
- [ ] Pagination working
- [ ] Status badge colors clear
- [ ] Table responsive di mobile

### **Murid UI**
- [ ] Form pinjam intuitive
- [ ] Dropdown buku mudah dipilih
- [ ] Tambah/hapus buku smooth
- [ ] Status badge jelas
- [ ] Statistics card readable
- [ ] Tombol action accessible
- [ ] Error message clear
- [ ] Success notification terlihat

---

## 📱 Cross-Browser Testing

Test di:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Chrome
- [ ] Mobile Safari

**Common Issues:**
- [ ] Modal tidak responsive di mobile?
- [ ] Dropdown tidak berfungsi?
- [ ] Form submit error?
- [ ] AJAX tidak bekerja?

---

## 🚀 Performance Testing

- [ ] Page load time < 2 detik
- [ ] Filter/search responsive (instant)
- [ ] Modal open < 500ms
- [ ] Approval submit < 1 detik
- [ ] Database query optimized
  - [ ] Gunakan eager loading (.with())
  - [ ] Limit pagination (10-20 per page)
  - [ ] Index pada foreign keys

**Check Queries:**
```bash
# Enable query logging
php artisan tinker
>>> \DB::enableQueryLog()
>>> // akses halaman admin peminjaman
>>> \DB::getQueryLog()

# Harus < 10 queries
```

---

## 📊 Data Integrity Testing

```
1. Check constraint foreign key
   ✓ Tidak bisa delete user dengan peminjaman aktif
   ✓ Tidak bisa delete buku dalam detail_bookrent

2. Check enum status
   ✓ Status hanya bisa 5 nilai
   ✓ Tidak bisa insert nilai invalid

3. Check calculation
   ✓ Stok += qty saat approve
   ✓ Stok -= qty saat return
   ✓ Denda calculated correctly

4. Check date
   ✓ return_date >= borrow_date
   ✓ Timestamp auto-updated
```

---

## 🔒 Security Testing

```
1. SQL Injection
   ✓ Try search: '; DROP TABLE bookrent; --
   ✓ Harus error, not crash

2. CSRF
   ✓ Form submit tanpa CSRF token
   ✓ Harus error 419

3. Authorization
   ✓ User role:0 try access /admin
   ✓ Harus redirect
   ✓ User role:0 try edit peminjaman user lain
   ✓ Harus forbidden

4. Input Validation
   ✓ Negative qty: -5
   ✓ Invalid date: "xxx"
   ✓ Semua harus rejected

5. Rate Limiting (opsional)
   ✓ Multiple rapid requests
   ✓ Harus throttle?
```

---

## 📋 Final Checklist

### **Backend**
- [ ] All routes working
- [ ] All methods callable
- [ ] Database schema correct
- [ ] Validations working
- [ ] Error handling proper
- [ ] Logging setup
- [ ] No fatal errors in logs

### **Frontend**
- [ ] All pages loading
- [ ] All forms responsive
- [ ] All buttons clickable
- [ ] All modals appearing
- [ ] Search/filter working
- [ ] Pagination working
- [ ] Status badges displaying

### **Functionality**
- [ ] Approve flow complete
- [ ] Reject flow complete
- [ ] Return flow complete
- [ ] Denda calculation correct
- [ ] Stok management correct
- [ ] Statistics accurate

### **Security**
- [ ] Auth middleware working
- [ ] CSRF tokens present
- [ ] Role authorization working
- [ ] No SQL injection risks
- [ ] Input sanitized

### **Documentation**
- [ ] SISTEM_APPROVAL_PEMINJAMAN.md complete
- [ ] PANDUAN_CEPAT_ADMIN.md complete
- [ ] PANDUAN_MURID_GURU.md complete
- [ ] IMPLEMENTASI_SUMMARY.md complete

---

## ✅ Go-Live Checklist

- [ ] All tests passing
- [ ] No critical bugs
- [ ] No performance issues
- [ ] Documentation complete
- [ ] Admin trained
- [ ] Users notified
- [ ] Backup taken
- [ ] Monitoring setup
- [ ] Support plan ready

---

## 📞 Post-Launch Support

### **Monitor These:**
```
1. Error logs
   php artisan logs

2. Database
   SELECT COUNT(*) FROM bookrent;
   SELECT COUNT(*) FROM detail_bookrent;

3. Users complaints
   Form feedback
   Email support
   Direct messages

4. Performance
   Page load time
   Database queries
   Server resources
```

### **Common Issues & Fixes:**

| Issue | Penyebab | Solusi |
|-------|----------|--------|
| Tombol tidak responsive | JS error | Check console, reload page |
| Stok tidak berkurang | Query error | Check migration, run migration |
| Denda salah | Date parsing | Check timezone, date format |
| Filter tidak bekerja | CSS hidden | Check CSS, browser cache clear |

---

## 🎓 Training Checklist

### **Admin Training**
- [ ] Cara lihat daftar peminjaman
- [ ] Cara approve permintaan
- [ ] Cara reject permintaan
- [ ] Cara terima pengembalian
- [ ] Cara hitung denda
- [ ] Cara handle error
- [ ] Cara check report

### **Murid/Guru Training**
- [ ] Cara submit request pinjam
- [ ] Cara lihat status
- [ ] Cara kembalikan buku
- [ ] Bagaimana jika ditolak
- [ ] Bagaimana jika telat
- [ ] FAQ & support

---

**Versi:** 1.0  
**Status:** Ready for Deployment  
**Last Checked:** [DATE]  
**Checked By:** [ADMIN NAME]
