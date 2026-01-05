<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Book;

class KategoriController extends Controller
{
     public function index()
    {
        $kategori = Kategori::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_category' => 'required'
        ]);

        Kategori::create($request->only('name_category'));
        return redirect()->back()->with('success','Kategori berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $kat = Kategori::findOrFail($id);
        $kat->delete();
        return redirect()->back()->with('success','Kategori dihapus');
    }

    public function edit($id)
    {
        $kategori = Kategori::with('books')->findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_category' => 'required'
        ]);

        $kat = Kategori::findOrFail($id);
        $kat->update($request->only('name_category'));

        return redirect()->route('admin.kategori.edit', $id)->with('success', 'Kategori diperbarui');
    }

    public function storeBook(Request $request, $id)
    {
        $request->validate([
            'book_code' => 'required',
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        Book::create([
            'category_id' => $id,
            'book_code' => $request->book_code,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'cover' => $request->cover,
            'description' => $request->description,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.kategori.edit', $id)->with('success', 'Buku berhasil ditambahkan');
    }
}