<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\session;
use App\Models\User;

class RegisterController extends Controller
{
    public function register()
    {
        return view('tampilan.register',['judul' => 'REGISTER', ]);
    }

    public function registerProcess(Request $request)
    {
        $request->validate=([
            'nama' => 'required', 
            'email' => 'required',
            'password' => 'required',
            'hp' => 'required|numeric|max:13']);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
        ]);
        
        session::flash('status','success');
        session::flash('message','Register berhasil, silakan login');
        return redirect('tampilan/register');
    }
}
