# Laravel Perpustakaan Project - Comprehensive Analysis Report

**Generated:** May 7, 2026  
**Project:** Library Management System (perpustakaan)  
**Analysis Focus:** User workflows, database consistency, route mapping, middleware, validation, and data integrity

---

## Executive Summary

This report identifies **16 issues** across the perpustakaan project, ranging from critical database inconsistencies to low-priority code cleanup. The most urgent issues involve:
1. Missing NISN database field despite validation code
2. Deprecated enum values still used in seeder
3. Stock management inconsistencies
4. Incomplete guru role implementation

**Severity Breakdown:**
- **Critical:** 2 issues
- **High:** 4 issues
- **Medium:** 4 issues
- **Low:** 6 issues

---

## Detailed Findings

### CRITICAL ISSUES

#### 1. Missing NISN Database Field ⚠️ CRITICAL
**Severity:** Critical  
**Impact:** Registration and login workflow broken for students (Anggota) using NISN

**Details:**
- NISN validation exists in `User` model but database field doesn't exist
- Code validates 10-digit NISN format: [User.php](app/Models/User.php#L19) - `NISN_REGEX = '/^\d{10}$/'`
- Login and registration accept NISN values [LoginController.php](app/Http/Controllers/LoginController.php#L25-L35)
- But only NIP column exists in database (added in migration [2026_04_29_000001_add_nip_to_user_table.php](database/migrations/2026_04_29_000001_add_nip_to_user_table.php))
- NISN is stored in the same NIP column, mixing 18-digit NIP with 10-digit NISN

**Affected Files:**
- [app/Models/User.php](app/Models/User.php#L25-L39) - User model fillable doesn't include 'nisn'
- [app/Http/Controllers/RegisterController.php](app/Http/Controllers/RegisterController.php#L30-L31) - Validation accepts NISN
- [app/Http/Controllers/LoginController.php](app/Http/Controllers/LoginController.php#L25-L35) - Login attempts NISN lookup

**Solution:**
Add a dedicated NISN column to the user table:
```php
Schema::table('user', function (Blueprint $table) {
    $table->string('nisn', 10)->nullable()->unique()->after('nip');
});
```
Update User model fillable array to include 'nisn', and update login/register logic to use separate columns.

---

#### 2. Seeder Uses Deprecated Enum Value ⚠️ CRITICAL
**Severity:** Critical  
**Impact:** Database seeder will fail on fresh installation

**Details:**
- [BookrentSeeder.php](database/seeders/BookrentSeeder.php#L38) uses status: `'dikembalikan'`
- But [2026_05_06_120001_update_bookrent_status_enum.php](database/migrations/2026_05_06_120001_update_bookrent_status_enum.php#L14) converts all `'dikembalikan'` to `'kembali'`
- Migration removes 'dikembalikan' from enum entirely (line 15)
- Seeder creates invalid enum values

**Affected Files:**
- [database/seeders/BookrentSeeder.php](database/seeders/BookrentSeeder.php#L30-L45)
- [database/migrations/2026_05_06_120001_update_bookrent_status_enum.php](database/migrations/2026_05_06_120001_update_bookrent_status_enum.php)

**Solution:**
Update seeder to use current valid enum values:
```php
'status' => 'kembali', // instead of 'dikembalikan'
```

---

### HIGH ISSUES

#### 3. Inconsistent Stock Management in Book Return Flow ⚠️ HIGH
**Severity:** High  
**Impact:** Book stock can become inaccurate when books are returned in damaged/lost condition

**Details:**
- In [PeminjamanController.php](app/Http/Controllers/Admin/PeminjamanController.php#L103-L106), stock restoration is conditional:
  ```php
  if ($peminjaman->status !== 'kembali' && $condition === 'baik' && $peminjaman->book) {
      $peminjaman->book->stock += 1;
  }
  ```
  Stock is ONLY restored if condition is 'baik' (good)

- But in `confirmReturn()` method [line 190](app/Http/Controllers/Admin/PeminjamanController.php#L190-L192):
  ```php
  if ($peminjaman->book) {
      $peminjaman->book->stock += 1;
  }
  ```
  Stock is ALWAYS restored regardless of condition

**Inconsistency:** Same return flow has different stock logic in two places. If a book is marked 'rusak' (damaged) or 'hilang' (lost), it's unclear whether stock should increase.

**Affected Files:**
- [app/Http/Controllers/Admin/PeminjamanController.php](app/Http/Controllers/Admin/PeminjamanController.php#L90-L115) - update() method
- [app/Http/Controllers/Admin/PeminjamanController.php](app/Http/Controllers/Admin/PeminjamanController.php#L175-L200) - confirmReturn() method

**Solution:**
Clarify business logic: Should damaged/lost books increase stock or stay in inventory tracking? Choose one approach:
- Option A: Always restore stock (current confirmReturn logic)
- Option B: Only restore if 'baik', track damaged separately using Book.damaged and Book.lost columns

---

#### 4. BerandaAnggotaController Uses Wrong Query for Active Borrowings ⚠️ HIGH
**Severity:** High  
**Impact:** Active borrowing count incorrect if user has completed returns or rejected requests

**Details:**
- [BerandaAnggotaController.php](app/Http/Controllers/Anggota/BerandaAnggotaController.php#L18-L21):
  ```php
  $bookrents = Bookrent::where('user_id', auth()->user()->id)
      ->whereNull('return_date')
      ->count();
  ```
  Uses `whereNull('return_date')` to find active borrowings

- But multiple statuses have `return_date = null`:
  - 'menunggu_acc' (awaiting approval)
  - 'dipinjam' (actively borrowed)
  - 'ditolak' (rejected) - should NOT be counted as active
  - 'proses_kembali' (return in progress)

**Impact:** Count includes rejected requests, inflating active borrowing numbers.

**Affected Files:**
- [app/Http/Controllers/Anggota/BerandaAnggotaController.php](app/Http/Controllers/Anggota/BerandaAnggotaController.php#L17-L21)

**Solution:**
Filter by specific statuses:
```php
$bookrents = Bookrent::where('user_id', auth()->user()->id)
    ->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
    ->count();
```

---

#### 5. View References to Removed Enum Value ⚠️ HIGH
**Severity:** High  
**Impact:** UI displays outdated status labels and checks fail silently

**Details:**
- Blade templates still check for deprecated 'dikembalikan' status that was migrated to 'kembali'

**Affected Files:**
- [resources/views/admin/peminjaman/index.blade.php](resources/views/admin/peminjaman/index.blade.php#L142) - Badge shows "Belum Dikembalikan" 
- [resources/views/admin/peminjaman/index.blade.php](resources/views/admin/peminjaman/index.blade.php#L155) - Badge shows "Sudah Dikembalikan" but checks status='kembali'
- [resources/views/anggota/riwayat-peminjaman.blade.php](resources/views/anggota/riwayat-peminjaman.blade.php#L98) - Text displays "Sudah dikembalikan"
- [resources/views/anggota/Profile/index.blade.php](resources/views/anggota/Profile/index.blade.php#L396) - Same

**Solution:**
Verify all view logic matches current enum values. Current valid values:
- 'menunggu_acc' (awaiting approval)
- 'dipinjam' (borrowed)
- 'ditolak' (rejected)
- 'proses_kembali' (return in progress)
- 'kembali' (returned)

---

#### 6. Incomplete Guru Role Implementation ⚠️ HIGH
**Severity:** High  
**Impact:** Guru users cannot access role-specific features, only generic anggota/guru shared routes

**Details:**
- Guru role (role=2) is registered in [User.php](app/Models/User.php#L16)
- Routes configured in [web.php](routes/web.php#L94-L98)
- But only [BerandaGuruController.php](app/Http/Controllers/Guru/BerandaGuruController.php) exists
- No dedicated guru controllers for:
  - Book management or recommendations
  - Student monitoring dashboard
  - Administrative functions beyond shared routes
  - Borrowing approval workflow (shared with anggota but should differ)

**Affected Files:**
- [app/Http/Controllers/Guru/](app/Http/Controllers/Guru/) - Only BerandaGuruController exists
- [routes/web.php](routes/web.php#L87-L98) - Guru uses $portalSharedRoutes same as anggota

**Solution:**
Either:
1. Implement guru-specific controllers and features
2. Or restrict guru access and clarify if they should use same workflow as students

---

### MEDIUM ISSUES

#### 7. NISN Validation Not Enforced as Unique ⚠️ MEDIUM
**Severity:** Medium  
**Impact:** Multiple users can register with same NISN (if field existed)

**Details:**
- [RegisterController.php](app/Http/Controllers/RegisterController.php#L27-L33) validates NISN format but doesn't check uniqueness
- Validation rule:
  ```php
  'nip' => ['nullable', 'string', function (...) { ... }, 'unique:user,nip']
  ```
  Only validates NIP uniqueness, not NISN
- Once NISN field is added (Issue #1), must also add unique constraint

**Affected Files:**
- [app/Http/Controllers/RegisterController.php](app/Http/Controllers/RegisterController.php#L27)

**Solution:**
When implementing NISN field, add validation:
```php
'nisn' => 'nullable|string|unique:user,nisn',
```

---

#### 8. Fine Calculation Inconsistency Between Two Methods ⚠️ MEDIUM
**Severity:** Medium  
**Impact:** Users may see different fines depending on which admin action is used

**Details:**
- [update() method](app/Http/Controllers/Admin/PeminjamanController.php#L93-L115):
  ```php
  $borrowDate = Carbon::parse($peminjaman->borrow_date);
  $days = $borrowDate->diffInDays($returnDate);
  $denda = $days > 7 ? ($days - 7) * 5000 : 0;
  ```
  Uses borrow_date to return_date difference

- [confirmReturn() method](app/Http/Controllers/Admin/PeminjamanController.php#L185-L192):
  ```php
  $borrowDate = Carbon::parse($peminjaman->borrow_date);
  $days = $borrowDate->diffInDays($today);
  $denda = $days > 7 ? ($days - 7) * 5000 : 0;
  ```
  Uses borrow_date to today's date

**Impact:** Same book could have different fines calculated. The update() method allows custom return dates while confirmReturn() uses current date.

**Affected Files:**
- [app/Http/Controllers/Admin/PeminjamanController.php](app/Http/Controllers/Admin/PeminjamanController.php#L80-L115) - update()
- [app/Http/Controllers/Admin/PeminjamanController.php](app/Http/Controllers/Admin/PeminjamanController.php#L175-L200) - confirmReturn()

**Solution:**
Consolidate fine calculation logic into a helper method or model method to ensure consistency.

---

#### 9. Validation Rules Inconsistency for Phone Number ⚠️ MEDIUM
**Severity:** Medium  
**Impact:** Different phone number formats accepted in different places

**Details:**
- [RegisterController.php](app/Http/Controllers/RegisterController.php#L41):
  ```php
  'hp' => 'required|digits_between:10,13',
  ```
  Accepts 10-13 digits

- [AnggotaController.php](app/Http/Controllers/AnggotaController.php#L46):
  ```php
  'hp' => 'required|string|max:13',
  ```
  Accepts any string up to 13 chars

- [ProfileAnggotaController.php](app/Http/Controllers/ProfileAnggotaController.php#L176):
  ```php
  'hp' => 'required|string|max:13',
  ```
  Same as AnggotaController

**Impact:** Admin can create users with invalid phone numbers (letters, special chars), but registration won't allow it.

**Affected Files:**
- [app/Http/Controllers/RegisterController.php](app/Http/Controllers/RegisterController.php#L41)
- [app/Http/Controllers/AnggotaController.php](app/Http/Controllers/AnggotaController.php#L46)
- [app/Http/Controllers/ProfileAnggotaController.php](app/Http/Controllers/ProfileAnggotaController.php#L176)

**Solution:**
Standardize to: `'hp' => 'required|digits_between:10,13'`

---

#### 10. Missing Transaction in Book Borrowing Process ⚠️ MEDIUM
**Severity:** Medium  
**Impact:** Race condition if two simultaneous requests borrow last book copy

**Details:**
- [ProfileAnggotaController.borrow()](app/Http/Controllers/ProfileAnggotaController.php#L66-L116) doesn't use database transaction:
  ```php
  public function borrow(Request $request) {
      // ... validation ...
      $book = Book::findOrFail($bookId);
      
      if ($book->stock < 1) {
          return back()->with('error', 'Stok buku habis.');
      }
      
      Bookrent::create([...]);  // No transaction
  }
  ```

- Compare with [approve() method](app/Http/Controllers/Admin/PeminjamanController.php#L133) which uses `DB::transaction()` and locks

**Race Condition Scenario:**
1. Book has 1 copy left
2. User A checks: stock = 1 ✓
3. User B checks: stock = 1 ✓
4. User A borrows: stock becomes 0
5. User B borrows: Overwrites the same copy

**Affected Files:**
- [app/Http/Controllers/ProfileAnggotaController.php](app/Http/Controllers/ProfileAnggotaController.php#L66-L116)

**Solution:**
Wrap in transaction with pessimistic locking:
```php
DB::transaction(function () use ($bookId) {
    $book = Book::where('id', $bookId)
        ->lockForUpdate()
        ->first();
    
    if (!$book || $book->stock < 1) {
        throw new \Exception('Stock tidak tersedia');
    }
    
    Bookrent::create([...]);
});
```

---

### LOW ISSUES

#### 11. Book Condition Fields Never Used ⚠️ LOW
**Severity:** Low  
**Impact:** Schema bloat, potential confusion

**Details:**
- [2026_01_13_143717_add_damaged_and_lost_to_books_table.php](database/migrations/2026_01_13_143717_add_damaged_and_lost_to_books_table.php) adds `damaged` and `lost` columns to Book
- [BookController.php](app/Http/Controllers/Admin/BookController.php) accepts them as fillable
- But system uses `condition` field in form instead:
  - [admin/peminjaman/edit.blade.php](resources/views/admin/peminjaman/edit.blade.php#L95-L100):
    ```html
    <select name="condition">
        <option value="rusak">Rusak</option>
        <option value="hilang">Hilang</option>
    </select>
    ```

**Question:** Should damaged/lost be tracked per book or per borrowing transaction?

**Affected Files:**
- [database/migrations/2026_01_13_143717_add_damaged_and_lost_to_books_table.php](database/migrations/2026_01_13_143717_add_damaged_and_lost_to_books_table.php)
- [app/Models/Book.php](app/Models/Book.php#L20-L23) - fillable includes damaged, lost
- [resources/views/admin/peminjaman/edit.blade.php](resources/views/admin/peminjaman/edit.blade.php#L95-L100)

**Solution:**
- Either use damaged/lost columns and add validation
- Or remove them and rely on condition field in bookrent
- Clarify in design documentation

---

#### 12. Duplicate Project Directory ⚠️ LOW
**Severity:** Low  
**Impact:** Maintenance confusion, wasted disk space

**Details:**
- Entire project duplicated in `perpustakaan/` subdirectory
- Contains same app/, database/, routes/, resources/, etc.
- Creates ambiguity about which copy is authoritative

**Affected:** [perpustakaan/](perpustakaan/) subdirectory

**Solution:**
Delete the duplicate directory:
```bash
rm -rf perpustakaan/
```

---

#### 13. Empty KepalaSekolah Controller Directory ⚠️ LOW
**Severity:** Low  
**Impact:** Code clutter, unused files

**Details:**
- [app/Http/Controllers/KepalaSekolah/](app/Http/Controllers/KepalaSekolah/) directory exists but is empty
- Migration [2026_04_26_000002_remove_kepala_sekolah_role.php](database/migrations/2026_04_26_000002_remove_kepala_sekolah_role.php) removed the role from database
- Directory no longer needed

**Solution:**
Delete the unused directory:
```bash
rm -rf app/Http/Controllers/KepalaSekolah/
```

---

#### 14. BookController Missing Validation for Condition Field ⚠️ LOW
**Severity:** Low  
**Impact:** Invalid data can be saved

**Details:**
- [PeminjamanController.edit.blade.php](resources/views/admin/peminjaman/edit.blade.php#L95-L100) accepts condition values: 'baik', 'rusak', 'hilang'
- [PeminjamanController.update()](app/Http/Controllers/Admin/PeminjamanController.php#L82-L84) validates:
  ```php
  'condition' => 'nullable|in:baik,rusak,hilang',
  ```

- But [BookController.php](app/Http/Controllers/Admin/BookController.php) doesn't validate 'damaged' or 'lost' if they become fillable

**Minor Issue:** If damaged/lost fields are ever used in book creation/update, they need validation.

**Affected Files:**
- [app/Http/Controllers/Admin/BookController.php](app/Http/Controllers/Admin/BookController.php#L34-L50)

**Solution:**
Add validation if fields are used:
```php
'damaged' => 'nullable|integer|min:0',
'lost' => 'nullable|integer|min:0',
```

---

#### 15. Route Naming Inconsistency ⚠️ LOW
**Severity:** Low  
**Impact:** Slight confusion in route references

**Details:**
- Some routes use 'tampilan.login' prefix, others don't
- [LoginController.php](app/Http/Controllers/LoginController.php#L55) uses:
  ```php
  return redirect(route('tampilan.login'));
  ```

- But direct route names exist:
  - 'admin.beranda'
  - 'anggota.beranda'
  - 'guru.beranda'

**Solution:**
Consider standardizing all route names to a consistent pattern.

---

#### 16. Seeder Silently Fails on Empty Data ⚠️ LOW
**Severity:** Low  
**Impact:** Incomplete test data without error message

**Details:**
- [BookrentSeeder.php](database/seeders/BookrentSeeder.php#L18-L21):
  ```php
  if ($users->isEmpty() || $books->isEmpty()) {
      return;  // Silent return
  }
  ```
  Returns silently if users or books don't exist, but doesn't log or warn

**Solution:**
Add informative message:
```php
if ($users->isEmpty() || $books->isEmpty()) {
    $this->command->warn('Skipping BookrentSeeder: No users or books found.');
    return;
}
```

---

## Summary Table

| # | Issue | Severity | File(s) | Line(s) |
|---|-------|----------|---------|---------|
| 1 | Missing NISN Database Field | 🔴 Critical | app/Models/User.php, LoginController.php | 19, 25-35 |
| 2 | Seeder Uses Removed Enum | 🔴 Critical | BookrentSeeder.php | 38 |
| 3 | Stock Management Inconsistency | 🟠 High | PeminjamanController.php | 103-106, 190-192 |
| 4 | Wrong Active Borrowing Query | 🟠 High | BerandaAnggotaController.php | 18-21 |
| 5 | View Enum References | 🟠 High | Multiple .blade.php files | 98, 142, 155, 396 |
| 6 | Incomplete Guru Features | 🟠 High | Guru controller dir, web.php | 94-98 |
| 7 | NISN Uniqueness Not Enforced | 🟡 Medium | RegisterController.php | 27-33 |
| 8 | Fine Calculation Inconsistency | 🟡 Medium | PeminjamanController.php | 93-115, 185-200 |
| 9 | Phone Validation Inconsistency | 🟡 Medium | Multiple controllers | 41, 46, 176 |
| 10 | Missing Transaction in Borrow | 🟡 Medium | ProfileAnggotaController.php | 66-116 |
| 11 | Unused Book Condition Fields | 🔵 Low | Migration, BookController.php | - |
| 12 | Duplicate Directory | 🔵 Low | perpustakaan/ | - |
| 13 | Empty KepalaSekolah Dir | 🔵 Low | app/Http/Controllers/KepalaSekolah/ | - |
| 14 | Missing Condition Validation | 🔵 Low | BookController.php | 34-50 |
| 15 | Route Naming Inconsistency | 🔵 Low | web.php, LoginController.php | - |
| 16 | Silent Seeder Failures | 🔵 Low | BookrentSeeder.php | 18-21 |

---

## Recommended Fix Priority

**Immediate (Days 1-2):**
1. ✅ Issue #2: Fix seeder enum value
2. ✅ Issue #1: Add NISN database column
3. ✅ Issue #4: Fix active borrowing query

**High Priority (Week 1):**
4. ✅ Issue #3: Standardize stock management logic
5. ✅ Issue #5: Update view enum references
6. ✅ Issue #10: Add transaction to borrow process

**Medium Priority (Week 2):**
7. ✅ Issue #6: Implement guru-specific features or clarify scope
8. ✅ Issue #8: Consolidate fine calculation logic
9. ✅ Issue #9: Standardize validation rules

**Low Priority (Cleanup):**
10. ✅ Issue #12, #13: Delete duplicate directories
11. ✅ Issue #7, #11, #14-16: Documentation and minor fixes

---

## Testing Recommendations

After fixes, run:
```bash
# Test seeder
php artisan migrate:fresh --seed

# Test login with NISN (once added)
php artisan tinker
> User::create([...with nisn...])

# Test concurrent borrowing
# (use multiple browser tabs simultaneously)

# Run test suite
php artisan test

# Validate enum values
# SELECT DISTINCT status FROM bookrent;
```

---

## Additional Notes

- **NIP Format:** 18 digits with checksum validation (currently implemented)
- **NISN Format:** 10 digits (validated but field missing)
- **Fine Rate:** Rp 5,000 per day (7-day grace period)
- **Book Borrowing Limit:** 3 active per user
- **Fine for Damage:** Rp 50,000 additional
- **Borrow Duration:** 72 hours (calculated in Bookrent model)

---

*End of Report*
