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
            'has_nip' => 'boolean',
            'role' => 'sometimes|in:0,2',
            'nip' => ['required_if:has_nip,1,true', 'nullable', 'string', function (string $attribute, mixed $value, \Closure $fail): void {
                $identifier = trim((string) $value);

                // Jika field kosong, tidak perlu validasi lebih lanjut
                if ($identifier === '') {
                    return;
                }

                // Jika ada input, harus valid NIP atau NISN
                if (!User::isValidNip($identifier) && !User::isValidNisn($identifier)) {
                    $fail('Format NIP/NISN tidak valid.');
                    return;
                }

                // Cek NIP sudah digunakan
                if (User::isValidNip($identifier)) {
                    if (User::query()->where('nip', $identifier)->exists()) {
                        $fail('NIP sudah digunakan.');
                    }
                    return;
                }

                // Cek NISN sudah digunakan
                if (User::query()->where('nisn', $identifier)->exists()) {
                    $fail('NISN sudah digunakan.');
                }
            }],
            'password' => 'required|min:6',
            'hp' => 'required|digits_between:10,13',
        ]);

        $identifier = trim((string) $request->input('nip'));
        $hasNip = filter_var($request->input('has_nip'), FILTER_VALIDATE_BOOLEAN);

        // Prevent registering with the reserved admin email
        if (strtolower(trim((string) $request->input('email'))) === 'admin@gmail.com') {
            return back()->withErrors(['email' => 'Email ini tidak dapat digunakan. Gunakan alamat email lain.'])->withInput();
        }

        $user = DB::transaction(function () use ($request, $identifier, $hasNip) {
            $nip = null;
            $nisn = null;
            $role = User::ROLE_ANGGOTA;

            if ($hasNip && $identifier !== '') {
                // Jika memiliki NIP/NISN, tentukan role berdasarkan input
                if (User::isValidNip($identifier)) {
                    $nip = $identifier;
                    $role = User::ROLE_GURU;
                } elseif (User::isValidNisn($identifier)) {
                    $nisn = $identifier;
                    $role = User::ROLE_ANGGOTA;
                }
            } else {
                // Jika tidak memiliki NIP/NISN, gunakan role yang dipilih user
                $selectedRole = (int) $request->input('role', User::ROLE_ANGGOTA);
                $role = in_array($selectedRole, [User::ROLE_ANGGOTA, User::ROLE_GURU], true) 
                    ? $selectedRole 
                    : User::ROLE_ANGGOTA;
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
