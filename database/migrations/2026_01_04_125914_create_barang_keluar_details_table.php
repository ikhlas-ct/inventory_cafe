<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_barang_keluar');
            $table->foreign('id_barang_keluar')
                  ->references('id')->on('barang_keluars')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')
                  ->references('id')->on('barangs')
                  ->onDelete('cascade');

            $table->integer('jumlah');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar_details');
    }
};
