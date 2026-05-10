# File-by-File Issues Index

## Database

### Migrations
- **2026_05_06_120001_update_bookrent_status_enum.php**
  - ✓ Correctly removes 'dikembalikan' and adds other statuses
  - Related Issue: #2 (Seeder uses removed enum)

- **2026_04_29_000001_add_nip_to_user_table.php**
  - ❌ Only adds NIP, not NISN field
  - Related Issue: #1 (Missing NISN)

- **2026_01_13_143717_add_damaged_and_lost_to_books_table.php**
  - ⚠️ Adds unused columns
  - Related Issue: #11 (Unused fields)

### Seeders
- **database/seeders/BookrentSeeder.php**
  - Line 38: ❌ Uses 'dikembalikan' (removed enum)
  - Related Issue: #2
  - Also: Line 18-21 - Silent fail on no data (Issue #16)

---

## Models

### app/Models/User.php
- ✓ NISN_REGEX defined (line 19)
- ✓ NIP_REGEX defined (line 18)
- ✓ Validation methods implemented
- Line 25-39: ❌ Missing 'nisn' in fillable array
- Related Issue: #1

### app/Models/Book.php
- ✓ Has damaged, lost fields in migration
- ⚠️ These fields never used in logic
- Related Issue: #11

### app/Models/Bookrent.php
- ✓ Status enum values correct
- ✓ Relations properly defined

---

## Controllers

### Authentication
**app/Http/Controllers/LoginController.php**
- Line 25-26: ❌ Validates NISN but no separate column lookup
- Line 26-35: ❌ Uses `where('nip', $identifier)` for both NIP and NISN
- Related Issues: #1

**app/Http/Controllers/RegisterController.php**
- Line 27-33: ⚠️ Validates NISN but stores in NIP field
- Line 41: `'hp' => 'required|digits_between:10,13'` ✓
- Related Issues: #1, #9

### Admin Controllers
**app/Http/Controllers/Admin/PeminjamanController.php**
- Line 82: ✓ Validation includes all enum values
- Line 93-115 (update): ❌ Stock only restored if condition='baik'
- Line 190-192 (confirmReturn): ⚠️ Stock always restored
- Line 93-115, 185-200: ⚠️ Fines calculated inconsistently
- Related Issues: #3, #8

**app/Http/Controllers/Admin/BookController.php**
- Line 34-50: ❌ Missing validation for damaged/lost
- Line 97: ⚠️ Accepts damaged/lost but view doesn't provide them
- Related Issues: #11, #14

**app/Http/Controllers/Admin/KategoriController.php**
- ✓ No issues found

### Anggota/Guru Controllers
**app/Http/Controllers/Anggota/BerandaAnggotaController.php**
- Line 18-21: ❌ Uses `whereNull('return_date')` instead of status check
- Related Issue: #4

**app/Http/Controllers/Guru/BerandaGuruController.php**
- ⚠️ Only dashboard, no guru-specific features
- Related Issue: #6

**app/Http/Controllers/ProfileAnggotaController.php**
- Line 66-116 (borrow): ❌ No database transaction or lock
- Line 176: `'hp' => 'required|string|max:13'` ⚠️ Inconsistent with register
- Related Issues: #9, #10

**app/Http/Controllers/AnggotaController.php**
- Line 46: `'hp' => 'required|string|max:13'` ⚠️ Inconsistent
- Related Issue: #9

---

## Views

### Login/Register
- **resources/views/tampilan/login.blade.php**
  - ✓ Accepts NIP/NISN/Email - OK for display
  - Needs backend fix for actual NISN handling

- **resources/views/tampilan/register.blade.php**
  - ✓ Accepts NIP/NISN/Email - OK for display
  - Field label says "NIP/NISN" but input name is "nip"

### Admin Peminjaman Views
- **resources/views/admin/peminjaman/index.blade.php**
  - Line 142: ⚠️ Shows "Belum Dikembalikan"
  - Line 155: ⚠️ Shows "Sudah Dikembalikan" for status='kembali'
  - Related Issue: #5

- **resources/views/admin/peminjaman/edit.blade.php**
  - Line 82: ✓ Status options are current
  - Line 95-100: Has 'condition' field select (for baik/rusak/hilang)
  - Line 100: ✓ Currently correct

### Anggota Views
- **resources/views/anggota/riwayat-peminjaman.blade.php**
  - Line 95-98: ❌ Checks deprecated conditions
  - Line 98: ❌ Shows "Sudah dikembalikan"
  - Related Issue: #5

- **resources/views/anggota/Profile/index.blade.php**
  - Line 311: ✓ `where('status', 'kembali')` - Correct
  - Line 394: ⚠️ Checks multiple statuses (some old format?)
  - Line 396: ❌ Text shows "Sudah Dikembalikan"
  - Related Issue: #5

---

## Routes & Configuration

**routes/web.php**
- Line 50: Middleware `'role:1'` ✓ Correct (Admin)
- Line 74: Middleware `'role:0'` ✓ Correct (Anggota)
- Line 91: Middleware `'role:2'` ✓ Correct (Guru)
- Line 87-98: ⚠️ Guru uses same $portalSharedRoutes as Anggota
- Related Issue: #6

**app/Http/Kernel.php**
- Line 40: ✓ Role middleware defined correctly

**app/Http/Middleware/EnsureUserHasRole.php**
- ✓ Middleware logic correct

---

## Tests

**tests/Feature/RoleAccessAndLoginTest.php**
- ✓ Tests expect current behavior
- Line 16: Creates guru with NIP '198704122010011001' ✓
- Line 111-116: Tests NISN login (but field doesn't exist)
- Related Issue: #1

**tests/Feature/BookReturnFeatureTest.php**
- ✓ Tests use current enum values
- Line 70: Creates with status='proses_kembali' ✓
- Line 114: Expects status='kembali' ✓

---

## Configuration Files
- **config/app.php** ✓ No issues
- **config/auth.php** ✓ No issues
- **composer.json** ✓ No issues

---

## Directory Structure

### Problem Areas
- **app/Http/Controllers/KepalaSekolah/** ❌ Empty, unused
  - Related Issue: #13

- **perpustakaan/** ❌ Duplicate of entire project
  - Related Issue: #12

### Good Structure
- **app/Http/Controllers/Anggota/** ✓ Well organized
- **app/Http/Controllers/Admin/** ✓ Well organized
- **app/Http/Controllers/Guru/** ⚠️ Only BerandaGuruController (Issue #6)

---

## Summary by Issue Count

| File | Issues | Priority |
|------|--------|----------|
| PeminjamanController.php | 3 | 🔴 Critical |
| BerandaAnggotaController.php | 1 | 🟠 High |
| LoginController.php | 1 | 🔴 Critical |
| RegisterController.php | 1 | 🟠 High |
| User.php | 1 | 🔴 Critical |
| BookrentSeeder.php | 2 | 🔴 Critical |
| ProfileAnggotaController.php | 2 | 🟡 Medium |
| riwayat-peminjaman.blade.php | 2 | 🟠 High |
| Profile/index.blade.php | 2 | 🟠 High |
| AnggotaController.php | 1 | 🟡 Medium |
| BookController.php | 2 | 🟡 Medium |
| web.php | 1 | 🟠 High |
| peminjaman/index.blade.php | 2 | 🟠 High |
| peminjaman/edit.blade.php | 1 | ✓ OK |
| Tests | 1 | 🟠 High |
| Others | 3 | 🔵 Low |

---

## Cross-File Dependencies

**Issue #1 (Missing NISN field)** affects:
- Migration (doesn't exist)
- User model
- LoginController  
- RegisterController
- Tests

**Issue #2 (Bad seeder)** affects:
- BookrentSeeder (primary)
- Related to migration #5

**Issue #3 (Stock inconsistency)** affects:
- PeminjamanController (2 places)
- Related to Issue #11

**Issue #5 (View enums)** affects:
- 4 different blade files
- Related to migration status enum

**Issue #9 (Validation inconsistency)** affects:
- 3 different controllers
- Could affect form processing

---

Generated: May 7, 2026
