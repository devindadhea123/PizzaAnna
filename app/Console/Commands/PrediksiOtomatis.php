<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\RiwayatPrediksiController;

class PrediksiOtomatis extends Command
{
    protected $signature = 'prediksi:otomatis';
    protected $description = 'Menjalankan prediksi menu terlaris secara otomatis';

    public function handle()
    {
        $now = now();

        if ($now->day != 3){
            $this->info('Bukan tanggal prediksi.');
            return;
        }

        $bulanTarget = now()->addMonth()->format('Y-m');

        $existing = \App\Models\RiwayatPrediksi::where('bulan_target', $bulanTarget)->first();

        if ($existing) {
            $this->info('Prediksi sudah ada, skip.');
            return;
        }

        $controller = new \App\Http\Controllers\Admin\RiwayatPrediksiController();

        $response = $controller->lakukanPrediksi(request());

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->info(' Prediksi otomatis BERHASIL!');
        } else {
            $this->error(' Gagal: ' . $result['message']);
        }
    }
}