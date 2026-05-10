<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class LeaderboardController extends Controller
{
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
                    'total_peminjaman' => (int) $item->total_peminjaman,
                ];
            })->values(),
        ]);
    }
}
