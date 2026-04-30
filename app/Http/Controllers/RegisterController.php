<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function register()
    {
        return view('tampilan.register',['judul' => 'REGISTER', ]);
    }

    public function registerProcess(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'nip' => ['nullable', 'string', function (string $attribute, mixed $value, \Closure $fail): void {
                $identifier = trim((string) $value);

                if ($identifier === '') {
                    return;
                }

                if (! User::isValidNip($identifier) && ! User::isValidNisn($identifier)) {
                    $fail('Format NIP/NISN tidak valid.');
                }
            }, 'unique:user,nip'],
            'password' => 'required|min:6',
            'hp' => 'required|digits_between:10,13',
        ]);

        $role = User::isValidNip((string) $request->nip)
            ? User::ROLE_GURU
            : User::ROLE_ANGGOTA;

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nip' => $request->nip,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'status' => 1,
            'role' => $role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $dashboardRoute = $user->dashboardRouteName();

        Session::flash('status', 'success');
        Session::flash('message', 'Register berhasil');

        return redirect()->route($dashboardRoute);
    }
}
