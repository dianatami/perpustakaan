<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class AnggotaKategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::with('books')->get();
        return view('anggota.kategori.index', compact('kategori'));
    }
}
