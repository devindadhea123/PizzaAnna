<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');

            $table->string('nama_menu');
            $table->text('deskripsi')->nullable();

            $table->unsignedBigInteger('id_kategori');
            $table->decimal('harga', 10, 2);
            $table->string('gambar')->nullable();
            $table->enum('diskon_jenis', ['none', 'persen'])->default('none');
            $table->decimal('diskon_nilai', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};