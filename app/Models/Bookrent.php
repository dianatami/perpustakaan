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
        'book_id',
        'borrow_date',
        'return_date',
        'status',
        'denda',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function getDueAtAttribute(): ?Carbon
    {
        if (!$this->created_at) {
            return null;
        }

        return $this->created_at->copy()->addHours(72);
    }
}
