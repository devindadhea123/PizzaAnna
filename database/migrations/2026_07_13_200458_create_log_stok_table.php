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
        Schema::create('log_stok', function (Blueprint $table) {
            $table->id('id_log_stok');
            $table->unsignedBigInteger('id_bahan');
            $table->double('stok_sebelum');
            $table->double('stok_sesudah');
            $table->double('perubahan');
            $table->string('keterangan')->nullable();
            $table->string('tipe'); // 'tambah', 'kurang'
            $table->string('referensi')->nullable();
            $table->timestamps();

            $table->foreign('id_bahan')->references('id_bahan')->on('bahan_baku')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stok');
    }
};
