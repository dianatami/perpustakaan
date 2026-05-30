<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Book;
use App\Models\Rack;

class KategoriController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 20, 50]) ? $perPage : 10;

        $kategori = Kategori::when($search, function ($query, $search) {
                $query->where('name_category', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.kategori.index', compact('kategori', 'search', 'perPage'));
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
        $kategori = Kategori::with('books.rack')->findOrFail($id);
        $racks = Rack::orderBy('code')->get();
        return view('admin.kategori.edit', compact('kategori', 'racks'));
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
        'rack_id' => 'nullable|exists:racks,id',
        'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $coverPath = null;

    if ($request->hasFile('cover')) {

        $coverPath = $request->file('cover')->store('covers', 'public');

    }

    Book::create([
        'category_id' => $id,
        'rack_id' => $request->rack_id,
        'book_code' => $request->book_code,
        'title' => $request->title,
        'author' => $request->author,
        'publisher' => $request->publisher,
        'year' => $request->year,
        'cover' => $coverPath,
        'description' => $request->description,
        'stock' => $request->stock,
    ]);

    return redirect()
        ->route('admin.kategori.edit', $id)
        ->with('success', 'Buku berhasil ditambahkan');
    }
}