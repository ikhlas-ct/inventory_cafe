<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;

Route::get('/', function () {
    return view('layouts.app');
});

Route::resource('satuans', SatuanController::class);
Route::resource('kategoris', KategoriController::class);
Route::resource('karyawans', KaryawanController::class);
Route::resource('barangs', BarangController::class);

