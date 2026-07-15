<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPrediksi;

class RiwayatPrediksiRealSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ==================== DESEMBER 2025 ====================
            [
                'tanggal_prediksi' => '2025-11-27 12:00:00',
                'bulan_target' => '2025-12',
                'data_yang_dipakai' => 'Mei - Oktober 2025',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Spicy Chicken Mushroom', 'harga' => 71000, 'prediksi' => 165, 'kenaikan' => 12.5],
                    ['ranking' => 2, 'nama_menu' => 'French Fries', 'harga' => 9000, 'prediksi' => 140, 'kenaikan' => 8.3],
                    ['ranking' => 3, 'nama_menu' => 'Choco Blend', 'harga' => 12000, 'prediksi' => 125, 'kenaikan' => 5.2],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Spicy Chicken Mushroom', 'judul' => 'FLASH SALE', 'deskripsi' => 'Spicy Chicken Mushroom diskon 20%! Hanya untuk bulan ini!'],
                    ['menu' => 'French Fries', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 French Fries dapatkan 1 FREE!'],
                ]),
                'rata_rata_akurasi' => 88.5,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 165, 'aktual' => 158, 'akurasi' => 95.57, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'French Fries', 'prediksi' => 140, 'aktual' => 132, 'akurasi' => 93.94, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Choco Blend', 'prediksi' => 125, 'aktual' => 118, 'akurasi' => 94.07, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2025-11-27 12:00:00',
                'updated_at' => '2025-11-27 12:00:00',
            ],
            
            // ==================== JANUARI 2026 ====================
            [
                'tanggal_prediksi' => '2025-12-27 12:00:00',
                'bulan_target' => '2026-01',
                'data_yang_dipakai' => 'Juni - November 2025',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Favorite Pizza', 'harga' => 95000, 'prediksi' => 142, 'kenaikan' => 15.2],
                    ['ranking' => 2, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 75000, 'prediksi' => 128, 'kenaikan' => 7.8],
                    ['ranking' => 3, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 115, 'kenaikan' => 10.5],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Favorite Pizza', 'judul' => 'FLASH SALE', 'deskripsi' => 'Favorite Pizza diskon 20%! Promo terbatas!'],
                    ['menu' => 'Chicken Burger', 'judul' => 'BUNDLING HEMAT', 'deskripsi' => 'Chicken Burger + Teh Es hanya Rp 18.000!'],
                ]),
                'rata_rata_akurasi' => 85.3,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Favorite Pizza', 'prediksi' => 142, 'aktual' => 135, 'akurasi' => 94.81, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 128, 'aktual' => 120, 'akurasi' => 93.33, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Chicken Burger', 'prediksi' => 115, 'aktual' => 98, 'akurasi' => 82.65, 'kategori' => 'Akurat'],
                ]),
                'created_at' => '2025-12-27 12:00:00',
                'updated_at' => '2025-12-27 12:00:00',
            ],

            // ==================== FEBRUARI 2026 ====================
            [
                'tanggal_prediksi' => '2026-01-27 12:00:00',
                'bulan_target' => '2026-02',
                'data_yang_dipakai' => 'Juli - Desember 2025',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Spicy Chicken Mushroom', 'harga' => 71000, 'prediksi' => 185, 'kenaikan' => 12.5],
                    ['ranking' => 2, 'nama_menu' => 'Meat Lover', 'harga' => 75000, 'prediksi' => 162, 'kenaikan' => 8.3],
                    ['ranking' => 3, 'nama_menu' => 'Pepperoni Mushroom', 'harga' => 63000, 'prediksi' => 148, 'kenaikan' => -5.2],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Spicy Chicken Mushroom', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Spicy Chicken Mushroom dapatkan 1 FREE French Fries!'],
                    ['menu' => 'Meat Lover', 'judul' => 'PAKET KELUARGA', 'deskripsi' => '2 Meat Lover + 2 Teh Es hanya Rp 150.000!'],
                ]),
                'rata_rata_akurasi' => 90.2,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 185, 'aktual' => 178, 'akurasi' => 96.07, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Meat Lover', 'prediksi' => 162, 'aktual' => 155, 'akurasi' => 95.48, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Pepperoni Mushroom', 'prediksi' => 148, 'aktual' => 152, 'akurasi' => 97.37, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2026-01-27 12:00:00',
                'updated_at' => '2026-01-27 12:00:00',
            ],

            // ==================== MARET 2026 ====================
            [
                'tanggal_prediksi' => '2026-02-27 12:00:00',
                'bulan_target' => '2026-03',
                'data_yang_dipakai' => 'Agustus 2025 - Januari 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 75000, 'prediksi' => 155, 'kenaikan' => 16.8],
                    ['ranking' => 2, 'nama_menu' => 'Favorite Pizza', 'harga' => 95000, 'prediksi' => 138, 'kenaikan' => 9.2],
                    ['ranking' => 3, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 130, 'kenaikan' => 4.5],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'BUNDLING HEMAT', 'deskripsi' => 'Smoked Beef & Corn + Teh Es hanya Rp 75.000!'],
                    ['menu' => 'Favorite Pizza', 'judul' => 'FLASH SALE', 'deskripsi' => 'Favorite Pizza diskon 15%! Promo terbatas!'],
                ]),
                'rata_rata_akurasi' => 84.7,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 155, 'aktual' => 148, 'akurasi' => 95.27, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Favorite Pizza', 'prediksi' => 138, 'aktual' => 125, 'akurasi' => 89.60, 'kategori' => 'Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 130, 'aktual' => 122, 'akurasi' => 93.44, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2026-02-27 12:00:00',
                'updated_at' => '2026-02-27 12:00:00',
            ],

            // ==================== APRIL 2026 ====================
            [
                'tanggal_prediksi' => '2026-03-27 12:00:00',
                'bulan_target' => '2026-04',
                'data_yang_dipakai' => 'September 2025 - Februari 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Meat Lover', 'harga' => 75000, 'prediksi' => 170, 'kenaikan' => 22.5],
                    ['ranking' => 2, 'nama_menu' => 'Spicy Chicken Mushroom', 'harga' => 71000, 'prediksi' => 155, 'kenaikan' => 10.8],
                    ['ranking' => 3, 'nama_menu' => 'French Fries', 'harga' => 9000, 'prediksi' => 145, 'kenaikan' => 12.3],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Meat Lover', 'judul' => 'PAKET KELUARGA', 'deskripsi' => '2 Meat Lover + 2 Minuman hanya Rp 160.000!'],
                    ['menu' => 'Spicy Chicken Mushroom', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Spicy Chicken Mushroom dapatkan 1 FREE!'],
                ]),
                'rata_rata_akurasi' => 91.2,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Meat Lover', 'prediksi' => 170, 'aktual' => 162, 'akurasi' => 95.06, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 155, 'aktual' => 148, 'akurasi' => 95.27, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'French Fries', 'prediksi' => 145, 'aktual' => 138, 'akurasi' => 94.93, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2026-03-27 12:00:00',
                'updated_at' => '2026-03-27 12:00:00',
            ],

            // ==================== MEI 2026 ====================
            [
                'tanggal_prediksi' => '2026-04-27 12:00:00',
                'bulan_target' => '2026-05',
                'data_yang_dipakai' => 'Oktober 2025 - Maret 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Pepperoni Mushroom', 'harga' => 63000, 'prediksi' => 160, 'kenaikan' => 14.2],
                    ['ranking' => 2, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 148, 'kenaikan' => 9.8],
                    ['ranking' => 3, 'nama_menu' => 'Choco Blend', 'harga' => 12000, 'prediksi' => 135, 'kenaikan' => 6.5],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Pepperoni Mushroom', 'judul' => 'FLASH SALE', 'deskripsi' => 'Pepperoni Mushroom diskon 20%! Hanya untuk bulan ini!'],
                    ['menu' => 'Chicken Burger', 'judul' => 'BUNDLING HEMAT', 'deskripsi' => 'Chicken Burger + Choco Blend hanya Rp 25.000!'],
                ]),
                'rata_rata_akurasi' => 89.8,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Pepperoni Mushroom', 'prediksi' => 160, 'aktual' => 152, 'akurasi' => 94.74, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Chicken Burger', 'prediksi' => 148, 'aktual' => 138, 'akurasi' => 92.75, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Choco Blend', 'prediksi' => 135, 'aktual' => 125, 'akurasi' => 92.00, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2026-04-27 12:00:00',
                'updated_at' => '2026-04-27 12:00:00',
            ],

            // ==================== JUNI 2026 ====================
            [
                'tanggal_prediksi' => '2026-05-27 12:00:00',
                'bulan_target' => '2026-06',
                'data_yang_dipakai' => 'November 2025 - April 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Favorite Pizza', 'harga' => 95000, 'prediksi' => 175, 'kenaikan' => 20.5],
                    ['ranking' => 2, 'nama_menu' => 'Meat Lover', 'harga' => 75000, 'prediksi' => 158, 'kenaikan' => 12.3],
                    ['ranking' => 3, 'nama_menu' => 'Spicy Chicken Mushroom', 'harga' => 71000, 'prediksi' => 145, 'kenaikan' => 8.7],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Favorite Pizza', 'judul' => 'PAKET KELUARGA', 'deskripsi' => '2 Favorite Pizza + 2 Minuman hanya Rp 180.000!'],
                    ['menu' => 'Meat Lover', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Meat Lover dapatkan 1 FREE French Fries!'],
                ]),
                'rata_rata_akurasi' => 92.5,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Favorite Pizza', 'prediksi' => 175, 'aktual' => 168, 'akurasi' => 95.83, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Meat Lover', 'prediksi' => 158, 'aktual' => 150, 'akurasi' => 94.67, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 145, 'aktual' => 138, 'akurasi' => 94.93, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2026-05-27 12:00:00',
                'updated_at' => '2026-05-27 12:00:00',
            ],

            // ==================== JULI 2026 ====================
            [
                'tanggal_prediksi' => '2026-06-27 12:00:00',
                'bulan_target' => '2026-07',
                'data_yang_dipakai' => 'Desember 2025 - Mei 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Smoked Beef & Corn', 'harga' => 75000, 'prediksi' => 168, 'kenaikan' => 18.2],
                    ['ranking' => 2, 'nama_menu' => 'Teh / Es Teh', 'harga' => 4000, 'prediksi' => 155, 'kenaikan' => 11.5],
                    ['ranking' => 3, 'nama_menu' => 'Chicken Burger', 'harga' => 15000, 'prediksi' => 142, 'kenaikan' => 9.3],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Smoked Beef & Corn', 'judul' => 'FLASH SALE', 'deskripsi' => 'Smoked Beef & Corn diskon 20%! Promo terbatas!'],
                    ['menu' => 'Teh / Es Teh', 'judul' => 'BUNDLING HEMAT', 'deskripsi' => 'Teh / Es Teh + French Fries hanya Rp 12.000!'],
                ]),
                'rata_rata_akurasi' => 87.4,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Smoked Beef & Corn', 'prediksi' => 168, 'aktual' => 160, 'akurasi' => 95.00, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 155, 'aktual' => 145, 'akurasi' => 93.10, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Chicken Burger', 'prediksi' => 142, 'aktual' => 132, 'akurasi' => 92.42, 'kategori' => 'Sangat Akurat'],
                ]),
                'created_at' => '2026-06-27 12:00:00',
                'updated_at' => '2026-06-27 12:00:00',
            ],

            // ==================== AGUSTUS 2026 ====================
            [
                'tanggal_prediksi' => '2026-07-27 12:00:00',
                'bulan_target' => '2026-08',
                'data_yang_dipakai' => 'Januari - Juni 2026',
                'hasil_prediksi' => json_encode([
                    ['ranking' => 1, 'nama_menu' => 'Meat Lover', 'harga' => 75000, 'prediksi' => 180, 'kenaikan' => 22.5],
                    ['ranking' => 2, 'nama_menu' => 'Spicy Chicken Mushroom', 'harga' => 71000, 'prediksi' => 165, 'kenaikan' => 15.3],
                    ['ranking' => 3, 'nama_menu' => 'Favorite Pizza', 'harga' => 95000, 'prediksi' => 150, 'kenaikan' => 10.8],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Meat Lover', 'judul' => 'PAKET KELUARGA', 'deskripsi' => '2 Meat Lover + 2 Minuman hanya Rp 160.000! Hemat 20%!'],
                    ['menu' => 'Spicy Chicken Mushroom', 'judul' => 'BUY 1 GET 1', 'deskripsi' => 'Beli 1 Spicy Chicken Mushroom dapatkan 1 FREE French Fries!'],
                    ['menu' => 'Favorite Pizza', 'judul' => 'FLASH SALE', 'deskripsi' => 'Favorite Pizza diskon 15%! Hanya untuk bulan ini!'],
                ]),
                'rata_rata_akurasi' => null,
                'detail_akurasi' => null,
                'created_at' => '2026-07-27 12:00:00',
                'updated_at' => '2026-07-27 12:00:00',
            ],
        ];

        foreach ($data as $item) {
            RiwayatPrediksi::create($item);
        }

        $this->command->info('✅ Riwayat prediksi Desember 2025 - Agustus 2026 berhasil diisi!');
        $this->command->info('📊 Total: ' . count($data) . ' bulan');
    }
}