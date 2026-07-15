<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPrediksi;

class RiwayatPrediksiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ==================== DESEMBER 2025 ====================
            [
                'tanggal_prediksi' => '2025-11-27 12:00:00',
                'bulan_target' => '2025-12',
                'data_yang_dipakai' => 'November 2025',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 12, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 10, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 8, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'FLASH SALE', 'deskripsi' => 'Smoked Beef & Corn diskon 20%! Promo terbatas!'],
                ]),
                'rata_rata_akurasi' => 85.5,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 12, 'aktual' => 11, 'akurasi' => 91.67, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 10, 'aktual' => 9, 'akurasi' => 90.00, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 8, 'aktual' => 7, 'akurasi' => 87.50, 'kategori' => 'Akurat'],
                ]),
            ],

            // ==================== JANUARI 2026 ====================
            [
                'tanggal_prediksi' => '2025-12-27 12:00:00',
                'bulan_target' => '2026-01',
                'data_yang_dipakai' => 'Desember 2025',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 14, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 12, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 10, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Smoked Beef & Corn dapatkan 1 FREE!'],
                ]),
                'rata_rata_akurasi' => 88.2,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 14, 'aktual' => 13, 'akurasi' => 92.86, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 12, 'aktual' => 11, 'akurasi' => 91.67, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 10, 'aktual' => 9, 'akurasi' => 90.00, 'kategori' => 'Sangat Akurat'],
                ]),
            ],

            // ==================== FEBRUARI 2026 ====================
            [
                'tanggal_prediksi' => '2026-01-27 12:00:00',
                'bulan_target' => '2026-02',
                'data_yang_dipakai' => 'Desember 2025 - Januari 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 15, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 13, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 11, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'PAKET KELUARGA', 'deskripsi' => 'Smoked Beef & Corn + Minuman hanya Rp 30.000!'],
                ]),
                'rata_rata_akurasi' => 87.5,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 15, 'aktual' => 14, 'akurasi' => 93.33, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 13, 'aktual' => 12, 'akurasi' => 92.31, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 11, 'aktual' => 10, 'akurasi' => 90.91, 'kategori' => 'Sangat Akurat'],
                ]),
            ],

            // ==================== MARET 2026 ====================
            [
                'tanggal_prediksi' => '2026-02-27 12:00:00',
                'bulan_target' => '2026-03',
                'data_yang_dipakai' => 'Desember 2025 - Februari 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 16, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 14, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 12, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'FLASH SALE', 'deskripsi' => 'Smoked Beef & Corn diskon 15%! Promo terbatas!'],
                ]),
                'rata_rata_akurasi' => 89.1,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 16, 'aktual' => 15, 'akurasi' => 93.75, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 14, 'aktual' => 13, 'akurasi' => 92.86, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 12, 'aktual' => 11, 'akurasi' => 91.67, 'kategori' => 'Sangat Akurat'],
                ]),
            ],

            // ==================== APRIL 2026 ====================
            [
                'tanggal_prediksi' => '2026-03-27 12:00:00',
                'bulan_target' => '2026-04',
                'data_yang_dipakai' => 'Desember 2025 - Maret 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 17, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 15, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 13, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Smoked Beef & Corn dapatkan 1 FREE French Fries!'],
                ]),
                'rata_rata_akurasi' => 90.3,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 17, 'aktual' => 16, 'akurasi' => 94.12, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 15, 'aktual' => 14, 'akurasi' => 93.33, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 13, 'aktual' => 12, 'akurasi' => 92.31, 'kategori' => 'Sangat Akurat'],
                ]),
            ],

            // ==================== MEI 2026 ====================
            [
                'tanggal_prediksi' => '2026-04-27 12:00:00',
                'bulan_target' => '2026-05',
                'data_yang_dipakai' => 'Desember 2025 - April 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 18, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 16, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 14, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'PAKET KELUARGA', 'deskripsi' => 'Smoked Beef & Corn + Teh Es + French Fries hanya Rp 35.000!'],
                ]),
                'rata_rata_akurasi' => 91.4,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 18, 'aktual' => 17, 'akurasi' => 94.44, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 16, 'aktual' => 15, 'akurasi' => 93.75, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 14, 'aktual' => 13, 'akurasi' => 92.86, 'kategori' => 'Sangat Akurat'],
                ]),
            ],

            // ==================== JUNI 2026 ====================
            [
                'tanggal_prediksi' => '2026-05-27 12:00:00',
                'bulan_target' => '2026-06',
                'data_yang_dipakai' => 'Desember 2025 - Mei 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 26000, 'prediksi' => 19, 'kenaikan' => 0],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 17, 'kenaikan' => 0],
                    ['ranking' => 3, 'nama_menu' => 'Completo', 'harga' => 79000, 'prediksi' => 15, 'kenaikan' => 0],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'FLASH SALE', 'deskripsi' => 'Smoked Beef & Corn diskon 20%! Hanya untuk bulan ini!'],
                    ['menu' => 'Teh / Es Teh', 'judul' => 'BUNDLING HEMAT', 'deskripsi' => 'Teh / Es Teh + French Fries hanya Rp 12.000!'],
                ]),
                'rata_rata_akurasi' => 92.8,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 19, 'aktual' => 18, 'akurasi' => 94.74, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 17, 'aktual' => 16, 'akurasi' => 94.12, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Completo', 'prediksi' => 15, 'aktual' => 14, 'akurasi' => 93.33, 'kategori' => 'Sangat Akurat'],
                ]),
            ],
        ];

        foreach ($data as $item) {
            RiwayatPrediksi::create($item);
        }

        $this->command->info('✅ Riwayat prediksi Desember 2025 - Juni 2026 berhasil diisi!');
        $this->command->info('📊 Total: ' . count($data) . ' bulan');
    }
}