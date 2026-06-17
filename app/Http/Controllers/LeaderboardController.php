<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Tampilkan halaman leaderboard
     */
    public function index(): View
    {
        $leaderboard = User::leaderboardPeminjam(50);
        $myRank = null;
        
        if (auth()->check()) {
            $myRank = $leaderboard->search(function ($item) {
                return $item->id === auth()->id();
            });
            if ($myRank !== false) {
                $myRank = $myRank + 1;
            }
        }

        return view('leaderboard.index', compact('leaderboard', 'myRank'));
    }

    /**
     * API endpoint untuk live leaderboard (JSON)
     */
    public function live(): JsonResponse
    {
        $items = User::leaderboardPeminjam(10);
        $totalPeserta = $items->count();
        $totalPeminjaman = (int) $items->sum('total_peminjaman');
        $peminjamanTertinggi = (int) max(1, (int) $items->max('total_peminjaman'));

        return response()->json([
            'updated_at' => Carbon::now()->format('d M Y H:i:s'),
            'total_peserta' => $totalPeserta,
            'total_peminjaman' => $totalPeminjaman,
            'peminjaman_tertinggi' => $peminjamanTertinggi,
            'items' => $items->map(static function ($item) {
                return [
                    'id' => (int) $item->id,
                    'nama' => (string) $item->nama,
                    'role' => (string) $item->roleLabel(),
                    'total_peminjaman' => (int) $item->total_peminjaman,
                    'total_dikembalikan' => (int) ($item->total_dikembalikan ?? 0),
                ];
            })->values(),
        ]);
    }

    /**
     * API endpoint untuk get top 3 peminjam (untuk widget kecil)
     */
    public function top3(): JsonResponse
    {
        $items = User::leaderboardPeminjam(3);

        return response()->json([
            'items' => $items->map(static function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'nama' => (string) $item->nama,
                    'total_peminjaman' => (int) $item->total_peminjaman,
                    'medal' => match ($index) {
                        0 => '🥇',
                        1 => '🥈',
                        2 => '🥉',
                        default => '',
                    },
                ];
            })->values(),
        ]);
    }
}
