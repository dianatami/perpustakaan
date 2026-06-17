<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'books';

    protected $fillable = [
        'category_id',
        'rack_id',
        'book_code',
        'title',
        'author',
        'publisher',
        'year',
        'cover',
        'description',
        'stock',
        'damaged',
        'lost',
    ];

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id');
    }
}
