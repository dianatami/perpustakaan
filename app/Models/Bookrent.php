<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bookrent extends Model
{
    use HasFactory;

    protected $table = 'bookrent';

    protected $fillable = [
        'user_id',
        'borrow_date',
        'return_date',
        'status',
        'denda',
        'jenis_peminjam',
        'di_acc_at',
        'tgl_kembali_maksimal',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'di_acc_at' => 'datetime',
        'tgl_kembali_maksimal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getDueAtAttribute(): ?Carbon
    {
        if (!$this->created_at) {
            return null;
        }

        return $this->created_at->copy()->addHours(72);
    }

    public function hitungSisaWaktu()
    {
        if (!$this->tgl_kembali_maksimal) {
            return null;
        }

        $now = Carbon::now();
        $deadline = Carbon::parse($this->tgl_kembali_maksimal);

        if ($now->greaterThanOrEqualTo($deadline)) {
            return 'Terlambat';
        }

        $diff = $now->diff($deadline);

        $parts = [];
        if ($diff->d > 0) {
            $parts[] = $diff->d . ' hari';
        }
        if ($diff->h > 0) {
            $parts[] = $diff->h . ' jam';
        }
        if ($diff->i > 0) {
            $parts[] = $diff->i . ' menit';
        }

        if (empty($parts)) {
            return $diff->s . ' detik';
        }

        return implode(' ', $parts);
    }

    public function details()
    {
        return $this->hasMany(DetailBookrent::class, 'bookrent_id');
    }
}
