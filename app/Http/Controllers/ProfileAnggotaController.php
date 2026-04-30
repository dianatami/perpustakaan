<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileAnggotaController extends Controller
{
    private function portalPrefix(Request $request): string
    {
        return $request->routeIs('guru.*') ? 'guru' : 'anggota';
    }

    private function portalRouteName(Request $request, string $routeSuffix): string
    {
        return $this->portalPrefix($request) . '.' . $routeSuffix;
    }

    /**
     * Menampilkan halaman profil anggota
     */
    public function profil(Request $request)
    {
        return redirect()->route($this->portalRouteName($request, 'profil.detail'));
    }

    /**
     * Menampilkan halaman detail profil dengan riwayat peminjaman
     */
    public function profilDetail(Request $request)
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
            'bookrents' => $bookrents,
            'portalPrefix' => $this->portalPrefix($request),
        ]);
    }

    /**
     * Menampilkan form edit profil
     */
    public function editProfil(Request $request)
    {
        return view('anggota.Profile.edit-profil', [
            'portalPrefix' => $this->portalPrefix($request),
        ]);
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
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $foto = $request->file('foto');
            $nama_foto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/profil', $nama_foto);
            $user->foto = 'profil/' . $nama_foto;
        }

        $user->save();

        return redirect()
            ->route($this->portalRouteName($request, 'profil.detail'))
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Menghapus foto profil user yang sedang login
     */
    public function deleteFoto(Request $request)
    {
        $user = Auth::user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->foto = null;
            $user->save();

            return redirect()
                ->route($this->portalRouteName($request, 'edit.profil'))
                ->with('success', 'Foto profil berhasil dihapus');
        }

        return redirect()
            ->route($this->portalRouteName($request, 'edit.profil'))
            ->with('info', 'Foto profil belum tersedia');
    }

    /**
     * Menampilkan form ubah password
     */
    public function ubahPassword(Request $request)
    {
        return view('anggota.ubah-password', [
            'portalPrefix' => $this->portalPrefix($request),
        ]);
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

        return redirect()
            ->route($this->portalRouteName($request, 'profil.detail'))
            ->with('success', 'Password berhasil diubah');
    }

    /**
     * Menampilkan riwayat peminjaman
     */
    public function riwayatPeminjaman()
    {
        $peminjaman = Auth::user()
            ->bookrent()
            ->with('book')
            ->latest('borrow_date')
            ->get();

        return view('anggota.riwayat-peminjaman', compact('peminjaman'));
    }

    /**
     * Menampilkan form edit informasi pribadi
     */
    public function editInfoPribadi(Request $request)
    {
        return view('anggota.Profile.update-infopribadi', [
            'portalPrefix' => $this->portalPrefix($request),
        ]);
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

        return redirect()
            ->route($this->portalRouteName($request, 'profil.detail'))
            ->with('success', 'Informasi pribadi berhasil diperbarui');
    }
}
 