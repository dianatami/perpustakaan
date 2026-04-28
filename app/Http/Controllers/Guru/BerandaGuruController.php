<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Bookrent;
use App\Models\Kategori;
use App\Models\User;

class BerandaGuruController extends Controller
{
    public function berandaGuru()
    {
        $user = auth()->user();

        $bukuTersedia = Book::where('stock', '>', 0)->count();
        $totalKategori = Kategori::count();
        $bookrents = Bookrent::where('user_id', $user->id)
            ->whereNull('return_date')
            ->count();
        $riyawatPinjam = Bookrent::where('user_id', $user->id)->count();
        $books = Book::with('category')->orderByDesc('created_at')->take(10)->get();
        $leaderboardSiswa = User::query()
            ->where('role', User::ROLE_ANGGOTA)
            ->leftJoin('bookrent', 'user.id', '=', 'bookrent.user_id')
            ->selectRaw('user.id, user.nama, COUNT(bookrent.id) as total_peminjaman')
            ->groupBy('user.id', 'user.nama')
            ->orderByDesc('total_peminjaman')
            ->orderBy('user.nama')
            ->take(10)
            ->get();

        return view('guru.dashboard', compact(
            'bukuTersedia',
            'bookrents',
            'books',
            'riyawatPinjam',
            'totalKategori',
            'leaderboardSiswa'
        ));
    }
}