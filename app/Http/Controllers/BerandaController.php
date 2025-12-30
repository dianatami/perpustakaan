<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function berandaAnggota()
    {
        return view('anggota.dashboard',['judul'=> 'Halaman Beranda']);
    }

    public function berandaAdmin()
    {
        return view('admin.dashboard',['judul'=> 'Halaman Beranda']);
    }
}
