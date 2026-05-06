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

        $identifier = trim((string) $request->input('nip'));
        // Prevent registering with the reserved admin email
        if (strtolower(trim((string) $request->input('email'))) === 'admin@gmail.com') {
            return back()->withErrors(['email' => 'Email ini tidak dapat digunakan. Gunakan alamat email lain.'])->withInput();
        }

        $user = DB::transaction(function () use ($request, $identifier) {
            return User::create([
                'nama' => trim((string) $request->input('nama')),
                'email' => trim((string) $request->input('email')),
                'nip' => $identifier !== '' ? $identifier : null,
                'password' => bcrypt($request->input('password')),
                'hp' => trim((string) $request->input('hp')),
                'status' => 1,
                'role' => User::resolveRegistrationRole($identifier),
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
