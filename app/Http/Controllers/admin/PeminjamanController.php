<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookrent;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Bookrent::with(['user', 'book'])->latest('created_at')->paginate(10);
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])->get();
        $books = Book::where('stock', '>', 0)->get();
        return view('admin.peminjaman.index', compact('peminjaman', 'users', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:user,id',
            'book_id'     => 'required|exists:books,id',
            'borrow_date' => 'required|date',
        ]);

        $jumlahDipinjam = Bookrent::where('user_id', $request->user_id)
            ->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
            ->count();

        if ($jumlahDipinjam >= 3) {
            return back()->with('error', 'Anggota sudah meminjam 3 buku.');
        }

        $cekBuku = Bookrent::where('user_id', $request->user_id)
            ->where('book_id', $request->book_id)
            ->whereIn('status', ['menunggu_acc', 'dipinjam', 'proses_kembali'])
            ->exists();

        if ($cekBuku) {
            return back()->with('error', 'Buku ini sudah dipinjam oleh anggota tersebut.');
        }

        $book = Book::findOrFail($request->book_id);

        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku habis');
        }

        Bookrent::create([
            'user_id'     => $request->user_id,
            'book_id'     => $request->book_id,
            'borrow_date' => $request->borrow_date,
            'status'      => 'menunggu_acc',
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibuat. Menunggu ACC.');
    }



    public function edit($id)
    {
        $peminjaman = Bookrent::findOrFail($id);
        $users = User::all();
        $books = Book::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'books'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'     => 'required|exists:user,id',
            'book_id'     => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
            'status'      => 'required|in:menunggu_acc,dipinjam,ditolak,proses_kembali,kembali',
            'condition'   => 'nullable|in:baik,rusak,hilang',
        ]);

        $returnDate = $request->return_date ? Carbon::parse($request->return_date) : Carbon::today();
        $condition = $request->input('condition', 'baik');

        DB::transaction(function () use ($request, $id, $returnDate, $condition): void {
            $peminjaman = Bookrent::with('book')->lockForUpdate()->findOrFail($id);
            $denda = $peminjaman->denda ?? 0;

            if ($request->status === 'kembali') {
                $borrowDate = Carbon::parse($peminjaman->borrow_date);
                $days = $borrowDate->diffInDays($returnDate);

                $denda = $days > 7 ? ($days - 7) * 5000 : 0;

                if ($condition === 'rusak' || $condition === 'hilang') {
                    $denda += 50000;
                }

                if ($peminjaman->status !== 'kembali' && $condition === 'baik' && $peminjaman->book) {
                    $peminjaman->book->stock += 1;
                    $peminjaman->book->save();
                }

                $peminjaman->return_date = $returnDate->toDateString();
                $peminjaman->denda = $denda;
            } else {
                $peminjaman->return_date = $request->return_date;
            }

            $peminjaman->user_id = $request->user_id;
            $peminjaman->book_id = $request->book_id;
            $peminjaman->borrow_date = $request->borrow_date;
            $peminjaman->status = $request->status;
            $peminjaman->save();
        });

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function approve($id)
    {
        $result = DB::transaction(function () use ($id): array {
            $peminjaman = Bookrent::with('book')->lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== 'menunggu_acc') {
                return ['ok' => false, 'message' => 'Peminjaman tidak dalam status menunggu ACC.'];
            }

            $book = Book::query()->lockForUpdate()->findOrFail($peminjaman->book_id);

            if ($book->stock < 1) {
                return ['ok' => false, 'message' => 'Stok buku habis.'];
            }

            $book->stock -= 1;
            $book->save();

            $peminjaman->status = 'dipinjam';
            $peminjaman->borrow_date = Carbon::now()->toDateString();
            $peminjaman->save();

            return ['ok' => true, 'message' => 'Peminjaman berhasil di-ACC.'];
        });

        $flashType = $result['ok'] ? 'success' : 'error';

        return redirect()->route('admin.peminjaman.index')->with($flashType, $result['message']);
    }

    public function reject($id)
    {
        $result = DB::transaction(function () use ($id): array {
            $peminjaman = Bookrent::lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== 'menunggu_acc') {
                return ['ok' => false, 'message' => 'Peminjaman tidak dalam status menunggu ACC.'];
            }

            $peminjaman->status = 'ditolak';
            $peminjaman->save();

            return ['ok' => true, 'message' => 'Peminjaman berhasil ditolak.'];
        });

        $flashType = $result['ok'] ? 'success' : 'error';

        return redirect()->route('admin.peminjaman.index')->with($flashType, $result['message']);
    }

    public function confirmReturn($id)
    {
        $result = DB::transaction(function () use ($id): array {
            $peminjaman = Bookrent::with('book')->lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== 'proses_kembali') {
                return ['ok' => false, 'message' => 'Pengembalian belum diajukan oleh user.'];
            }

            $today = Carbon::today();
            $borrowDate = Carbon::parse($peminjaman->borrow_date);
            $days = $borrowDate->diffInDays($today);
            $denda = $days > 7 ? ($days - 7) * 5000 : 0;

            if ($peminjaman->book) {
                $peminjaman->book->stock += 1;
                $peminjaman->book->save();
            }

            $peminjaman->status = 'kembali';
            $peminjaman->return_date = $today->toDateString();
            $peminjaman->denda = $denda;
            $peminjaman->save();

            $message = $denda > 0
                ? 'Pengembalian dikonfirmasi. Denda: Rp ' . number_format($denda, 0, ',', '.')
                : 'Pengembalian dikonfirmasi.';

            return ['ok' => true, 'message' => $message];
        });

        $flashType = $result['ok'] ? 'success' : 'error';

        return redirect()->route('admin.peminjaman.index')->with($flashType, $result['message']);
    }


    public function destroy($id)
    {
        $peminjaman = Bookrent::findOrFail($id);
        $peminjaman->delete();
        
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
