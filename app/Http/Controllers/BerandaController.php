<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Bookrent;
use Illuminate\Http\Request;

class BerandaController extends Controller
{

    public function berandaAdmin()
{
    $totalBuku     = Book::count();
    $totalKategori = Kategori::count();
    $totalAnggota  = User::where('role', User::ROLE_ANGGOTA)->count();
    $totalGuru     = User::where('role', User::ROLE_GURU)->count();
    $totalPinjam   = Bookrent::count();
    $stokTersedia  = Book::where('stock', '>', 0)->count();
    $dipinjam      = Bookrent::whereNull('return_date')->count();

    $totalRusakHilang = Book::where('damaged', '>', 0)
        ->orWhere('lost', '>', 0)
        ->count(); 

    $leaderboardSiswa = User::query()
        ->where('role', User::ROLE_ANGGOTA)
        ->leftJoin('bookrent', 'user.id', '=', 'bookrent.user_id')
        ->selectRaw('user.id, user.nama, COUNT(bookrent.id) as total_peminjaman')
        ->groupBy('user.id', 'user.nama')
        ->orderByDesc('total_peminjaman')
        ->orderBy('user.nama')
        ->take(10)
        ->get();

    return view(
        'admin.dashboard',
        ['judul'=> 'Halaman Beranda'],
        compact(
            'totalBuku',
            'totalKategori',
            'totalAnggota',
            'totalGuru',
            'totalPinjam',
            'stokTersedia',
            'dipinjam',
            'totalRusakHilang',
            'leaderboardSiswa'
        )
    );
}

}
