<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


class LoginController extends Controller
{
    public function login()
    {
        return view('tampilan.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $identifier = trim($credentials['identifier']);
        if (! User::isValidLoginIdentifier($identifier)) {
            return back()->with('error', 'Format NIP/NISN tidak valid.');
        }

        $user = User::query()
            ->where('email', $identifier)
            ->first();

        if (! $user) {
            $user = User::query()
                ->where('nip', $identifier)
                ->first();
        }

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Login Gagal');
        }

        if ((int) $user->role === 3) {
            return back()->with('error', 'Akun kepala sekolah sudah tidak didukung. Silakan hubungi admin.');
        }

        if (! $user->status) {
            return back()->with('error', 'User belum aktif');
        }

        Auth::login($user);
        $request->session()->regenerate();

        $dashboardRoute = $user->dashboardRouteName();

        if (! Route::has($dashboardRoute)) {
            $dashboardRoute = 'anggota.beranda';
        }

        return redirect()->route($dashboardRoute);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('tampilan.login'));
    }

}
