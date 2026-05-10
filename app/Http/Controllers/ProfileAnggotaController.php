<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Bookrent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $activeBorrowings = $bookrents->where('status', 'dipinjam')->values();

        // Buku yang tersedia untuk dipinjam
        $availableBooks = Book::where('stock', '>', 0)->orderBy('title')->get();

        return view('anggota.Profile.index', [
            'user' => $user,
            'bookrents' => $bookrents,
            'activeBorrowings' => $activeBorrowings,
            'availableBooks' => $availableBooks,
            'portalPrefix' => $this->portalPrefix($request),
        ]);
    }

    /**
     * Handle borrow request from anggota/guru profile
     */
    public function borrow(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'nullable|date',
        ]);

        $bookId = $request->book_id;
        $activeStatuses = ['menunggu_acc', 'dipinjam', 'proses_kembali'];

        $activeCount = Bookrent::where('user_id', $user->id)
            ->whereIn('status', $activeStatuses)
            ->count();

        if ($activeCount >= 3) {
            return back()->with('error', 'Anda sudah memiliki 3 pengajuan atau peminjaman aktif.');
        }

        $already = Bookrent::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->whereIn('status', $activeStatuses)
            ->exists();

        if ($already) {
            return back()->with('error', 'Pengajuan atau peminjaman buku ini masih aktif.');
        }

        try {
            $result = DB::transaction(function () use ($user, $bookId, $request) {
                $book = Book::where('id', $bookId)
                    ->lockForUpdate()
                    ->first();

                if (!$book) {
                    throw new \Exception('Buku tidak ditemukan.');
                }

                if ($book->stock < 1) {
                    throw new \Exception('Stok buku habis.');
                }

                $borrowDate = $request->borrow_date
                    ? Carbon::parse($request->borrow_date)->toDateString()
                    : Carbon::now()->toDateString();

                Bookrent::create([
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                    'borrow_date' => $borrowDate,
                    'status' => 'menunggu_acc',
                ]);

                return true;
            });

            return back()->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle return request from anggota/guru history page
     */
    public function returnBook(Request $request, int $bookrentId)
    {
        $user = Auth::user();

        $bookrent = Bookrent::query()
            ->where('id', $bookrentId)
            ->where('user_id', $user->id)
            ->with('book')
            ->first();

        if (! $bookrent) {
            return back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($bookrent->status !== 'dipinjam') {
            return back()->with('error', 'Pengembalian hanya bisa diajukan untuk buku yang sedang dipinjam.');
        }

        DB::transaction(function () use ($bookrent): void {
            $lockedBookrent = Bookrent::query()->where('id', $bookrent->id)->lockForUpdate()->first();

            if (! $lockedBookrent || $lockedBookrent->status !== 'dipinjam') {
                return;
            }

            $lockedBookrent->status = 'proses_kembali';
            $lockedBookrent->save();
        });

        return back()->with('success', 'Permintaan pengembalian berhasil dikirim. Menunggu konfirmasi admin.');
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
            'hp' => 'required|digits_between:10,13',
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
            ->latest('created_at')
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
            'tanggal_lahir' => ['nullable', 'date_format:Y-m-d', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! $this->isValidCalendarDate((string) $value)) {
                    $fail('Format tanggal lahir tidak valid.');
                }
            }],
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

    private function isValidCalendarDate(string $value): bool
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);

        return $date !== false && $date->format('Y-m-d') === $value;
    }
}
 