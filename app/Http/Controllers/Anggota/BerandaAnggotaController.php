<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Bookrent;
use App\Models\User;

class BerandaAnggotaController extends Controller
{
    public function berandaAnggota()
    {
        $bukuTersedia = Book::where('stock', '>', 0)->count();
        $bookrents = Bookrent::where('user_id', auth()->user()->id)
            ->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
            ->count();
        $books = Book::with('category')->latest()->take(10)->get();
        $riyawatPinjam = Bookrent::where('user_id', auth()->user()->id)->count();
        $leaderboardSiswa = User::leaderboardPeminjam(10);

        return view('anggota.dashboard', compact('bukuTersedia', 'bookrents', 'books', 'riyawatPinjam', 'leaderboardSiswa'));
    }


}
