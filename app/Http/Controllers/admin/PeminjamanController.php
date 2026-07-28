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
use Illuminate\Support\Facades\Log;


class PeminjamanController extends Controller
{
     public function setujui(Request $request, $id)
    {
        try {
            // Log untuk debugging
            Log::info('Proses persetujuan peminjaman ID: ' . $id);
            Log::info('Data request: ', $request->all());
            
            $result = DB::transaction(function () use ($request, $id) {
                // Cari data peminjaman
                $peminjaman = Bookrent::with(['details.book', 'user'])->lockForUpdate()->findOrFail($id);
                
                if ($peminjaman->status !== 'menunggu_acc' && $peminjaman->status !== 'disetujui') {
                    if (in_array($peminjaman->status, ['dipinjam', 'kembali', 'proses_kembali'], true)) {
                        throw new \Exception('Peminjaman tidak dalam status yang dapat disetujui.');
                    }
                }

                // Validate stock for all books
                foreach ($peminjaman->details as $detail) {
                    if ($detail->book->stock < $detail->qty) {
                        throw new \Exception('Stok buku ' . $detail->book->title . ' tidak cukup.');
                    }
                }

                // Decrease stock for each book
                foreach ($peminjaman->details as $detail) {
                    $detail->book->decrement('stock', $detail->qty);
                }

                $borrowDate = Carbon::now()->toDateString();
                $duration = $request->input('borrow_duration', 7);
                $returnDate = Carbon::now()->addDays($duration)->toDateString();

                $peminjaman->status = 'dipinjam';
                $peminjaman->borrow_date = $borrowDate;
                $peminjaman->return_date = $returnDate;

                // Detect jenis_peminjam and set di_acc_at and tgl_kembali_maksimal
                $jenisPeminjam = $peminjaman->jenis_peminjam ?? ($peminjaman->user->role == User::ROLE_GURU ? 'guru' : 'murid');
                $peminjaman->jenis_peminjam = $jenisPeminjam;

                $peminjaman->di_acc_at = Carbon::now();
                if ($jenisPeminjam === 'murid') {
                    $peminjaman->tgl_kembali_maksimal = Carbon::now()->addDays(3);
                } else {
                    $peminjaman->tgl_kembali_maksimal = null;
                }

                $peminjaman->save();

                return $peminjaman;
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil disetujui',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error dalam persetujuan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function saveData()
    {
    // Proses simpan data...

    // Kirim sinyal ke browser untuk tutup modal
    $this->dispatch('close-modal'); 
    }
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

    if ($peminjaman->status === 'kembali') {
        return redirect()
            ->route('admin.peminjaman.index')
            ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
    }

    $users = User::whereIn('role', [
                (string) User::ROLE_ANGGOTA,
                (string) User::ROLE_GURU
            ])
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
            'conditions' => 'sometimes|array',
            'conditions.*' => 'in:baik,rusak,hilang',
        ]);

        $peminjaman = Bookrent::with('details.book')
            ->findOrFail($id);

        // simpan status lama
        $statusLama = $peminjaman->status;

        // update data utama
        $peminjaman->update([
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'status' => $request->status,
            'denda' => $request->denda ?? 0,
        ]);

        // simpan kondisi buku per detail
        foreach ($peminjaman->details as $detail) {
            $condition = $request->input('conditions.' . $detail->id)
                ?? ($detail->condition ?? 'baik');

            if ($detail->condition !== $condition) {
                $detail->condition = $condition;
                $detail->save();
            }
        }

        // kembalikan stok kalau status berubah ke kembali
        if ($request->status === 'kembali' && $statusLama !== 'kembali') {
            foreach ($peminjaman->details as $detail) {
                $condition = $detail->condition ?? 'baik';
                if ($condition === 'rusak') {
                 Book::where('id', $detail->book_id)
                    ->increment('damaged', $detail->qty);
                continue;
                }

                if ($condition === 'hilang') {
                    Book::where('id', $detail->book_id)
                        ->increment('lost', $detail->qty);
                    continue;
                }

                $detail->book->increment('stock', $detail->qty);
                
            }
        }

        return redirect()
            ->route('admin.peminjaman.index')
            ->with(
                'success',
                'Data peminjaman berhasil diperbarui'
            );
    }

    public function approve(Request $request, Bookrent $peminjaman)
    {
        $request->validate([
            'borrow_duration' => 'required|integer|min:1|max:30',
        ]);

        $result = DB::transaction(function () use ($request, $peminjaman): array {
            // Re-fetch with lock for update
            $peminjaman = Bookrent::with(['details.book', 'user'])->lockForUpdate()->findOrFail($peminjaman->id);
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

            // Detect jenis_peminjam and set di_acc_at and tgl_kembali_maksimal
            $jenisPeminjam = $peminjaman->jenis_peminjam ?? ($peminjaman->user->role == User::ROLE_GURU ? 'guru' : 'murid');
            $peminjaman->jenis_peminjam = $jenisPeminjam;

            $peminjaman->di_acc_at = Carbon::now();
            if ($jenisPeminjam === 'murid') {
                $peminjaman->tgl_kembali_maksimal = Carbon::now()->addDays(3);
            } else {
                $peminjaman->tgl_kembali_maksimal = null;
            }

            $peminjaman->save();

            return ['ok' => true, 'message' => 'Pengajuan peminjaman berhasil disetujui. Murid diminta datang ke perpustakaan untuk mengambil buku.'];
        });

        $flashType = $result['ok'] ? 'success' : 'error';
        
        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json($result, $result['ok'] ? 200 : 422);
        }
        
        return redirect()->route('admin.peminjaman.index')->with($flashType, $result['message']);
    }

    public function reject(Request $request, Bookrent $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman): array {
            if ($peminjaman->status !== 'menunggu_acc') {
                return ['ok' => false, 'message' => 'Peminjaman tidak dalam status menunggu ACC.'];
            }
            $peminjaman->status = 'ditolak';
            $peminjaman->save();
            return ['ok' => true, 'message' => 'Peminjaman berhasil ditolak.'];
        });
        $flashType = $result['ok'] ? 'success' : 'error';
        
        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json($result, $result['ok'] ? 200 : 422);
        }
        
        return redirect()->route('admin.peminjaman.index')->with($flashType, $result['message']);
    }

    public function confirmReturn(Request $request, Bookrent $peminjaman)
    {
        $request->validate([
            'return_date' => 'required|date',
            'denda' => 'nullable|integer|min:0',
            'conditions' => 'sometimes|array',
            'conditions.*' => 'in:baik,rusak,hilang',
        ]);

        $result = DB::transaction(function () use ($request, $peminjaman): array {
            if ($peminjaman->status !== 'proses_kembali') {
                return [
                    'ok' => false,
                    'message' => 'Pengembalian belum diajukan oleh user.'
                ];
            }

            $borrowDate = Carbon::parse($peminjaman->borrow_date);
            $returnDate = Carbon::parse($request->input('return_date'));

            // simpan kondisi buku per detail
            $conditions = $request->input('conditions', []);
            $damageLoss = 0;
            foreach ($peminjaman->details as $detail) {
                $cond = $conditions[$detail->id] ?? ($detail->condition ?? 'baik');
                $detail->condition = $cond;
                $detail->save();

                if (in_array($cond, ['rusak', 'hilang'], true)) {
                    $damageLoss += 50000 * ($detail->qty ?? 1);
                }
            }

            // If admin supplied a denda explicitly, use it
            if ($request->filled('denda')) {
                $denda = (int) $request->input('denda');
            } else {
                // compute late fee
                $days = $borrowDate->diffInDays($returnDate);
                $late = $days > 7 ? ($days - 7) * 5000 : 0;
                $denda = $late + $damageLoss;
            }

            // =========================================
            // KEMBALIKAN STOK SEMUA BUKU
            // =========================================

            foreach ($peminjaman->details as $detail) {
                $condition = $detail->condition ?? 'baik';

                if ($condition === 'rusak') {
                    Book::where('id', $detail->book_id)
                        ->increment('damaged', $detail->qty);
                    continue;
                }

                if ($condition === 'hilang') {
                    Book::where('id', $detail->book_id)
                        ->increment('lost', $detail->qty);
                    continue;
                }

                Book::where('id', $detail->book_id)
                        ->increment('stock', $detail->qty);
            }

            // =========================================
            // UPDATE STATUS
            // =========================================

            $peminjaman->status = 'kembali';
            $peminjaman->return_date = $returnDate->toDateString();
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

    public function processReturn(Bookrent $peminjaman)
    {
        $peminjaman->load('details.book','user');
        return view('admin.peminjaman.return', compact('peminjaman'));
    }

    /**
     * AJAX: calculate fine for given return date and conditions
     */
    public function calculateFineAjax(Request $request, Bookrent $peminjaman)
    {
        $request->validate([
            'return_date' => 'required|date',
            'conditions' => 'sometimes|array',
        ]);

        $peminjaman->load('details');

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

    public function pengajuan()
    {
        $peminjaman = Bookrent::with(['user', 'details.book'])
            ->whereIn('status', ['menunggu_acc', 'dipinjam', 'ditolak'])
            ->latest('created_at')
            ->paginate(10);
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])->get();
        $books = Book::all();

        return view('admin.transaksi.pengajuan', compact('peminjaman', 'users', 'books'));
    }

    public function pengembalian()
    {
        $peminjaman = Bookrent::with(['user', 'details.book'])
            ->where('status', 'proses_kembali')
            ->latest('created_at')
            ->paginate(10);
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])->get();
        $books = Book::all();

        return view('admin.transaksi.pengembalian', compact('peminjaman', 'users', 'books'));
    }

    public function riwayat()
    {
        $peminjaman = Bookrent::with(['user', 'details.book'])
            ->latest('created_at')
            ->paginate(10);
        $users = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])->get();
        $books = Book::all();

        return view('admin.transaksi.riwayat', compact('peminjaman', 'users', 'books'));
    }

    public function laporanUtama(Request $request)
    {
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput === 'all') ? 'all' : ($bulanInput ? (int) $bulanInput : (int) date('n'));
        $tahun = (int) $request->input('tahun', date('Y'));

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $namaBulan = ($bulan === 'all') ? 'Semua Periode' : ($namaBulanList[$bulan] ?? $namaBulanList[date('n')]);

        $query = Bookrent::with(['user', 'details.book.category']);

        if ($bulan !== 'all') {
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $startDate = Carbon::createFromDate($tahun, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($tahun, 12, 31)->endOfDay();
            $query->whereYear('created_at', $tahun);
        }

        $peminjaman = $query->latest('created_at')->get();

        $totalBuku = Book::count();
        $totalAnggota = User::whereIn('role', [(string) User::ROLE_ANGGOTA, (string) User::ROLE_GURU])->count();
        $totalPinjam = $peminjaman->count();
        $sedangDipinjam = $peminjaman->where('status', 'dipinjam')->count();
        $sudahDikembalikan = $peminjaman->where('status', 'kembali')->count();
        $terlambat = $peminjaman->where('status', 'terlambat')->count();
        
        $totalDenda = 0;
        foreach ($peminjaman as $p) {
            if (method_exists($p, 'calculateFine')) {
                $totalDenda += $p->calculateFine();
            } else {
                $totalDenda += (int) ($p->denda ?? 0);
            }
        }

        return view('admin.laporan.utama', compact(
            'peminjaman', 'totalBuku', 'totalAnggota', 'totalPinjam', 
            'sedangDipinjam', 'sudahDikembalikan', 'terlambat', 'totalDenda',
            'bulan', 'tahun', 'namaBulan', 'startDate', 'endDate', 'namaBulanList'
        ));
    }

    public function laporanPeminjaman(Request $request)
    {
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput === 'all') ? 'all' : ($bulanInput ? (int) $bulanInput : (int) date('n'));
        $tahun = (int) $request->input('tahun', date('Y'));
        $status = $request->input('status');
        $role = $request->input('role');
        $search = $request->input('search');

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $namaBulan = ($bulan === 'all') ? 'Semua Periode' : ($namaBulanList[$bulan] ?? $namaBulanList[date('n')]);

        $query = Bookrent::with(['user', 'details.book.category']);

        // Filter periode rentang waktu 1 bulan terpilih
        if ($bulan !== 'all') {
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('created_at', $tahun);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        // Filter role (Siswa = 0, Guru = 2)
        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        // Filter pencarian (Nama, Email, Judul Buku)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($qu) use ($search) {
                    $qu->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('details.book', function ($qb) use ($search) {
                    $qb->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Ambil seluruh data hasil filter rentang 1 bulan terpilih
        $allPeminjaman = (clone $query)->latest('created_at')->get();

        // Hitung statistik tersinkronisasi
        $totalPinjam = $allPeminjaman->count();
        $sedangDipinjam = $allPeminjaman->where('status', 'dipinjam')->count();
        $sudahDikembalikan = $allPeminjaman->where('status', 'kembali')->count();
        $menungguAcc = $allPeminjaman->where('status', 'menunggu_acc')->count();
        $prosesKembali = $allPeminjaman->where('status', 'proses_kembali')->count();
        $ditolak = $allPeminjaman->where('status', 'ditolak')->count();
        
        $totalDenda = 0;
        foreach ($allPeminjaman as $p) {
            if (method_exists($p, 'calculateFine')) {
                $totalDenda += $p->calculateFine();
            } else {
                $totalDenda += (int) ($p->denda ?? 0);
            }
        }

        // Data untuk tampilan tabel terpaginasi
        $peminjaman = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.laporan.peminjaman', compact(
            'peminjaman', 'allPeminjaman', 'totalPinjam', 'sedangDipinjam',
            'sudahDikembalikan', 'menungguAcc', 'prosesKembali', 'ditolak', 'totalDenda',
            'bulan', 'tahun', 'namaBulan', 'namaBulanList', 'status', 'role', 'search'
        ));
    }

    public function laporanPengembalian()
    {
        $peminjaman = Bookrent::with(['user', 'details.book'])
            ->where('status', 'kembali')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.laporan.pengembalian', compact('peminjaman'));
    }

    public function laporanDenda()
    {
        $peminjaman = Bookrent::with(['user', 'details.book'])
            ->where('denda', '>', 0)
            ->latest('created_at')
            ->paginate(10);

        return view('admin.laporan.denda', compact('peminjaman'));
    }

    public function laporanStatistik()
    {
        $totalBuku = Book::count();
        $totalKategori = \App\Models\Kategori::count();
        $totalAnggota = User::where('role', '0')->count();
        $totalGuru = User::where('role', User::ROLE_GURU)->count();
        $totalPinjam = Bookrent::count();
        $stokTersedia = Book::where('stock', '>', 0)->count();
        $dipinjam = Bookrent::where('status', 'dipinjam')->count();
        $totalRusak = Book::sum('damaged');
        $totalHilang = Book::sum('lost');
        $leaderboardGuruFull = User::leaderboardByRole(User::ROLE_GURU, 0);
        $leaderboardSiswaFull = User::leaderboardByRole(User::ROLE_ANGGOTA, 0);

        $leaderboardGuru = $leaderboardGuruFull->take(10);
        $leaderboardSiswa = $leaderboardSiswaFull->take(10);

        $totalGuruAktif = $leaderboardGuruFull->count();
        $totalSiswaAktif = $leaderboardSiswaFull->count();

        $totalPeminjamanGuruLb = $leaderboardGuruFull->sum('total_peminjaman');
        $totalPeminjamanSiswaLb = $leaderboardSiswaFull->sum('total_peminjaman');

        return view('admin.laporan.statistik', compact(
            'totalBuku',
            'totalKategori',
            'totalAnggota',
            'totalGuru',
            'totalPinjam',
            'stokTersedia',
            'dipinjam',
            'totalRusak',
            'totalHilang',
            'leaderboardGuru',
            'leaderboardSiswa',
            'totalGuruAktif',
            'totalSiswaAktif',
            'totalPeminjamanGuruLb',
            'totalPeminjamanSiswaLb'
        ));
    }
}
