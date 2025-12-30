<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials)){
            if(Auth::user()->status == 0){
                Auth::logout();
                return back()->with('error','User belum aktif');
            }
            $request->session()->regenerate();
           
            if(Auth::user()->role == '1'){
                return redirect()->intended(route('admin.beranda'));
            } else {
                return redirect()->intended(route('anggota.beranda')); // Sesuaikan nama route
            }
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
