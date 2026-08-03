<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKelasIsSet
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (int)$user->role === User::ROLE_ANGGOTA) {
            $isPilihKelasRoute = $request->routeIs('anggota.pilih-kelas', 'anggota.pilih-kelas.store', 'tampilan.logout');

            if (! $user->hasSelectedKelas() && ! $isPilihKelasRoute) {
                return redirect()->route('anggota.pilih-kelas')
                    ->with('info', 'Silakan pilih kelas Anda terlebih dahulu sebelum menggunakan layanan perpustakaan.');
            }
        }

        return $next($request);
    }
}
