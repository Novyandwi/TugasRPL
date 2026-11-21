<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KitchenController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/',       [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/',          [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Menu & Stok
    Route::get('/menu',                     [AdminController::class, 'menu'])->name('admin.menu');
    Route::post('/menu',                    [AdminController::class, 'menuStore'])->name('admin.menu.store');
    Route::put('/menu/{menu}',              [AdminController::class, 'menuUpdate'])->name('admin.menu.update');
    Route::delete('/menu/{menu}',           [AdminController::class, 'menuDestroy'])->name('admin.menu.destroy');

    // Users
    Route::get('/users',                    [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users',                   [AdminController::class, 'userStore'])->name('admin.users.store');
    Route::put('/users/{user}',             [AdminController::class, 'userUpdate'])->name('admin.users.update');
    Route::delete('/users/{user}',          [AdminController::class, 'userDestroy'])->name('admin.users.destroy');

    // Laporan
    Route::get('/laporan',                  [AdminController::class, 'laporan'])->name('admin.laporan');
});

// Kasir
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/',                         [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/transaksi',               [KasirController::class, 'transaksi'])->name('kasir.transaksi');
    Route::get('/antrean',                  [KasirController::class, 'getAntrean'])->name('kasir.antrean');
});

// Kitchen
Route::prefix('kitchen')->middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/',                         [KitchenController::class, 'index'])->name('kitchen.index');
    Route::post('/selesai/{transaksi}',     [KitchenController::class, 'selesai'])->name('kitchen.selesai');
    Route::get('/antrean',                  [KitchenController::class, 'getAntrean'])->name('kitchen.antrean');
});
