<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AnggotaController;

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