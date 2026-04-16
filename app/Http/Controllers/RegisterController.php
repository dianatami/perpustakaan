<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

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
            'password' => 'required|min:6',
            'hp' => 'required|digits_between:10,13',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'status' => 1,
            'role' => User::ROLE_ANGGOTA,
        ]);
        
        Session::flash('status', 'success');
        Session::flash('message', 'Register berhasil, silakan login');

        return redirect()->route('tampilan.register');
    }
}
