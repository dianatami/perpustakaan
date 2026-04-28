<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ((int) $user->role === 3) {
                Auth::logout();
                return back()->with('error', 'Akun kepala sekolah sudah tidak didukung. Silakan hubungi admin.');
            }

            if (! $user->status) {
                Auth::logout();
                return back()->with('error', 'User belum aktif');
            }

            $request->session()->regenerate();

            $dashboardRoute = $user->dashboardRouteName();

            if (! Route::has($dashboardRoute)) {
                $dashboardRoute = 'anggota.beranda';
            }

            return redirect()->intended(route($dashboardRoute));
        }

        return back()->with('error', 'Login Gagal');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('tampilan.login'));
    }

}
