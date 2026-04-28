<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Bookrent;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BerandaKepalaSekolahController extends Controller
{
    public function berandaKepala()
    {
        $totalBuku = Book::count();
        $totalKategori = Kategori::count();
        $totalSiswa = User::where('role', User::ROLE_ANGGOTA)->count();
        $totalGuru = User::where('role', User::ROLE_GURU)->count();
        $totalPenggunaAktif = User::whereIn('role', [User::ROLE_ANGGOTA, User::ROLE_GURU])
            ->where('status', true)
            ->count();

        $totalPeminjaman = Bookrent::count();
        $peminjamanAktif = Bookrent::where('status', 'dipinjam')->count();
        $peminjamanSelesai = Bookrent::where('status', 'dikembalikan')->count();

        $peminjamanTerlambat = Bookrent::where('status', 'dipinjam')
            ->whereDate('borrow_date', '<', Carbon::now()->subDays(7)->toDateString())
            ->count();

        $stokTersedia = (int) Book::sum('stock');
        $stokRusakHilang = (int) Book::sum(DB::raw('COALESCE(damaged, 0) + COALESCE(lost, 0)'));
        $kesehatanKoleksi = ($stokTersedia + $stokRusakHilang) > 0
            ? round(($stokTersedia / ($stokTersedia + $stokRusakHilang)) * 100)
            : 100;

        $kategoriPopuler = Kategori::withCount('books')
            ->orderByDesc('books_count')
            ->take(5)
            ->get();

        $peminjamanTerbaru = Bookrent::with(['user', 'book'])
            ->latest()
            ->take(7)
            ->get();

        return view('kepala.dashboard', compact(
            'totalBuku',
            'totalKategori',
            'totalSiswa',
            'totalGuru',
            'totalPenggunaAktif',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanSelesai',
            'peminjamanTerlambat',
            'stokTersedia',
            'stokRusakHilang',
            'kesehatanKoleksi',
            'kategoriPopuler',
            'peminjamanTerbaru'
        ));
    }
}