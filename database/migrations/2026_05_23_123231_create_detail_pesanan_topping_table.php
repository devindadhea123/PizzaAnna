<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('detail_pesanan_topping', function (Blueprint $table) {
    $table->bigIncrements('id');

    $table->unsignedBigInteger('detail_pesanan_id');
    $table->unsignedBigInteger('topping_id');

    // FK ke detail_pesanan
    $table->foreign('detail_pesanan_id')
        ->references('id_detail')
        ->on('detail_pesanan')
        ->onDelete('cascade');

    // FK ke toppings (INI FIX UTAMA)
    $table->foreign('topping_id')
        ->references('id_topping')
        ->on('toppings')
        ->onDelete('cascade');

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan_topping');
    }
};