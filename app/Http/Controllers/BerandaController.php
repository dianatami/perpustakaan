<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Bookrent;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function berandaAnggota()
    {
        return view('anggota.dashboard',['judul'=> 'Halaman Beranda']);
    }

    public function berandaAdmin()
{
    $totalBuku     = Book::count();
    $totalKategori = Kategori::count();
    $totalAnggota  = User::where('role', '0')->count();
    $totalPinjam   = Bookrent::count();
    $stokTersedia  = Book::where('stock', '>', 0)->count();
    $dipinjam     = Bookrent::whereNull('return_date')->count();

    $totalRusakHilang = Book::where('damaged', '>', 0)
        ->orWhere('lost', '>', 0)
        ->count(); 

    return view(
        'admin.dashboard',
        ['judul'=> 'Halaman Beranda'],
        compact(
            'totalBuku',
            'totalKategori',
            'totalAnggota',
            'totalPinjam',
            'stokTersedia',
            'dipinjam',
            'totalRusakHilang'
        )
    );
}

}
