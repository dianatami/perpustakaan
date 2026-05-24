<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookrent;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\DetailBookrent;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Bookrent::with(['user','details.book'])->latest('created_at')->paginate(10);
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])->get();
        $books = Book::all();

        return view('admin.peminjaman.index', compact('peminjaman', 'users', 'books'));
    }

    public function create()
    {
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])
                    ->orderBy('nama')
                    ->get();

        $books = Book::where('stock', '>=', 0)
                    ->orderBy('title')
                    ->get();
       return view('admin.peminjaman.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:user,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.qty' => 'required|integer|min:1',
        ]);

        // =====================================================
        // VALIDASI DUPLIKAT BUKU
        // =====================================================

        $bookIds = collect($request->books)
                    ->pluck('book_id');
        if($bookIds->duplicates()->count() > 0){
            return back()
                    ->withInput()
                    ->with('error', 'Tidak boleh memilih buku yang sama');
        }

        // =====================================================
        // VALIDASI STOK
        // =====================================================

        foreach ($request->books as $item) {
            $book = Book::findOrFail($item['book_id']);
            if($item['qty'] > $book->stock){
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Stok buku '.$book->title.' hanya tersedia '.$book->stock
                    );
            }
        }

        // =====================================================
        // SIMPAN PEMINJAMAN
        // =====================================================

        $peminjaman = Bookrent::create([
            'user_id' => $request->user_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'status' => 'dipinjam',
        ]);

        // =====================================================
        // DETAIL PEMINJAMAN
        // =====================================================

        foreach ($request->books as $item) {
            DetailBookrent::create([
                'bookrent_id' => $peminjaman->id,
                'book_id' => $item['book_id'],
                'qty' => $item['qty'],
            ]);

            // kurangi stok
            $book = Book::find($item['book_id']);
            $book->decrement('stock', $item['qty']);
        }

        return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Peminjaman berhasil ditambahkan');
    }



    public function edit($id)
    {
        $peminjaman = Bookrent::with('details.book')->findOrFail($id);
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])
                    ->orderBy('nama')
                    ->get();
        $books = Book::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'books'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:user,id',
        'borrow_date' => 'required|date',
        'return_date' => 'nullable|date',
        'status' => 'required',
    ]);

    $peminjaman = Bookrent::with('details.book')
        ->findOrFail($id);

    // simpan status lama
    $statusLama = $peminjaman->status;

    // update data utama
    $peminjaman->update([
        'user_id' => $request->user_id,
        'borrow_date' => $request->borrow_date,
        'return_date' => $request->return_date,
        'status' => $request->status,
        'denda' => $request->denda ?? 0,
    ]);

    // update kondisi buku
    foreach ($peminjaman->details as $detail) {

    $condition = $request->conditions[$detail->id] ?? 'baik';

    if ($condition == 'rusak') {

        $detail->book->increment('damaged', $detail->qty);

    }

    if ($condition == 'hilang') {

        $detail->book->increment('lost', $detail->qty);

    }

}

    // kembalikan stok kalau status kembali
    if (
    $request->status == 'kembali' &&
    $statusLama != 'kembali'
) {

    foreach ($peminjaman->details as $detail) {

        $condition =
            $request->conditions[$detail->id]
            ?? 'baik';

        // ======================
        // KONDISI BAIK
        // ======================

        if ($condition == 'baik') {

            $detail->book->increment(
                'stock',
                $detail->qty
            );
        }

        // ======================
        // KONDISI RUSAK
        // ======================

        elseif ($condition == 'rusak') {

            $detail->book->increment(
                'damaged',
                $detail->qty
            );
        }

        // ======================
        // KONDISI HILANG
        // ======================

        elseif ($condition == 'hilang') {

            $detail->book->increment(
                'lost',
                $detail->qty
            );
        }
    }
}
    return redirect()
        ->route('admin.peminjaman.index')
        ->with(
            'success',
            'Data peminjaman berhasil diperbarui'
        );
}

    public function approve(Request $request, $id)
    {
        $request->validate([
            'borrow_duration' => 'required|integer|min:1|max:30',
        ]);

        $result = DB::transaction(function () use ($request, $id): array {
            $peminjaman = Bookrent::with('details.book')->lockForUpdate()->findOrFail($id);
            if ($peminjaman->status !== 'menunggu_acc') {
                return ['ok' => false, 'message' => 'Peminjaman tidak dalam status menunggu ACC.'];
            }

            // Validate stock for all books
            foreach ($peminjaman->details as $detail) {
                if ($detail->book->stock < $detail->qty) {
                    return ['ok' => false, 'message' => 'Stok buku ' . $detail->book->title . ' tidak cukup.'];
                }
            }

            // Decrease stock for each book
            foreach ($peminjaman->details as $detail) {
                $detail->book->decrement('stock', $detail->qty);
            }

            $borrowDate = Carbon::now()->toDateString();
            $returnDate = Carbon::now()->addDays($request->borrow_duration)->toDateString();

            $peminjaman->status = 'dipinjam';
            $peminjaman->borrow_date = $borrowDate;
            $peminjaman->return_date = $returnDate;
            $peminjaman->save();

            return ['ok' => true, 'message' => 'Pengajuan peminjaman berhasil disetujui. Murid diminta datang ke perpustakaan untuk mengambil buku.'];
        });

        $flashType = $result['ok'] ? 'success' : 'error';
        return redirect()->route('admin.peminjaman.index')->with($flashType, $result['message']);
    }

    public function reject($id)
    {
        $result = DB::transaction(function () use ($id): array {
            $peminjaman = Bookrent::with('details.book')->lockForUpdate()->findOrFail($id);
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

    public function confirmReturn(Request $request, $id)
    {
    $result = DB::transaction(function () use ($request, $id): array {

        $peminjaman = Bookrent::with('details.book')
            ->lockForUpdate()
            ->findOrFail($id);
        if ($peminjaman->status !== 'proses_kembali') {
            return [
                'ok' => false,
                'message' => 'Pengembalian belum diajukan oleh user.'
            ];
        }
        $today = Carbon::today();
        $borrowDate = Carbon::parse($peminjaman->borrow_date);

        // If admin supplied a denda explicitly, use it
        if ($request->filled('denda')) {
            $denda = (int) $request->input('denda');
        } else {
            // compute late fee
            $days = $borrowDate->diffInDays($today);
            $late = $days > 7 ? ($days - 7) * 5000 : 0;

            // compute damage/loss fees based on provided conditions or existing
            $conditions = $request->input('conditions', []);
            $damageLoss = 0;
            foreach ($peminjaman->details as $detail) {
                $cond = $conditions[$detail->id] ?? ($detail->condition ?? 'baik');
                if (in_array($cond, ['rusak', 'hilang'])) {
                    $damageLoss += 50000 * ($detail->qty ?? 1);
                }
            }

            $denda = $late + $damageLoss;
        }

        // =========================================
        // KEMBALIKAN STOK SEMUA BUKU
        // =========================================

        foreach ($peminjaman->details as $detail) {
            // use submitted condition if available
            $condition = $request->input('conditions.' . $detail->id) ?? ($detail->condition ?? 'baik');

            if ($condition === 'rusak') {
                $detail->book->increment('damaged', $detail->qty);
                continue;
            }

            if ($condition === 'hilang') {
                $detail->book->increment('lost', $detail->qty);
                continue;
            }

            $detail->book->increment('stock', $detail->qty);
        }

        // =========================================
        // UPDATE STATUS
        // =========================================

        $peminjaman->status = 'kembali';
        $peminjaman->return_date = $today->toDateString();
        $peminjaman->denda = $denda;
        $peminjaman->save();
        $message = $denda > 0
            ? 'Pengembalian dikonfirmasi. Denda: Rp ' .
                number_format($denda, 0, ',', '.')
            : 'Pengembalian dikonfirmasi.';
        return [
            'ok' => true,
            'message' => $message
        ];
    });
    $flashType =
        $result['ok']
            ? 'success'
            : 'error';
    return redirect()
        ->route('admin.peminjaman.index')
        ->with(
            $flashType,
            $result['message']
        );
}

    /**
     * Calculate fine based on borrow date, return date, and condition
     * 
     * Business rules:
     * - Grace period: 7 days
     * - Late fee: Rp 5,000 per day after grace period
     * - Damage/Loss fee: Rp 50,000
     */
    private function calculateFine(string|Carbon $borrowDate, string|Carbon $returnDate, string $condition = 'baik'): int
    {
        $borrow = $borrowDate instanceof Carbon ? $borrowDate : Carbon::parse($borrowDate);
        $return = $returnDate instanceof Carbon ? $returnDate : Carbon::parse($returnDate);

        $days = $borrow->diffInDays($return);
        $denda = $days > 7 ? ($days - 7) * 5000 : 0;

        if ($condition === 'rusak' || $condition === 'hilang') {
            $denda += 50000;
        }

        return $denda;
    }

    public function destroy($id)
    {
        $peminjaman = Bookrent::with('details')->findOrFail($id);
        $peminjaman->delete();
        
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }

    public function processReturn($id)
    {
        $peminjaman = Bookrent::with('details.book','user')->findOrFail($id);
        return view('admin.peminjaman.return', compact('peminjaman'));
    }

    /**
     * AJAX: calculate fine for given return date and conditions
     */
    public function calculateFineAjax(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'conditions' => 'sometimes|array',
        ]);

        $peminjaman = Bookrent::with('details')->findOrFail($id);

        $borrowDate = Carbon::parse($peminjaman->borrow_date);
        $returnDate = Carbon::parse($request->return_date);

        $days = $borrowDate->diffInDays($returnDate);
        $late = $days > 7 ? ($days - 7) * 5000 : 0;

        $conditions = $request->input('conditions', []);
        $damageLoss = 0;

        foreach ($peminjaman->details as $detail) {
            $cond = $conditions[$detail->id] ?? ($detail->condition ?? 'baik');
            if (in_array($cond, ['rusak', 'hilang'])) {
                $damageLoss += 50000 * ($detail->qty ?? 1);
            }
        }

        $total = $late + $damageLoss;

        return response()->json([
            'denda' => $total,
            'formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }
}
