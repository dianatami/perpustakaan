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
        $user = auth()->user();
        $bukuTersedia = Book::where('stock', '>', 0)->count();
        $bookrents = Bookrent::where('user_id', auth()->user()->id)
            ->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
            ->count();
        $books = Book::with('category')->latest()->take(10)->get();
        $riyawatPinjam = Bookrent::where('user_id', auth()->user()->id)->count();
        $leaderboardSiswaFull = User::leaderboardByRole(User::ROLE_ANGGOTA, 0);

        $myRankSiswa = $leaderboardSiswaFull->search(function ($item) use ($user) {
            return $item->id === $user->id;
        });
        if ($myRankSiswa !== false) {
            $myRankSiswa += 1;
        }

        $myTotalPeminjamanSiswa = $leaderboardSiswaFull->firstWhere('id', $user->id)->total_peminjaman ?? 0;

        $leaderboardSiswa = $leaderboardSiswaFull->take(10);
        $totalSiswaAktif = $leaderboardSiswaFull->count();
        $totalPeminjamanSiswaLb = $leaderboardSiswaFull->sum('total_peminjaman');

        return view('anggota.dashboard', compact(
            'bukuTersedia', 'bookrents', 'books', 'riyawatPinjam', 
            'leaderboardSiswa', 'totalSiswaAktif', 'totalPeminjamanSiswaLb',
            'myRankSiswa', 'myTotalPeminjamanSiswa'
        ));
    }


}
