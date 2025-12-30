<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        Kategori::create($request->all());
        return redirect()->back()->with('success','Kategori berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Kategori::find($id)->delete();
        return redirect()->back()->with('success','Kategori dihapus');
    }
}
