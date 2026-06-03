<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pizza_ukuran', function (Blueprint $table) {

            $table->id('id_ukuran');

            $table->unsignedBigInteger('id_menu');

            $table->enum('ukuran', ['S', 'M', 'L']);

            $table->decimal('harga', 10, 2);

            $table->timestamps();

            // Relasi ke tabel menu
            $table->foreign('id_menu')
                  ->references('id_menu')
                  ->on('menu')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pizza_ukuran');
    }
};