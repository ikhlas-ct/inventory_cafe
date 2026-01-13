<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['role:karyawan,manajer'])->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('barangmasuks', BarangMasukController::class);
    Route::get('/laporan/barang-masuk', [BarangMasukController::class, 'laporanBarangMasuk'])->name('laporan.barang_masuk');
    Route::resource('barangkeluars', BarangKeluarController::class);
    Route::get('/laporan/barang-keluar', [BarangKeluarController::class, 'laporanBarangKeluar'])->name('laporan.barang_keluar');
});

Route::middleware('role:manajer')->group(function () {
Route::resource('karyawans', KaryawanController::class);
Route::resource('satuans', SatuanController::class);
Route::resource('kategoris', KategoriController::class);
Route::resource('karyawans', KaryawanController::class);
Route::resource('barangs', BarangController::class);
    Route::get('/laporan/stok-barang', [BarangController::class, 'laporanStokBarang'])->name('laporan.stok_barang');
Route::resource('suppliers', SupplierController::class);
Route::resource('manajers', ManajerController::class);

});
