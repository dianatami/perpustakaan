<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookrent;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Bookrent::with (['user', 'book'])->paginate(10);
        $users = User::whereIn('role', [User::ROLE_ANGGOTA, User::ROLE_GURU])->get();
        $books = Book::where('stock', '>', 0)->get();
        return view('admin.peminjaman.index', compact('peminjaman', 'users', 'books'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'user_id'     => 'required|exists:user,id',
        'book_id'     => 'required|exists:books,id',
        'borrow_date' => 'required|date',
    ]);

    $jumlahDipinjam = Bookrent::where('user_id', $request->user_id)
        ->where('status', 'dipinjam')
        ->count();

    if ($jumlahDipinjam >= 3) {
        return back()->with('error', 'Anggota sudah meminjam 3 buku.');
    }

    $cekBuku = Bookrent::where('user_id', $request->user_id)
    ->where('book_id', $request->book_id)
    ->where('status', 'dipinjam')
    ->exists();

    if ($cekBuku) {
    return back()->with('error', 'Buku ini sudah dipinjam oleh anggota tersebut.');
    }


    // 1️⃣ Ambil data buku
    $book = Book::findOrFail($request->book_id);

    // 2️⃣ Cek stok
    if ($book->stock < 1) {
        return back()->with('error', 'Stok buku habis');
    }

    // 3️⃣ Kurangi stok
    $book->stock -= 1;
    $book->save();

    // 4️⃣ Simpan peminjaman
    Bookrent::create([
        'user_id'     => $request->user_id,
        'book_id'     => $request->book_id,
        'borrow_date' => $request->borrow_date,
        'status'      => 'dipinjam',
    ]);

    return redirect()->route('admin.peminjaman.index')
        ->with('success', 'Buku berhasil dipinjam');
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
        'user_id'     => 'required|exists:user,id',
        'book_id'     => 'required|exists:books,id',
        'borrow_date' => 'required|date',
        'return_date' => 'required|date',
        'status'      => 'required|in:dipinjam,dikembalikan',
        'condition'   => 'required|in:baik,rusak,hilang',
    ]);

    $peminjaman = Bookrent::with('book')->findOrFail($id);

    $denda = 0;

    if ($request->status === 'dikembalikan') {
        $borrowDate = Carbon::parse($peminjaman->borrow_date);
        $returnDate = Carbon::parse($request->return_date);
        $days = $borrowDate->diffInDays($returnDate);

        // DENDA TELAT
        if ($days > 7) {
            $denda += ($days - 7) * 5000;
        }

        // DENDA KONDISI
        if ($request->condition === 'rusak' || $request->condition === 'hilang') {
            $denda += 50000;
        }

        //  UPDATE STOK
        if ($request->condition === 'baik') {
            $peminjaman->book->stock += 1;
        }

        $peminjaman->book->save();
    }

    $peminjaman->update([
        'user_id'     => $request->user_id,
        'book_id'     => $request->book_id,
        'borrow_date' => $request->borrow_date,
        'return_date' => $request->return_date,
        'status'      => $request->status,
        'denda'       => $denda,
    ]);

    return redirect()->route('admin.peminjaman.index')
        ->with('success', 'Buku dikembalikan. Denda: Rp ' . number_format($denda, 0, ',', '.'));
}


    public function destroy($id)
    {
        $peminjaman = Bookrent::findOrFail($id);
        $peminjaman->delete();
        
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
