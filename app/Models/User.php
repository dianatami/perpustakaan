<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ANGGOTA = 0;
    public const ROLE_ADMIN = 1;
    public const ROLE_GURU = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "user";
    protected $fillable = [
        'nama',
        'email',
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
     * Normalize role assignment for enum storage.
     */
    public function setRoleAttribute(int|string $value): void
    {
        $this->attributes['role'] = (string) $value;
    }
}
