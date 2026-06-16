<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-\']+$/i',
            'email' => 'required|email|unique:user,email',
            'nip' => ['nullable', 'string', function (string $attribute, mixed $value, \Closure $fail): void {
                $identifier = trim((string) $value);

                if ($identifier === '') {
                    return;
                }

                if (!User::isValidNip($identifier) && !User::isValidNisn($identifier)) {
                    $fail('Format NIP/NISN tidak valid.');
                    return;
                }

                if (User::isValidNip($identifier)) {
                    if (User::query()->where('nip', $identifier)->exists()) {
                        $fail('NIP sudah digunakan.');
                    }
                    return;
                }

                if (User::query()->where('nisn', $identifier)->exists()) {
                    $fail('NISN sudah digunakan.');
                }
            }],
            'password' => 'required|min:6',
            'hp' => 'required|digits_between:10,13',
        ]);

        $identifier = trim((string) $request->input('nip'));
        // Prevent registering with the reserved admin email
        if (strtolower(trim((string) $request->input('email'))) === 'admin@gmail.com') {
            return back()->withErrors(['email' => 'Email ini tidak dapat digunakan. Gunakan alamat email lain.'])->withInput();
        }

        $user = DB::transaction(function () use ($request, $identifier) {
            $nip = null;
            $nisn = null;
            $role = User::ROLE_ANGGOTA;

            if ($identifier !== '') {
                if (User::isValidNip($identifier)) {
                    $nip = $identifier;
                    $role = User::ROLE_GURU;
                } elseif (User::isValidNisn($identifier)) {
                    $nisn = $identifier;
                    $role = User::ROLE_ANGGOTA;
                }
            }

            return User::create([
                'nama' => trim((string) $request->input('nama')),
                'email' => trim((string) $request->input('email')),
                'nip' => $nip,
                'nisn' => $nisn,
                'password' => bcrypt($request->input('password')),
                'hp' => trim((string) $request->input('hp')),
                'status' => 1,
                'role' => $role,
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();

        $dashboardRoute = $user->dashboardRouteName();

        Session::flash('status', 'success');
        Session::flash('message', 'Register berhasil');

        return redirect()->route($dashboardRoute);
    }
}
