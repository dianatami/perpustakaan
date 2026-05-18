<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Bookrent;
use App\Models\DetailBookrent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        
        if (!$user) {
            return redirect()->route('tampilan.login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Eager load relationship untuk menampilkan detail buku di riwayat
        $bookrents = $user->bookrent()->with('details.book')->get();
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
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.qty' => 'required|integer|min:1',
            'borrow_date' => 'nullable|date',
        ]);

        $bookIds = collect($request->books)->pluck('book_id');
        if ($bookIds->duplicates()->count() > 0) {
            return back()->with('error', 'Tidak boleh memilih buku yang sama lebih dari sekali.');
        }

        $activeCount = Bookrent::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        if ($activeCount >= 3) {
            return back()->with('error', 'Anda sudah meminjam 3 buku. Kembalikan dulu sebelum meminjam lagi.');
        }

        $activeRentIds = $user->bookrent()->where('status', 'dipinjam')->pluck('id');

        foreach ($request->books as $item) {
            if (DetailBookrent::whereIn('bookrent_id', $activeRentIds)->where('book_id', $item['book_id'])->exists()) {
                $book = Book::find($item['book_id']);
                return back()->with('error', 'Anda sudah meminjam buku ' . ($book?->title ?? '') . '.');
            }

            $book = Book::findOrFail($item['book_id']);
            if ($item['qty'] > $book->stock) {
                return back()->with('error', 'Stok buku ' . $book->title . ' hanya tersedia ' . $book->stock . '.');
            }
        }

        $borrowDate = $request->borrow_date ? Carbon::parse($request->borrow_date)->toDateString() : Carbon::now()->toDateString();

        $bookrent = Bookrent::create([
            'user_id' => $user->id,
            'borrow_date' => $borrowDate,
            'status' => 'dipinjam',
        ]);

        foreach ($request->books as $item) {
            DetailBookrent::create([
                'bookrent_id' => $bookrent->id,
                'book_id' => $item['book_id'],
                'qty' => $item['qty'],
            ]);

            $book = Book::findOrFail($item['book_id']);
            $book->decrement('stock', $item['qty']);
        }

        return back()->with('success', 'Buku berhasil dipinjam.');
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
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'hp' => 'required|string|max:13',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => ['nullable','date_format:Y-m-d', function ($attribute, $value, $fail) {
                if (!
                    \DateTimeImmutable::createFromFormat('Y-m-d', $value)
                    || \DateTimeImmutable::createFromFormat('Y-m-d', $value)->format('Y-m-d') !== $value
                ) {
                    $fail('Format tanggal lahir tidak valid.');
                }
            }],
            'alamat' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->hp = $request->hp;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->tempat_lahir = $request->tempat_lahir;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->alamat = $request->alamat;

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
 