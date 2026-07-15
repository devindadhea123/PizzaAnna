<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RiwayatPrediksi;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateAktualPrediksi extends Command
{
    protected $signature = 'prediksi:update-aktual';
    protected $description = 'Update data aktual untuk semua prediksi';

    public function handle()
    {
        $this->info('🔄 Update data aktual...');
        
        $predictions = RiwayatPrediksi::whereNull('detail_akurasi')
            ->orWhere('detail_akurasi', '[]')
            ->get();
        
        $updated = 0;
        
        foreach ($predictions as $prediction) {
            $bulanTarget = $prediction->bulan_target;
            $tahun = substr($bulanTarget, 0, 4);
            $bulan = substr($bulanTarget, 5, 2);
            
            $tanggalTarget = Carbon::create($tahun, $bulan, 27);
            
            if (now()->greaterThan($tanggalTarget)) {
                $this->updateAktual($prediction);
                $updated++;
                $this->info("✅ Updated: {$bulanTarget}");
            }
        }
        
        $this->info("✅ Selesai! Updated {$updated} predictions");
    }
    
    private function updateAktual($prediksi)
    {
        $hasilPrediksi = json_decode($prediksi->hasil_prediksi, true);
        $tahun = substr($prediksi->bulan_target, 0, 4);
        $bulan = substr($prediksi->bulan_target, 5, 2);
        
        $aktualPenjualan = DetailPesanan::select(
            'menu.nama_menu',
            DB::raw('SUM(detail_pesanan.jumlah) as total_terjual')
        )
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
        ->whereYear('pesanan.tanggal', $tahun)
        ->whereMonth('pesanan.tanggal', $bulan)
        ->whereDay('pesanan.tanggal', '<=', 27)
        ->groupBy('menu.nama_menu')
        ->get()
        ->keyBy('nama_menu');
        
        $detailAktual = [];
        foreach ($hasilPrediksi as $item) {
            $namaMenu = $item['nama_menu'];
            $aktualJumlah = isset($aktualPenjualan[$namaMenu]) 
                ? (int) $aktualPenjualan[$namaMenu]->total_terjual 
                : 0;
            
            $detailAktual[] = [
                'nama_menu' => $namaMenu,
                'prediksi' => $item['prediksi'],
                'aktual' => $aktualJumlah,
                'selisih' => $aktualJumlah - $item['prediksi'],
            ];
        }
        
        $prediksi->update(['detail_akurasi' => $detailAktual]);
    }
}