<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('pesanan', function (Blueprint $table) {
    $table->id('id_pesanan');

    $table->string('no_invoice', 50)->nullable();
    $table->dateTime('tanggal')->nullable();

    $table->foreignId('id_kasir')
        ->constrained('users', 'id_user')
        ->nullable();

    $table->string('nama_customer', 100)->nullable();
    $table->integer('no_meja')->nullable();

    $table->decimal('total_bayar', 10, 2)->default(0);

    $table->enum('metode_bayar', ['tunai', 'qris'])
        ->nullable();

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};