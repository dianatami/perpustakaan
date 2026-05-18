<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBookrent extends Model
{
    
    protected $table = 'detail_bookrent';

    protected $fillable = [
        'bookrent_id',
        'book_id',
        'qty',
        'condition',
    ];

    public function bookrent()
    {
        return $this->belongsTo(Bookrent::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
