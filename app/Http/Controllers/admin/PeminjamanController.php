<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Bookrent;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Bookrent::with(['user', 'book'])->paginate(10);
        $users = User::all();
        $books = Book::where('stock', '>', 0)->get();
        return view('admin.peminjaman.index', compact('peminjaman', 'users', 'books'));
    }

    public function create()
    {
        $users = User::all();
        $books = Book::where('stock', '>', 0)->get();
        return view('admin.peminjaman.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date|after:borrow_date',
        ]);

        Bookrent::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'status' => 'dipinjam',
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan');
    }

    public function edit($id)
    {
        $peminjaman = Bookrent::findOrFail($id);
        $users = User::all();
        $books = Book::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'books'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan',
        ]);

        $peminjaman = Bookrent::findOrFail($id);
        $peminjaman->update($request->all());

        $book = $peminjaman->book;

        if ($request->condition == 'baik') {
        $book->stock += 1;
        }

        if ($request->condition == 'rusak') {
        $book->damaged += 1;
        }

        if ($request->condition == 'hilang') {
        $book->lost += 1;
        }

        $book->save();
        
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui');
    }

    public function destroy($id)
    {
        $peminjaman = Bookrent::findOrFail($id);
        $peminjaman->delete();
        
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
