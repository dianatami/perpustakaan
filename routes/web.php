<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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

Route::get('admin/beranda', [BerandaController::class, 'berandaAdmin'])->name('admin.beranda');
Route::get('anggota/beranda', [BerandaController::class, 'berandaAnggota'])->name('anggota.beranda');

Route::get('tampilan/login', [LoginController::class, 'login'])->name('tampilan.login');
Route::post('tampilan/login', [LoginController::class, 'authenticate'])->name('tampilan.login');

Route::post('tampilan/logout', [LoginController::class, 'logout'])->name('tampilan.logout');

Route::get('tampilan/register', [RegisterController::class, 'register'])->name('tampilan.register');
Route::post('tampilan/register', [RegisterController::class, 'registerProcess'])->name('tampilan.register');

use App\Http\Controllers\Admin\KategoriController;

Route::get('admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
Route::post('admin/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
Route::delete('admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

Route::get('admin/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
Route::put('admin/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
Route::post('admin/kategori/{id}/book', [KategoriController::class, 'storeBook'])->name('admin.kategori.book.store');