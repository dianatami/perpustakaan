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
            ->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
            ->count();
        $riyawatPinjam = Bookrent::where('user_id', $user->id)->count();
        $books = Book::with('category')->orderByDesc('created_at')->take(10)->get();
        $leaderboardGuruFull = User::leaderboardByRole(User::ROLE_GURU, 0);

        $myRankGuru = $leaderboardGuruFull->search(function ($item) use ($user) {
            return $item->id === $user->id;
        });
        if ($myRankGuru !== false) {
            $myRankGuru += 1;
        }

        $myTotalPeminjamanGuru = $leaderboardGuruFull->firstWhere('id', $user->id)->total_peminjaman ?? 0;

        $leaderboardGuru = $leaderboardGuruFull->take(10);
        $totalGuruAktif = $leaderboardGuruFull->count();
        $totalPeminjamanGuruLb = $leaderboardGuruFull->sum('total_peminjaman');

        return view('guru.dashboard', compact(
            'bukuTersedia', 'bookrents', 'books', 'riyawatPinjam', 'totalKategori',
            'leaderboardGuru', 'totalGuruAktif', 'totalPeminjamanGuruLb',
            'myRankGuru', 'myTotalPeminjamanGuru'
        ));
    }
}