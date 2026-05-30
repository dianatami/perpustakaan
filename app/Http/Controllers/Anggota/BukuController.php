<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Kategori;
use App\Models\Rack;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    
     //Halaman daftar buku (anggota)
     
    public function index(Request $request)
    {
        $kategoriId = $request->query('kategori');
        $rackId = $request->query('rack');

        $books = Book::with(['category', 'rack'])
            ->where('stock', '>=', 0)
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('category_id', $kategoriId);
            })
            ->when($rackId, function ($query) use ($rackId) {
                $query->where('rack_id', $rackId);
            })
            ->orderBy('title', 'asc')
            ->get();

        $categories = Kategori::orderBy('name_category')->get();
        $racks = Rack::where('is_active', true)->orderBy('code')->get();
        $bookCount = $books->count();

        return view('anggota.buku.index', compact('books', 'kategoriId', 'rackId', 'categories', 'racks', 'bookCount'));
    }

    
     //Detail buku
     
    public function show($id)
    {
        $book = Book::with(['category', 'rack'])->findOrFail($id);

        return view('anggota.buku.show', compact('book'));
    }
}
