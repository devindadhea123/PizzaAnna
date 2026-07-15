<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id('id_bahan');
            $table->string('nama_bahan', 100);
            $table->string('satuan', 20)->default('kg');
            $table->decimal('stok', 15, 2)->default(0);
            $table->decimal('stok_minimal', 15, 2)->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_baku');
    }
};