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
    $totalAnggota  = User::query()
        ->where(function ($query): void {
            $query->where('role', User::ROLE_ANGGOTA)
                ->orWhere(function ($subQuery): void {
                    $subQuery->whereNull('role')
                        ->whereNotNull('nisn');
                })
                ->orWhere(function ($subQuery): void {
                    $subQuery->whereNotNull('nisn')
                        ->whereNotIn('role', [User::ROLE_GURU, User::ROLE_ADMIN]);
                });
        })
        ->count();
    $totalGuru     = User::query()
        ->where(function ($query): void {
            $query->where('role', User::ROLE_GURU)
                ->orWhere(function ($subQuery): void {
                    $subQuery->whereNull('role')
                        ->whereNotNull('nip');
                })
                ->orWhere(function ($subQuery): void {
                    $subQuery->whereNotNull('nip')
                        ->where('role', '!=', User::ROLE_ADMIN);
                });
        })
        ->count();
    $totalPinjam   = Bookrent::count();
    $stokTersedia  = Book::where('stock', '>', 0)->count();
    $dipinjam      = Bookrent::whereNull('return_date')->count();

    $totalRusakHilang = Book::where('damaged', '>', 0)
        ->orWhere('lost', '>', 0)
        ->count(); 

    $leaderboardSiswa = User::leaderboardPeminjam(10);

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
