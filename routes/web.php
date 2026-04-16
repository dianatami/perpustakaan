<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ProfileAnggotaController;
use App\Http\Controllers\Anggota\AnggotaKategoriController;
use App\Http\Controllers\Anggota\BerandaAnggotaController;
use App\Http\Controllers\Anggota\BukuController;
use App\Http\Controllers\Guru\BerandaGuruController;
use App\Http\Controllers\KepalaSekolah\BerandaKepalaSekolahController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BookController;
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
    return redirect()->route('tampilan.login');
});

Route::middleware('guest')->group(function () {
    Route::get('tampilan/login', [LoginController::class, 'login'])->name('tampilan.login');
    Route::post('tampilan/login', [LoginController::class, 'authenticate'])->name('tampilan.login.process');

    Route::get('tampilan/register', [RegisterController::class, 'register'])->name('tampilan.register');
    Route::post('tampilan/register', [RegisterController::class, 'registerProcess'])->name('tampilan.register.process');
});

Route::post('tampilan/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('tampilan.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:1'])->group(function () {
    Route::get('beranda', [BerandaController::class, 'berandaAdmin'])->name('beranda');

    Route::resource('books', BookController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::post('kategori/{id}/book', [KategoriController::class, 'storeBook'])
        ->name('kategori.book.store');

    Route::resource('peminjaman', PeminjamanController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::get('anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    Route::put('anggota/{id}/status', [AnggotaController::class, 'toggleStatus'])->name('anggota.toggleStatus');
});

$portalSharedRoutes = function () {
    Route::get('kategori', [AnggotaKategoriController::class, 'index'])->name('kategori.index');
    Route::get('buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    Route::get('profil', [ProfileAnggotaController::class, 'profil'])->name('profil');
    Route::get('profil-detail', [ProfileAnggotaController::class, 'profilDetail'])->name('profil.detail');
    Route::get('edit-profil', [ProfileAnggotaController::class, 'editProfil'])->name('edit.profil');
    Route::put('update-profil', [ProfileAnggotaController::class, 'updateProfil'])->name('update.profil');
    Route::get('edit-infopribadi', [ProfileAnggotaController::class, 'editInfoPribadi'])->name('edit.infopribadi');
    Route::put('update-infopribadi', [ProfileAnggotaController::class, 'updateInfoPribadi'])->name('update.infopribadi');
    Route::get('ubah-password', [ProfileAnggotaController::class, 'ubahPassword'])->name('ubah.password');
    Route::post('store-password', [ProfileAnggotaController::class, 'storePassword'])->name('store.password');
    Route::get('riwayat-peminjaman', [ProfileAnggotaController::class, 'riwayatPeminjaman'])->name('riwayat.peminjaman');
};

Route::prefix('anggota')->name('anggota.')->middleware(['auth', 'role:0'])->group(function () use ($portalSharedRoutes) {
    Route::get('beranda', [BerandaAnggotaController::class, 'berandaAnggota'])->name('beranda');
    $portalSharedRoutes();
});

Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:2'])->group(function () use ($portalSharedRoutes) {
    Route::get('beranda', [BerandaGuruController::class, 'berandaGuru'])->name('beranda');
    $portalSharedRoutes();
});

Route::prefix('kepala')->name('kepala.')->middleware(['auth', 'role:3'])->group(function () {
    Route::get('beranda', [BerandaKepalaSekolahController::class, 'berandaKepala'])->name('beranda');
});