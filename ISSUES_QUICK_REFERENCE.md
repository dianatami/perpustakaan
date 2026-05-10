# Quick Reference: Issues & Quick Fixes

## Critical Issues (Must Fix)

### 1️⃣ Missing NISN Field
**Status:** ❌ Blocks student registration
**What's wrong:** NISN validation exists but database column doesn't exist
**File:** No migration exists for NISN column
**Quick fix:** 
```bash
php artisan make:migration add_nisn_to_user_table
```
Then add:
```php
$table->string('nisn', 10)->nullable()->unique()->after('nip');
```

**Files affected:**
- `app/Models/User.php` - Add 'nisn' to fillable
- `app/Http/Controllers/RegisterController.php` - Update form field name
- `app/Http/Controllers/LoginController.php` - Handle NISN login separately

---

### 2️⃣ Seeder Has Bad Data
**Status:** ❌ Fresh migrations will fail
**What's wrong:** `BookrentSeeder.php` uses deprecated `'dikembalikan'` status
**File:** `database/seeders/BookrentSeeder.php` line 38
**Quick fix:** Change `'status' => 'dikembalikan'` to `'status' => 'kembali'`

---

## High Issues (Fix This Week)

### 3️⃣ Stock Goes Negative
**Status:** ⚠️ Books can be lost
**What's wrong:** Two different places calculate stock returns differently
**File:** `app/Http/Controllers/Admin/PeminjamanController.php`
- Line 103-106: Only restore stock if condition is 'baik'
- Line 190-192: Always restore stock

**Needs:** Decide business logic - should damaged books keep stock? Currently inconsistent.

---

### 4️⃣ Wrong Borrowing Count
**Status:** ⚠️ Dashboard shows wrong numbers
**What's wrong:** Counts null return_date instead of checking status
**File:** `app/Http/Controllers/Anggota/BerandaAnggotaController.php` line 18-21
**Quick fix:** 
```php
// Change from:
->whereNull('return_date')

// To:
->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
```

---

### 5️⃣ Views Show Wrong Status Labels
**Status:** ⚠️ UI confusing
**What's wrong:** Views check for 'dikembalikan' but database has 'kembali'
**Files affected:**
- `resources/views/admin/peminjaman/index.blade.php` - Lines 142, 155
- `resources/views/anggota/riwayat-peminjaman.blade.php` - Line 98
- `resources/views/anggota/Profile/index.blade.php` - Line 396

**Quick fix:** Replace all 'dikembalikan' with 'kembali'

---

### 6️⃣ Guru Role Incomplete
**Status:** ⚠️ Guru users lack features
**What's wrong:** Only dashboard exists, no guru-specific workflows
**Files affected:**
- `app/Http/Controllers/Guru/` - Only BerandaGuruController
- `routes/web.php` - Lines 94-98 share routes with Anggota

**Needs:** Either implement guru features or document that guru=student

---

## Medium Issues (Fix When You Can)

### 7️⃣ NISN Not Unique
When NISN field is added, validation needs uniqueness check

### 8️⃣ Fines Calculated Two Ways
`PeminjamanController::update()` and `confirmReturn()` calculate fines differently

### 9️⃣ Phone Validation Inconsistent
- Register: `digits_between:10,13`
- Admin: `string|max:13`
- Profile: `string|max:13`

### 🔟 No Transaction in Borrow
Race condition if two users borrow last copy simultaneously
Needs: `DB::transaction()` with `lockForUpdate()`

---

## Low Issues (Nice to Fix)

### 1️⃣1️⃣ Duplicate perpustakaan/ Directory
Remove: `rm -rf perpustakaan/`

### 1️⃣2️⃣ Empty KepalaSekolah Controller
Remove: `rm -rf app/Http/Controllers/KepalaSekolah/`

### 1️⃣3️⃣ Damaged/Lost Fields Unused
Decide: Use Book.damaged/lost columns or remove them

### 1️⃣4️⃣-1️⃣6️⃣ Minor Code Quality Issues
- Add validation for condition field
- Improve seeder error messages
- Standardize route naming

---

## Current Enum Values (Reference)

Valid bookrent statuses:
- `'menunggu_acc'` - Awaiting approval
- `'dipinjam'` - Currently borrowed
- `'ditolak'` - Rejected
- `'proses_kembali'` - Return in progress
- `'kembali'` - Returned/Completed

~~'dikembalikan'~~ ← REMOVED, use 'kembali' instead

---

## Validation Rules (Standardize These)

| Field | Current Rule(s) | Recommended |
|-------|-----------------|------------|
| hp | digits_between:10,13 / string\|max:13 | `digits_between:10,13` |
| nisn | (field missing) | `nullable\|digits:10\|unique:user` |
| nip | digits:18 | `digits:18` ✓ |
| condition | nullable\|in:baik,rusak,hilang | ✓ Consistent |

---

## Testing Checklist

After fixes:
- [ ] Fresh migration + seed works
- [ ] Login with NIP works
- [ ] Login with NISN works (after Issue #1)
- [ ] Register with NISN works (after Issue #1)
- [ ] Dashboard counts correct books (Issue #4)
- [ ] Book returns update stock correctly (Issue #3)
- [ ] Concurrent borrows don't double-borrow (Issue #10)
- [ ] Enum values match views (Issue #5)
- [ ] Run: `php artisan test`

---

## File Dependencies

If fixing Issue #1 (NISN field), must also update:
1. Migration (new)
2. User model - add to fillable
3. RegisterController - form field
4. LoginController - add NISN lookup

If fixing Issue #3 (Stock), must decide then update:
1. PeminjamanController::update()
2. PeminjamanController::approve()
3. PeminjamanController::confirmReturn()

---

Last Updated: May 7, 2026
