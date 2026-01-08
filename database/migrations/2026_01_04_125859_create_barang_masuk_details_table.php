<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_masuk_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang_masuk');
            $table->foreign('id_barang_masuk')->references('id')->on('barang_masuks');
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id')->on('barangs');
            $table->integer('jumlah')->default(0);
            $table->integer('jumlah_tersisa')->default(0);
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_details');
    }
};
