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

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false) {
            $user = User::query()
                ->where('email', $identifier)
                ->first();
        } else {
            $user = User::query()
                ->where('nip', $identifier)
                ->first();
        }

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Login Gagal');
        }

        if (! $user->status) {
            return back()->with('error', 'User belum aktif');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return match ((int) $user->role) {
            User::ROLE_ADMIN => redirect('/admin/beranda'),
            User::ROLE_GURU => redirect('/guru/beranda'),
            default => redirect('/anggota/beranda'),
        };
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('tampilan.login'));
    }

}
