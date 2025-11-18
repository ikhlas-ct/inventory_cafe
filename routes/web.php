<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;

Route::get('/', function () {
    return view('layouts.app');
});
Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::resource('karyawans', KaryawanController::class);
Route::resource('satuans', SatuanController::class);
Route::resource('kategoris', KategoriController::class);
Route::resource('karyawans', KaryawanController::class);
Route::resource('barangs', BarangController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('manajers', ManajerController::class);
Route::resource('barangmasuks', BarangMasukController::class);
