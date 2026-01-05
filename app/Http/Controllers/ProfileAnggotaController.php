<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileAnggotaController extends Controller
{
    /**
     * Menampilkan halaman profil anggota
     */
    public function profil()
    {
        $anggota = Auth::user();
        return view('anggota.profil', compact('anggota'));
    }

    /**
     * Menampilkan form edit profil
     */
    public function editProfil()
    {
        return view('anggota.edit-profil');
    }

    /**
     * Menyimpan perubahan profil
     */
    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . Auth::id(),
            'hp' => 'required|string|max:13',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->hp = $request->hp;

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nama_foto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/profil', $nama_foto);
            $user->foto = 'profil/' . $nama_foto;
        }

        $user->save();

        return redirect()->route('anggota.profil')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Menampilkan form ubah password
     */
    public function ubahPassword()
    {
        return view('anggota.ubah-password');
    }

    /**
     * Menyimpan password baru
     */
    public function storePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
            'password_baru_confirmation' => 'required|string',
        ]);

        $user = Auth::user();

        // Verifikasi password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai']);
        }

        // Update password baru
        $user->password = Hash::make($request->password_baru);
        $user->save();

        return redirect()->route('anggota.profil')->with('success', 'Password berhasil diubah');
    }

    /**
     * Menampilkan riwayat peminjaman
     */
    public function riwayatPeminjaman()
    {
        $peminjaman = []; // Placeholder untuk riwayat peminjaman
        return view('anggota.riwayat-peminjaman', compact('peminjaman'));
    }
}
