<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HalamanDurianController;

/*
|--------------------------------------------------------------------------
| Web Routes — DurianFy
|--------------------------------------------------------------------------
*/

// Halaman Beranda
Route::get('/', fn () => view('beranda'))->name('beranda');

// Halaman Informasi Varietas
Route::get('/varietas', fn () => view('informasi-varietas'))->name('varietas');

// Grup route Klasifikasi — ditangani HalamanDurianController
Route::controller(HalamanDurianController::class)->group(function () {

    // GET  /klasifikasi  → tampilkan form upload
    Route::get('/klasifikasi', 'tampilkanForm')->name('klasifikasi');

    // POST /klasifikasi  → proses upload & kirim ke AI
    Route::post('/klasifikasi', 'proses')->name('klasifikasi.proses');

    // GET  /hasil        → tampilkan hasil klasifikasi dari session
    Route::get('/hasil', 'tampilkanHasil')->name('hasil');
});