<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatPrediksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data contoh untuk 3 bulan prediksi
        $riwayatPrediksi = [
            [
                'tanggal_prediksi' => '2026-03-27 12:00:00',
                'bulan_target' => '2026-04',
                'data_yang_dipakai' => 'September 2025 - Februari 2026',
                'hasil_prediksi' => json_encode([
                    [
                        'ranking' => 1,
                        'nama_menu' => 'Spicy Chicken Mushroom',
                        'harga' => 71000,
                        'prediksi' => 185,
                        'kenaikan' => 12.5
                    ],
                    [
                        'ranking' => 2,
                        'nama_menu' => 'Meat Lover',
                        'harga' => 75000,
                        'prediksi' => 162,
                        'kenaikan' => 8.3
                    ],
                    [
                        'ranking' => 3,
                        'nama_menu' => 'Pepperoni Mushroom',
                        'harga' => 63000,
                        'prediksi' => 148,
                        'kenaikan' => -5.2
                    ]
                ]),
                'rata_rata_akurasi' => 92.50,
                'detail_akurasi' => json_encode([
                    [
                        'nama_menu' => 'Spicy Chicken Mushroom',
                        'prediksi' => 185,
                        'aktual' => 178,
                        'akurasi' => 96.07,
                        'kategori' => 'Sangat Akurat'
                    ],
                    [
                        'nama_menu' => 'Meat Lover',
                        'prediksi' => 162,
                        'aktual' => 155,
                        'akurasi' => 95.48,
                        'kategori' => 'Sangat Akurat'
                    ],
                    [
                        'nama_menu' => 'Pepperoni Mushroom',
                        'prediksi' => 148,
                        'aktual' => 152,
                        'akurasi' => 97.37,
                        'kategori' => 'Sangat Akurat'
                    ]
                ]),
                'rekomendasi_promosi' => json_encode([
                    [
                        'menu' => 'Spicy Chicken Mushroom',
                        'judul' => 'BUY 1 GET 1',
                        'deskripsi' => 'Beli 1 Spicy Chicken Mushroom dapatkan 1 FREE French Fries!',
                        'target' => 'Spicy Chicken Mushroom'
                    ],
                    [
                        'menu' => 'Paket Keluarga',
                        'judul' => 'PAKET HEMAT',
                        'deskripsi' => '2 Meat Lover + 2 Teh Es hanya Rp 150.000!',
                        'target' => 'Meat Lover + Minuman'
                    ]
                ]),
                'created_at' => '2026-03-27 12:00:00',
                'updated_at' => '2026-03-27 12:00:00',
            ],
            [
                'tanggal_prediksi' => '2026-02-27 12:00:00',
                'bulan_target' => '2026-03',
                'data_yang_dipakai' => 'Agustus 2025 - Januari 2026',
                'hasil_prediksi' => json_encode([
                    [
                        'ranking' => 1,
                        'nama_menu' => 'Favorite Pizza',
                        'harga' => 95000,
                        'prediksi' => 142,
                        'kenaikan' => 15.2
                    ],
                    [
                        'ranking' => 2,
                        'nama_menu' => 'Smoked Beef & Corn',
                        'harga' => 75000,
                        'prediksi' => 128,
                        'kenaikan' => 7.8
                    ],
                    [
                        'ranking' => 3,
                        'nama_menu' => 'Chicken Burger',
                        'harga' => 15000,
                        'prediksi' => 115,
                        'kenaikan' => 10.5
                    ]
                ]),
                'rata_rata_akurasi' => 88.75,
                'detail_akurasi' => json_encode([
                    [
                        'nama_menu' => 'Favorite Pizza',
                        'prediksi' => 142,
                        'aktual' => 135,
                        'akurasi' => 94.81,
                        'kategori' => 'Sangat Akurat'
                    ],
                    [
                        'nama_menu' => 'Smoked Beef & Corn',
                        'prediksi' => 128,
                        'aktual' => 120,
                        'akurasi' => 93.33,
                        'kategori' => 'Sangat Akurat'
                    ],
                    [
                        'nama_menu' => 'Chicken Burger',
                        'prediksi' => 115,
                        'aktual' => 98,
                        'akurasi' => 82.65,
                        'kategori' => 'Akurat'
                    ]
                ]),
                'rekomendasi_promosi' => json_encode([
                    [
                        'menu' => 'Favorite Pizza',
                        'judul' => 'FLASH SALE',
                        'deskripsi' => 'Favorite Pizza diskon 20% setiap jam 12-14 siang!',
                        'target' => 'Favorite Pizza'
                    ]
                ]),
                'created_at' => '2026-02-27 12:00:00',
                'updated_at' => '2026-02-27 12:00:00',
            ],
            [
                'tanggal_prediksi' => '2026-01-27 12:00:00',
                'bulan_target' => '2026-02',
                'data_yang_dipakai' => 'Juli 2025 - Desember 2025',
                'hasil_prediksi' => json_encode([
                    [
                        'ranking' => 1,
                        'nama_menu' => 'Spicy Chicken Mushroom',
                        'harga' => 71000,
                        'prediksi' => 165,
                        'kenaikan' => 18.5
                    ],
                    [
                        'ranking' => 2,
                        'nama_menu' => 'French Fries',
                        'harga' => 9000,
                        'prediksi' => 140,
                        'kenaikan' => 12.3
                    ],
                    [
                        'ranking' => 3,
                        'nama_menu' => 'Choco Blend',
                        'harga' => 12000,
                        'prediksi' => 125,
                        'kenaikan' => 5.8
                    ]
                ]),
                'rata_rata_akurasi' => null, // Belum ada akurasi karena bulan target belum berakhir
                'detail_akurasi' => null,
                'rekomendasi_promosi' => json_encode([
                    [
                        'menu' => 'Spicy Chicken Mushroom',
                        'judul' => 'BUNDLING HEMAT',
                        'deskripsi' => 'Spicy Chicken Mushroom + Choco Blend hanya Rp 75.000!',
                        'target' => 'Spicy Chicken Mushroom + Minuman'
                    ]
                ]),
                'created_at' => '2026-01-27 12:00:00',
                'updated_at' => '2026-01-27 12:00:00',
            ],
        ];

        foreach ($riwayatPrediksi as $data) {
            DB::table('riwayat_prediksi')->insert($data);
        }
    }
}