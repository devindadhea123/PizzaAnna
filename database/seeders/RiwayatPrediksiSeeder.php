<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPrediksi;
use Carbon\Carbon;

class RiwayatPrediksiSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================
        // 1. DESEMBER 2025 → TARGET JANUARI 2026
        // ================================================
        $this->createPrediction(
            '2025-12-27',
            '2026-01',
            'Desember 2025 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 75000, 'prediksi' => 52, 'wma_mingguan' => 12.0, 'data_mingguan' => [14, 11, 12, 12], 'kenaikan' => 10.5],
                ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 33, 'wma_mingguan' => 7.7, 'data_mingguan' => [11, 7, 8, 7], 'kenaikan' => 8.2],
                ['ranking' => 3, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 22, 'wma_mingguan' => 5.1, 'data_mingguan' => [4, 5, 7, 4], 'kenaikan' => 5.5],
            ],
            [
                ['menu' => 'Smoked Beef & Corn', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Smoked Beef & Corn dapatkan 1 FREE French Fries! Promo terbatas!', 'icon' => '🎁', 'target' => 'Smoked Beef & Corn'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL
            [
                ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 52, 'aktual' => 49, 'selisih' => 3],
                ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 33, 'aktual' => 33, 'selisih' => 0],
                ['nama_menu' => 'Chicken Burger', 'prediksi' => 22, 'aktual' => 20, 'selisih' => 2],
            ]
        );

        // ================================================
        // 2. JANUARI 2026 → TARGET FEBRUARI 2026
        // ================================================
        $this->createPrediction(
            '2026-01-27',
            '2026-02',
            'Januari 2026 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 24, 'wma_mingguan' => 5.5, 'data_mingguan' => [10, 3, 5, 6], 'kenaikan' => 8.5],
                ['ranking' => 2, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 20, 'wma_mingguan' => 4.6, 'data_mingguan' => [8, 4, 2, 6], 'kenaikan' => 7.2],
                ['ranking' => 3, 'nama_menu' => 'Jeruk / Es Jeruk', 'harga' => 5000, 'prediksi' => 17, 'wma_mingguan' => 4.0, 'data_mingguan' => [6, 2, 2, 6], 'kenaikan' => 6.5],
            ],
            [
                ['menu' => 'Teh / Es Teh', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Teh / Es Teh dapatkan 1 FREE! Promo terbatas!', 'icon' => '🎁', 'target' => 'Teh / Es Teh'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL
            [
                ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 24, 'aktual' => 24, 'selisih' => 0],
                ['nama_menu' => 'Chicken Burger', 'prediksi' => 20, 'aktual' => 20, 'selisih' => 0],
                ['nama_menu' => 'Jeruk / Es Jeruk', 'prediksi' => 17, 'aktual' => 16, 'selisih' => 1],
            ]
        );

        // ================================================
        // 3. FEBRUARI 2026 → TARGET MARET 2026
        // ================================================
        $this->createPrediction(
            '2026-02-27',
            '2026-03',
            'Februari 2026 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 20, 'wma_mingguan' => 4.6, 'data_mingguan' => [6, 2, 4, 6], 'kenaikan' => 8.5],
                ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 19, 'wma_mingguan' => 4.4, 'data_mingguan' => [4, 4, 4, 5], 'kenaikan' => 7.8],
                ['ranking' => 3, 'nama_menu' => 'Donut / Bomboloni', 'harga' => 10000, 'prediksi' => 17, 'wma_mingguan' => 3.8, 'data_mingguan' => [5, 2, 3, 5], 'kenaikan' => 6.5],
            ],
            [
                ['menu' => 'Chicken Burger', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Chicken Burger dapatkan 1 FREE French Fries!', 'icon' => '🎁', 'target' => 'Chicken Burger'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL
            [
                ['nama_menu' => 'Chicken Burger', 'prediksi' => 20, 'aktual' => 18, 'selisih' => 2],
                ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 19, 'aktual' => 15, 'selisih' => 4],
                ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 17, 'aktual' => 17, 'selisih' => 0],
            ]
        );

        // ================================================
        // 4. MARET 2026 → TARGET APRIL 2026
        // ================================================
        $this->createPrediction(
            '2026-03-27',
            '2026-04',
            'Maret 2026 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 23, 'wma_mingguan' => 5.4, 'data_mingguan' => [7, 8, 5, 4], 'kenaikan' => 8.5],
                ['ranking' => 2, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 19, 'wma_mingguan' => 4.4, 'data_mingguan' => [4, 6, 4, 4], 'kenaikan' => 7.2],
                ['ranking' => 3, 'nama_menu' => 'Spicy Chicken Mushroom', 'harga' => 71000, 'prediksi' => 16, 'wma_mingguan' => 3.6, 'data_mingguan' => [5, 1, 3, 5], 'kenaikan' => 6.5],
            ],
            [
                ['menu' => 'Teh / Es Teh', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Teh / Es Teh dapatkan 1 FREE!', 'icon' => '🎁', 'target' => 'Teh / Es Teh'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL
            [
                ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 23, 'aktual' => 25, 'selisih' => 2],
                ['nama_menu' => 'Chicken Burger', 'prediksi' => 19, 'aktual' => 20, 'selisih' => 1],
                ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 16, 'aktual' => 14, 'selisih' => -2],
            ]
        );

        // ================================================
        // 5. APRIL 2026 → TARGET MEI 2026
        // ================================================
        $this->createPrediction(
            '2026-04-27',
            '2026-05',
            'April 2026 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 32, 'wma_mingguan' => 7.4, 'data_mingguan' => [5, 8, 7, 8], 'kenaikan' => 10.5],
                ['ranking' => 2, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 21, 'wma_mingguan' => 4.9, 'data_mingguan' => [2, 5, 3, 7], 'kenaikan' => 8.2],
                ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 22000, 'prediksi' => 14, 'wma_mingguan' => 3.2, 'data_mingguan' => [3, 3, 1, 5], 'kenaikan' => 6.5],
            ],
            [
                ['menu' => 'Teh / Es Teh', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Teh / Es Teh dapatkan 1 FREE!', 'icon' => '🎁', 'target' => 'Teh / Es Teh'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL
            [
                ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 32, 'aktual' => 35, 'selisih' => 3],
                ['nama_menu' => 'Chicken Burger', 'prediksi' => 21, 'aktual' => 18, 'selisih' => -3],
                ['nama_menu' => 'Completo', 'prediksi' => 14, 'aktual' => 12, 'selisih' => -2],
            ]
        );

        // ================================================
        // 6. MEI 2026 → TARGET JUNI 2026
        // ================================================
        $this->createPrediction(
            '2026-05-27',
            '2026-06',
            'Mei 2026 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 75000, 'prediksi' => 40, 'wma_mingguan' => 9.3, 'data_mingguan' => [7, 8, 10, 10], 'kenaikan' => 12.5],
                ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 27, 'wma_mingguan' => 6.2, 'data_mingguan' => [5, 6, 7, 6], 'kenaikan' => 9.2],
                ['ranking' => 3, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 21, 'wma_mingguan' => 4.8, 'data_mingguan' => [2, 4, 2, 8], 'kenaikan' => 7.5],
            ],
            [
                ['menu' => 'Smoked Beef & Corn', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Smoked Beef & Corn dapatkan 1 FREE French Fries!', 'icon' => '🎁', 'target' => 'Smoked Beef & Corn'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL
            [
                ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 40, 'aktual' => 45, 'selisih' => 5],
                ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 27, 'aktual' => 30, 'selisih' => 3],
                ['nama_menu' => 'Chicken Burger', 'prediksi' => 21, 'aktual' => 20, 'selisih' => -1],
            ]
        );

        // ================================================
        // 7. JUNI 2026 → TARGET JULI 2026
        // ================================================
        $this->createPrediction(
            '2026-06-27',
            '2026-07',
            'Juni 2026 (4 Minggu, Tutup Buku 27)',
            [
                ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 75000, 'prediksi' => 64, 'wma_mingguan' => 14.8, 'data_mingguan' => [14, 12, 10, 20], 'kenaikan' => 15.5],
                ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 40, 'wma_mingguan' => 9.1, 'data_mingguan' => [7, 10, 8, 10], 'kenaikan' => 12.0],
                ['ranking' => 3, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 25, 'wma_mingguan' => 5.8, 'data_mingguan' => [2, 6, 4, 8], 'kenaikan' => 9.5],
            ],
            [
                ['menu' => 'Smoked Beef & Corn', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Smoked Beef & Corn dapatkan 1 FREE French Fries!', 'icon' => '🎁', 'target' => 'Smoked Beef & Corn'],
            ],
            // ✅ TAMBAHKAN DATA AKTUAL (KOSONGKAN KARENA BELUM LEWAT)
            []
        );

        $this->command->info('✅ SEMUA seeder riwayat prediksi berhasil dijalankan!');
        $this->command->info('📊 Data prediksi dari Desember 2025 - Juni 2026 telah tersimpan.');
        $this->command->info('📌 Data aktual sudah diisi untuk bulan Januari - Juni 2026.');
        $this->command->info('📌 Data aktual Juli 2026 masih kosong (menunggu periode).');
    }

    /**
     * Helper function untuk membuat prediksi DENGAN data aktual
     */
    private function createPrediction($tanggalPrediksi, $bulanTarget, $dataYangDipakai, $hasilPrediksi, $rekomendasiPromosi, $detailAktual = null)
    {
        RiwayatPrediksi::create([
            'tanggal_prediksi' => Carbon::createFromFormat('Y-m-d H:i:s', $tanggalPrediksi . ' 12:00:00'),
            'bulan_target' => $bulanTarget,
            'data_yang_dipakai' => $dataYangDipakai,
            'hasil_prediksi' => $hasilPrediksi,
            'rekomendasi_promosi' => $rekomendasiPromosi,
            'rata_rata_akurasi' => null,
            'detail_akurasi' => $detailAktual, // ✅ SEKARANG DIISI!
        ]);
    }
}