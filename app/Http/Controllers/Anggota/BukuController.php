<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    
     //Halaman daftar buku (anggota)
     
    public function index(Request $request)
    {
        $kategoriId = $request->query('kategori');

        $books = Book::with('category')
            ->where('stock', '>=', 0)
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('category_id', $kategoriId);
            })
            ->orderBy('title', 'asc')
            ->get();
            

        return view('anggota.buku.index', compact('books', 'kategoriId'));
    }

    
     //Detail buku
     
    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);

        return view('anggota.buku.show', compact('book'));
    }
}
