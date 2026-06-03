<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  public function up(): void
    {
        Schema::create('riwayat_prediksi', function (Blueprint $table) {
            $table->id('id_prediksi');
            $table->dateTime('tanggal_prediksi');
            $table->string('bulan_target', 7);
            $table->string('data_yang_dipakai', 100);
            $table->text('hasil_prediksi');
            $table->decimal('rata_rata_akurasi', 5, 2)->nullable();
            $table->json('detail_akurasi')->nullable();
            $table->json('rekomendasi_promosi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_prediksi');
    }
};