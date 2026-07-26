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
    $totalAnggota  = User::where('role', '0')->count();
    $totalGuru     = User::where('role', User::ROLE_GURU)->count();
    $totalPinjam   = Bookrent::count();
    $stokTersedia  = Book::where('stock', '>', 0)->count();
    $dipinjam      = Bookrent::whereNull('return_date')->count();

    $totalRusakHilang = Book::where('damaged', '>', 0)
        ->orWhere('lost', '>', 0)
        ->count(); 

    $leaderboardGuruFull = User::leaderboardByRole(User::ROLE_GURU, 0);
    $leaderboardSiswaFull = User::leaderboardByRole(User::ROLE_ANGGOTA, 0);

    $leaderboardGuru = $leaderboardGuruFull->take(10);
    $leaderboardSiswa = $leaderboardSiswaFull->take(10);

    $totalGuruAktif = $leaderboardGuruFull->count();
    $totalSiswaAktif = $leaderboardSiswaFull->count();

    $totalPeminjamanGuruLb = $leaderboardGuruFull->sum('total_peminjaman');
    $totalPeminjamanSiswaLb = $leaderboardSiswaFull->sum('total_peminjaman');

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
            'leaderboardGuru',
            'leaderboardSiswa',
            'totalGuruAktif',
            'totalSiswaAktif',
            'totalPeminjamanGuruLb',
            'totalPeminjamanSiswaLb'
        )
    );
}

}
