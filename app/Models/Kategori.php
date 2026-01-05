<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name_category',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'category_id');
    }
}
