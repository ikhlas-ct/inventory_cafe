<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->string('nama', 35);
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
