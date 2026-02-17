<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')
                  ->references('id')->on('suppliers')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_kategori');
            $table->foreign('id_kategori')
                  ->references('id')->on('kategoris')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_satuan');
            $table->foreign('id_satuan')
                  ->references('id')->on('satuans')
                  ->onDelete('cascade');

            $table->string('kode_barang', 20)->unique();
            $table->string('nama', 35);
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
