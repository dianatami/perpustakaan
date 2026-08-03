<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\LeaderboardController;
use App\Models\User;
use App\Http\Controllers\ProfileAnggotaController;
use App\Http\Controllers\Anggota\AnggotaKategoriController;
use App\Http\Controllers\Anggota\BerandaAnggotaController;
use App\Http\Controllers\Anggota\BukuController;
use App\Http\Controllers\Anggota\PilihKelasController;
use App\Http\Controllers\Guru\BerandaGuruController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\RackController;
use App\Http\Controllers\Admin\PeminjamanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('tampilan.login');
    }

    $user = Auth::user();

    if ($user instanceof User) {
        return redirect()->route($user->dashboardRouteName());
    }

    return redirect()->route('tampilan.login');
})->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('tampilan/login', [LoginController::class, 'login'])->name('tampilan.login');
    Route::post('tampilan/login', [LoginController::class, 'authenticate'])->name('tampilan.login.process');

    Route::get('tampilan/register', [RegisterController::class, 'register'])->name('tampilan.register');
    Route::post('tampilan/register', [RegisterController::class, 'registerProcess'])->name('tampilan.register.process');
});

Route::post('tampilan/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('tampilan.logout');

// Leaderboard Routes

Route::middleware('auth')->get('leaderboard/live', [LeaderboardController::class, 'live'])
    ->name('leaderboard.live');
Route::middleware('auth')->get('leaderboard/top3', [LeaderboardController::class, 'top3'])
    ->name('leaderboard.top3');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:1'])->group(function () {
    Route::get('beranda', [BerandaController::class, 'berandaAdmin'])->name('beranda');

    Route::resource('books', BookController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('racks', RackController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::post('kategori/{id}/book', [KategoriController::class, 'storeBook'])
        ->name('kategori.book.store');

    Route::resource('peminjaman', PeminjamanController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    
    // Transaksi Menu Routes
    Route::get('transaksi/pengajuan', [PeminjamanController::class, 'pengajuan'])->name('transaksi.pengajuan');
    Route::get('transaksi/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('transaksi.pengembalian');
    Route::get('transaksi/riwayat', [PeminjamanController::class, 'riwayat'])->name('transaksi.riwayat');

    // Laporan Menu Routes
    Route::get('laporan/utama', [PeminjamanController::class, 'laporanUtama'])->name('laporan.utama');
    Route::get('laporan/peminjaman', [PeminjamanController::class, 'laporanPeminjaman'])->name('laporan.peminjaman');
    Route::get('laporan/pengembalian', [PeminjamanController::class, 'laporanPengembalian'])->name('laporan.pengembalian');
    Route::get('laporan/denda', [PeminjamanController::class, 'laporanDenda'])->name('laporan.denda');
    Route::get('laporan/statistik', [PeminjamanController::class, 'laporanStatistik'])->name('laporan.statistik');

    Route::post('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])
        ->name('peminjaman.approve');
    Route::post('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])
        ->name('peminjaman.reject');
    Route::put('peminjaman/{peminjaman}/confirm-return', [PeminjamanController::class, 'confirmReturn'])
        ->name('peminjaman.confirm-return');
    Route::post('peminjaman/{peminjaman}/calculate-fine', [PeminjamanController::class, 'calculateFineAjax'])
        ->name('peminjaman.calculate-fine');
    Route::get('peminjaman/{peminjaman}/process-return', [PeminjamanController::class, 'processReturn'])
        ->name('peminjaman.process-return');

    Route::get('anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    Route::put('anggota/{id}/status', [AnggotaController::class, 'toggleStatus'])->name('anggota.toggleStatus');

    Route::post('anggota/import-guru', [AnggotaController::class, 'importGuru'])->name('anggota.importGuru');
    Route::post('anggota/import-siswa', [AnggotaController::class, 'importSiswa'])->name('anggota.importSiswa');
    Route::get('anggota/template-guru', [AnggotaController::class, 'downloadTemplateGuru'])->name('anggota.templateGuru');
    Route::get('anggota/template-siswa', [AnggotaController::class, 'downloadTemplateSiswa'])->name('anggota.templateSiswa');
});

$portalSharedRoutes = function () {
    Route::get('kategori', [AnggotaKategoriController::class, 'index'])->name('kategori.index');
    Route::get('buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    Route::get('profil', [ProfileAnggotaController::class, 'profil'])->name('profil');
    Route::get('profil-detail', [ProfileAnggotaController::class, 'profilDetail'])->name('profil.detail');
    Route::get('edit-profil', [ProfileAnggotaController::class, 'editProfil'])->name('edit.profil');
    Route::put('update-profil', [ProfileAnggotaController::class, 'updateProfil'])->name('update.profil');
    Route::delete('hapus-foto-profil', [ProfileAnggotaController::class, 'deleteFoto'])->name('delete.foto');
    Route::get('edit-infopribadi', [ProfileAnggotaController::class, 'editInfoPribadi'])->name('edit.infopribadi');
    Route::put('update-infopribadi', [ProfileAnggotaController::class, 'updateInfoPribadi'])->name('update.infopribadi');
    Route::get('ubah-password', [ProfileAnggotaController::class, 'ubahPassword'])->name('ubah.password');
    Route::post('store-password', [ProfileAnggotaController::class, 'storePassword'])->name('store.password');
    Route::get('riwayat-peminjaman', [ProfileAnggotaController::class, 'riwayatPeminjaman'])->name('riwayat.peminjaman');
    Route::get('peminjaman', [ProfileAnggotaController::class, 'peminjaman'])->name('peminjaman');
    Route::post('pinjam', [ProfileAnggotaController::class, 'borrow'])->name('pinjam.store');
    Route::post('pengembalian/{bookrent}', [ProfileAnggotaController::class, 'returnBook'])->name('pengembalian.store');
};

Route::prefix('anggota')->name('anggota.')->middleware(['auth', 'role:0'])->group(function () use ($portalSharedRoutes) {
    Route::get('pilih-kelas', [PilihKelasController::class, 'index'])->name('pilih-kelas');
    Route::post('pilih-kelas', [PilihKelasController::class, 'store'])->name('pilih-kelas.store');

    Route::middleware('ensure.kelas')->group(function () use ($portalSharedRoutes) {
        Route::get('beranda', [BerandaAnggotaController::class, 'berandaAnggota'])->name('beranda');
        $portalSharedRoutes();
    });
});

Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:2'])->group(function () use ($portalSharedRoutes) {
    Route::get('beranda', [BerandaGuruController::class, 'berandaGuru'])->name('beranda');
    $portalSharedRoutes();
});