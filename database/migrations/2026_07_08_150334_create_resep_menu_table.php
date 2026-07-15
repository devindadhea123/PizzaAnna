<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resep_menu', function (Blueprint $table) {
            $table->id('id_resep');
            $table->unsignedBigInteger('id_menu');
            $table->unsignedBigInteger('id_bahan');
             $table->enum('ukuran', ['S', 'M', 'L'])->nullable();
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan', 20);
            $table->timestamps();
            
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade');
            $table->foreign('id_bahan')->references('id_bahan')->on('bahan_baku')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resep_menu');
    }
};