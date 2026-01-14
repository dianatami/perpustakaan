<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ProfileAnggotaController;
use App\Http\Controllers\Anggota\AnggotaKategoriController;
use App\Http\Controllers\Anggota\BukuController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\KategoriBookController;
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
    return view('tampilan/login');
});

Route::get('tampilan/login', [LoginController::class, 'login'])->name('tampilan.login');
Route::post('tampilan/login', [LoginController::class, 'authenticate'])->name('tampilan.login');

Route::post('tampilan/logout', [LoginController::class, 'logout'])->name('tampilan.logout');

Route::get('tampilan/register', [RegisterController::class, 'register'])->name('tampilan.register');
Route::post('tampilan/register', [RegisterController::class, 'registerProcess'])->name('tampilan.register');


Route::middleware(['auth'])->group(function () {
Route::get('admin/beranda', [BerandaController::class, 'berandaAdmin'])->name('admin.beranda');
Route::get('anggota/beranda', [BerandaController::class, 'berandaAnggota'])->name('anggota.beranda');

// Routes untuk Admin - Manajemen Anggota
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('peminjaman', PeminjamanController::class);
});

// Routes untuk Admin - Manajemen Anggota
Route::prefix('admin')->group(function () {
    Route::get('anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::get('anggota/create', [AnggotaController::class, 'create'])->name('admin.anggota.create');
    Route::post('anggota', [AnggotaController::class, 'store'])->name('admin.anggota.store');
    Route::get('anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
    Route::put('anggota/{id}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
    Route::delete('anggota/{id}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');
    Route::put('anggota/{id}/status', [AnggotaController::class, 'toggleStatus'])->name('admin.anggota.toggleStatus');
});    

// Routes untuk Admin - Manajemen Kategori
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('kategori', KategoriController::class);
    Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
    Route::get('/admin/kategori/create', [KategoriController::class, 'create'])->name('admin.kategori.create');
    Route::post('/admin/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::get('/admin/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/admin/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
});

// Routes untuk Admin - Manajemen Buku
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('books', BookController::class);
    Route::get('/admin/books', [BookController::class, 'index'])->name('admin.books.index');
    Route::get('/admin/books/create', [BookController::class, 'create'])->name('admin.books.create');
    Route::post('/admin/books', [BookController::class, 'store'])->name('admin.books.store');
    Route::get('/admin/books/{id}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/admin/books/{id}', [BookController::class, 'update'])->name('admin.books.update');
    Route::delete('/admin/books/{id}', [BookController::class, 'destroy'])->name('admin.books.destroy');

});

//Routes untuk Kategori Buku
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post(
        'kategori/{kategori}/book',
        [KategoriBookController::class, 'store']
    )->name('kategori.book.store');
});

//Routes untuk Admin - Kategori Buku
Route::post(
    'admin/kategori/{kategori}/book',
    [KategoriController::class, 'storeBook']
)->name('admin.kategori.book.store');

// Routes untuk Anggota - Profil dan Edit Profil
Route::prefix('anggota')->name('anggota.')->group(function () {
    Route::get('profil', [ProfileAnggotaController::class, 'profil'])->name('profil');
    Route::get('profil-detail', [ProfileAnggotaController::class, 'profilDetail'])->name('profil.detail');
    Route::get('edit-profil', [ProfileAnggotaController::class, 'editProfil'])->name('edit.profil')->middleware('auth');
    Route::put('update-profil', [ProfileAnggotaController::class, 'updateProfil'])->name('update.profil')->middleware('auth');
    Route::get('edit-infopribadi', [ProfileAnggotaController::class, 'editInfoPribadi'])->name('edit.infopribadi')->middleware('auth');
    Route::put('update-infopribadi', [ProfileAnggotaController::class, 'updateInfoPribadi'])->name('update.infopribadi')->middleware('auth');
    Route::get('ubah-password', [ProfileAnggotaController::class, 'ubahPassword'])->name('ubah.password')->middleware('auth');
    Route::post('store-password', [ProfileAnggotaController::class, 'storePassword'])->name('store.password')->middleware('auth');
    Route::get('riwayat-peminjaman', [ProfileAnggotaController::class, 'riwayatPeminjaman'])->name('riwayat.peminjaman')->middleware('auth');
});

    // Routes untuk Anggota - Kategori
    Route::prefix('anggota')->name('anggota.')->middleware('auth')->group(function () {
    Route::get('kategori', [AnggotaKategoriController::class, 'index'])->name('kategori.index');
});

    // Routes untuk Anggota - Buku
     Route::get('/anggota/buku', [BukuController::class, 'index'])
        ->name('anggota.buku.index');

    Route::get('/anggota/buku/{id}', [BukuController::class, 'show'])
        ->name('anggota.buku.show');
});