<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\RiwayatPrediksi;
use App\Http\Controllers\Admin\RiwayatPrediksiController;

class PrediksiOtomatis extends Command
{
    protected $signature = 'prediksi:otomatis';
    protected $description = 'Menjalankan prediksi menu terlaris secara otomatis dengan metode WMA';

    public function handle()
    {
        $now = now();
        
        // ✅ SETTING JADWAL PREDIKSI
        $HARI_PREDIKSI = 5;      // Tanggal prediksi
        $JAM_DEADLINE = 22;      // Jam deadline (setelah jam ini auto jalan)
        $MENIT_DEADLINE = 05;    // Menit deadline
        
        // ✅ CEK APAKAH HARI INI TANGGAL PREDIKSI
        if ($now->day != $HARI_PREDIKSI) {
            $this->info('📅 Bukan tanggal prediksi. Hari ini tanggal ' . $now->day . ', prediksi hanya tanggal ' . $HARI_PREDIKSI);
            return Command::SUCCESS;
        }
        
        $currentHour = (int)$now->format('H');
        $currentMinute = (int)$now->format('i');
        
        $isAfterDeadline = (
            $currentHour > $JAM_DEADLINE || 
            ($currentHour == $JAM_DEADLINE && $currentMinute >= $MENIT_DEADLINE)
        );
        
        // ✅ CEK APAKAH SUDAH MELEWATI JAM DEADLINE
        if (!$isAfterDeadline) {
            $this->info(' Belum waktunya prediksi otomatis. Sekarang jam ' . $now->format('H:i') . ', prediksi otomatis jam ' . sprintf('%02d:%02d', $JAM_DEADLINE, $MENIT_DEADLINE));
            return Command::SUCCESS;
        }
        
        $bulanTarget = now()->addMonth()->format('Y-m');
        
        // ✅ CEK APAKAH PREDIKSI SUDAH ADA
        $existing = RiwayatPrediksi::where('bulan_target', $bulanTarget)->first();
        
        if ($existing) {
            $this->info('✅ Prediksi untuk bulan ' . now()->addMonth()->format('F Y') . ' sudah ada, skip.');
            return Command::SUCCESS;
        }
        
        $this->info('🔮 Memulai prediksi otomatis untuk bulan ' . now()->addMonth()->format('F Y') . '...');
        $this->info('📊 Metode: Weighted Moving Average (WMA) dengan bobot 1,2,3,4,5,6');
        $this->info('📅 Menggunakan data 6 bulan terakhir');
        
        // ✅ MEMBUAT REQUEST DAN MEMANGGIL CONTROLLER
        $request = new Request();
        $controller = new RiwayatPrediksiController();
        
        try {
            $response = $controller->lakukanPrediksi($request);
            $result = json_decode($response->getContent(), true);
            
            if ($result['success']) {
                $this->info('✅ PREDIKSI OTOMATIS BERHASIL!');
                $this->info('📈 Top 3 Menu Terlaris:');
                
                if (isset($result['data'])) {
                    foreach ($result['data'] as $index => $menu) {
                        $ikon = $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉');
                        $this->info("   $ikon " . $menu['nama_menu'] . " → " . $menu['prediksi'] . " porsi");
                    }
                }
                
                if (isset($result['periode'])) {
                    $this->info('📅 Periode data: ' . $result['periode']);
                }
                
                if (isset($result['bulan_target'])) {
                    $this->info('🎯 Bulan target: ' . $result['bulan_target']);
                }
                
                $this->info('💡 Rekomendasi promosi sudah tersedia di dashboard admin.');
                
                return Command::SUCCESS;
            } else {
                $this->error('❌ Gagal: ' . $result['message']);
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}