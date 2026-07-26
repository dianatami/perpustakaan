<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LeaderboardController extends Controller
{


    /**
     * API endpoint untuk live leaderboard (JSON)
     */
    public function live(): JsonResponse
    {
        $guru = User::leaderboardByRole(User::ROLE_GURU, 10);
        $siswa = User::leaderboardByRole(User::ROLE_ANGGOTA, 10);

        $mapItem = static function ($item) {
            return [
                'id' => (int) $item->id,
                'nama' => (string) $item->nama,
                'role' => (string) $item->roleLabel(),
                'nisn' => (string) ($item->nisn ?? '-'),
                'total_peminjaman' => (int) $item->total_peminjaman,
            ];
        };

        return response()->json([
            'updated_at' => Carbon::now()->format('d M Y H:i:s'),
            'guru' => [
                'total_peserta' => $guru->count(),
                'total_peminjaman' => (int) $guru->sum('total_peminjaman'),
                'items' => $guru->map($mapItem)->values(),
            ],
            'siswa' => [
                'total_peserta' => $siswa->count(),
                'total_peminjaman' => (int) $siswa->sum('total_peminjaman'),
                'items' => $siswa->map($mapItem)->values(),
            ]
        ]);
    }

    /**
     * API endpoint untuk get top 3 peminjam (untuk widget kecil)
     */
    public function top3(): JsonResponse
    {
        $items = User::leaderboardByRole(User::ROLE_ANGGOTA, 3);

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
