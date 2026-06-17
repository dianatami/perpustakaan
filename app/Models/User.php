<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const ROLE_ANGGOTA = 0;
    public const ROLE_ADMIN = 1;
    public const ROLE_GURU = 2;
    public const NIP_REGEX = '/^\d{18}$/';
    public const NISN_REGEX = '/^\d{10}$/';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "user";
    protected $fillable = [
        'nama',
        'email',
        'nip',
        'nisn',
        'password',
        'hp',
        'status',
        'role',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
        'role' => 'integer',
    ];

    protected $attributes = [
        'status' => 1,
        'role' => self::ROLE_ANGGOTA,
    ];

    /**
     * Relationship: User memiliki banyak Bookrent
     */
    public function bookrent()
    {
        return $this->hasMany(Bookrent::class, 'user_id');
    }

    /**
     * Leaderboard peminjam terbanyak (non-admin).
     * Hanya menghitung peminjaman yang berhasil (status: dipinjam atau kembali)
     */
    public static function leaderboardPeminjam(int $limit = 10)
    {
        return self::query()
            ->leftJoin('bookrent', function ($join) {
                $join->on('user.id', '=', 'bookrent.user_id')
                    ->whereIn('bookrent.status', ['dipinjam', 'kembali']);
            })
            ->selectRaw('user.id, user.nama, user.role, COUNT(bookrent.id) as total_peminjaman, COALESCE(SUM(CASE WHEN bookrent.status = "kembali" THEN 1 ELSE 0 END), 0) as total_dikembalikan')
            ->where(function ($query): void {
                $query->whereNull('user.role')
                    ->orWhere('user.role', '!=', self::ROLE_ADMIN);
            })
            ->groupBy('user.id', 'user.nama', 'user.role')
            ->havingRaw('COUNT(bookrent.id) > 0')
            ->orderByDesc('total_peminjaman')
            ->orderBy('user.nama')
            ->limit($limit)
            ->get();
    }

    /**
     * Detail statistik peminjaman per user (untuk dashboard personal)
     */
    public function statistikPeminjaman()
    {
        $stats = Bookrent::where('user_id', $this->id)
            ->selectRaw('
                COUNT(CASE WHEN status IN ("dipinjam", "kembali") THEN 1 END) as total_berhasil,
                COUNT(CASE WHEN status = "dipinjam" THEN 1 END) as sedang_dipinjam,
                COUNT(CASE WHEN status = "kembali" THEN 1 END) as sudah_dikembalikan,
                COUNT(CASE WHEN status = "ditolak" THEN 1 END) as ditolak,
                COALESCE(SUM(denda), 0) as total_denda
            ')
            ->first();

        return $stats;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasRole(int|string ...$roles): bool
    {
        $allowedRoles = array_map(static fn ($role) => (string) $role, $roles);

        return in_array((string) $this->role, $allowedRoles, true);
    }

    /**
     * Human-readable role label for UI.
     */
    public function roleLabel(): string
    {
        return match ((int) $this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_GURU => 'Guru',
            default => 'Anggota',
        };
    }

    /**
     * Default dashboard route name by role.
     */
    public function dashboardRouteName(): string
    {
        return match ((int) $this->role) {
            self::ROLE_ADMIN => 'admin.beranda',
            self::ROLE_GURU => 'guru.beranda',
            default => 'anggota.beranda',
        };
    }

    /**
     * Validasi format NIP (Nomor Induk Pegawai) - 18 digit
     * Struktur NIP:
     * - Digit 1-4: Tahun lahir (YYYY)
     * - Digit 5-6: Bulan lahir (MM)
     * - Digit 7-8: Tanggal lahir (DD)
     * - Digit 9-12: Tahun keterima sebagai PNS (YYYY)
     * - Digit 13-14: Bulan keterima sebagai PNS (MM)
     * - Digit 15: Gender (1=Pria, 2=Wanita)
     * - Digit 16-18: Nomor urutan (000-999)
     *
     * Persyaratan: Tahun keterima PNS harus tepat 24 tahun setelah tahun lahir
     */
    public static function isValidNip(string $value): bool
    {
        if (preg_match(self::NIP_REGEX, $value) !== 1) {
            return false;
        }

        $birthYear = (int) substr($value, 0, 4);
        $birthMonth = (int) substr($value, 4, 2);
        $birthDay = (int) substr($value, 6, 2);
        $appointYear = (int) substr($value, 8, 4);
        $appointMonth = (int) substr($value, 12, 2);
        $genderDigit = (int) substr($value, 14, 1);

        // Validasi bulan lahir
        if ($birthMonth < 1 || $birthMonth > 12) {
            return false;
        }

        // Validasi tanggal lahir
        if ($birthDay < 1 || $birthDay > 31) {
            return false;
        }

        // Validasi tahun keterima PNS harus tepat 24 tahun setelah tahun lahir
        if ($appointYear !== $birthYear + 24) {
            return false;
        }

        // Validasi bulan keterima PNS
        if ($appointMonth < 1 || $appointMonth > 12) {
            return false;
        }

        // Validasi gender digit (1 atau 2)
        if (! in_array($genderDigit, [1, 2], true)) {
            return false;
        }

        return true;
    }

    public static function isValidNisn(string $value): bool
    {
        return preg_match(self::NISN_REGEX, $value) === 1;
    }

    public static function isValidLoginIdentifier(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false
            || self::isValidNip($value)
            || self::isValidNisn($value);
    }

    public static function resolveRegistrationRole(?string $identifier): int
    {
        $value = trim((string) $identifier);

        if (self::isValidNip($value)) {
            return self::ROLE_GURU;
        }

        return self::ROLE_ANGGOTA;
    }

    /**
     * Normalize role assignment for enum storage.
     */
    public function setRoleAttribute(int|string $value): void
    {
        $this->attributes['role'] = (string) $value;
    }
}
