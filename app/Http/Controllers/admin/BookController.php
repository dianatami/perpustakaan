<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($qer) use ($q) {
                $qer->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('book_code', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        $books = $query->orderBy('title')->paginate(10)->withQueryString();
        $categories = Kategori::all();

        return view('admin.data_buku.index', compact('books','categories'))->with('judul','Data Buku');
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('admin.data_buku.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'book_code' => 'required',
            'title' => 'required',
            'author' => 'required',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['category_id','book_code','title','author','publisher','year','cover','description','stock']);
        // pastikan kolom non-nullable seperti `description` diisi (gunakan string kosong jika tidak ada)
        $data['description'] = $request->input('description', '');

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success','Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Kategori::all();
        return view('admin.data_buku.edit', compact('book','categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'book_code' => 'required',
            'title' => 'required',
            'author' => 'required',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable',
        ]);

        $book = Book::findOrFail($id);

        $data = $request->only(['category_id','book_code','title','author','publisher','year','description','stock']);

        if ($request->hasFile('cover')) {
            // hapus cover lama jika ada
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success','Buku berhasil diperbarui');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }
        $book->delete();
        return redirect()->route('admin.books.index')->with('success','Buku berhasil dihapus');
    }
}
