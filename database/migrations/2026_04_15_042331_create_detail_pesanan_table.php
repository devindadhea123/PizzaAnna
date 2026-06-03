<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('detail_pesanan', function (Blueprint $table) {
        $table->id('id_detail');
        $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
        $table->foreignId('id_menu')->constrained('menu', 'id_menu');

        $table->string('ukuran')->nullable();

        $table->integer('jumlah');
        $table->decimal('harga_satuan', 10, 2);
        $table->decimal('subtotal', 10, 2);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('detail_pesanan');
}
};