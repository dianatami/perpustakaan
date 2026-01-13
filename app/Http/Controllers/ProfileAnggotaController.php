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
     * Menampilkan halaman detail profil dengan riwayat peminjaman
     */
    public function profilDetail()
    {
        $user = Auth::user();
        
        // Debug: Cek apakah user login
        if (!$user) {
            return redirect()->route('tampilan.login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Eager load relationship untuk menghindari N+1 query
        $bookrents = $user->bookrent()->with('book')->get();
        
        return view('anggota.Profile.index', [
            'user' => $user,
            'bookrents' => $bookrents
        ]);
    }

    /**
     * Menampilkan form edit profil
     */
    public function editProfil()
    {
        return view('anggota.Profile.edit-profil');
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
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->hp = $request->hp;
        $user->jenis_kelamin = $request->jenis_kelamin;

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nama_foto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/profil', $nama_foto);
            $user->foto = 'profil/' . $nama_foto;
        }

        $user->save();

        return redirect()->route('anggota.profil.detail')->with('success', 'Profil berhasil diperbarui');
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

    /**
     * Menampilkan form edit informasi pribadi
     */
    public function editInfoPribadi()
    {
        return view('anggota.Profile.update-infopribadi');
    }

    /**
     * Menyimpan perubahan informasi pribadi
     */
    public function updateInfoPribadi(Request $request)
    {
        $request->validate([
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        
        // Update data pribadi
        $user->tempat_lahir = $request->tempat_lahir;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->alamat = $request->alamat;

        $user->save();

        return redirect()->route('anggota.profil.detail')->with('success', 'Informasi pribadi berhasil diperbarui');
    }
}
 