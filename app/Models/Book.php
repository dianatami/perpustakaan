<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'category_id',
        'book_code',
        'title',
        'author',
        'publisher',
        'year',
        'cover',
        'description',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }
}
