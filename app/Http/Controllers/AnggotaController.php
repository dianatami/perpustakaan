<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index()
    {
        $anggota = User::where('role', '0')->paginate(10);
        return view('admin.user.anggota', compact('anggota'));
    }

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created member in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'hp' => 'required|string|max:13',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password' => bcrypt($request->password),
            'role' => 0, // Anggota
            'status' => 1,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    /**
     * Show the form for editing a member
     */
    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('admin.user.edit', compact('anggota'));
    }

    /**
     * Update the specified member in database
     */
    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id,
            'hp' => 'required|string|max:13',
        ]);

        $anggota->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $anggota->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diperbarui');
    }

    /**
     * Delete a member
     */
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus');
    }

    /**
     * Toggle member status
     */
    public function toggleStatus($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->update(['status' => !$anggota->status]);

        return redirect()->route('admin.anggota.index')->with('success', 'Status anggota berhasil diubah');
    }
}
