<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('layouts.app');
});
Route::resource('karyawans', KaryawanController::class);
Route::resource('satuans', SatuanController::class);
Route::resource('kategoris', KategoriController::class);
Route::resource('karyawans', KaryawanController::class);
Route::resource('barangs', BarangController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('manajers', ManajerController::class);
