<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatPrediksiSeeder extends Seeder
{
    public function run(): void
    {
        $prediksiData = [
            [
                'tanggal_prediksi' => Carbon::create(2026, 5, 27, 10, 30, 0),
                'bulan_target' => '2026-06',
                'data_yang_dipakai' => 'Desember 2025 - Mei 2026',
                'hasil_prediksi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 2133, 'kenaikan' => 6.5],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1750, 'kenaikan' => 9.4],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 620, 'kenaikan' => 6.9],
                ]),
                'rata_rata_akurasi' => 94.4,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 2133, 'aktual' => 2150, 'akurasi' => 99.2, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1750, 'aktual' => 1580, 'akurasi' => 89.2, 'kategori' => 'Akurat'],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 620, 'aktual' => 590, 'akurasi' => 94.9, 'kategori' => 'Sangat Akurat'],
                ]),
                'rekomendasi_promosi' => json_encode([
                    ['menu' => 'Teh / Es Teh', 'judul' => 'Buy 1 Get 1', 'deskripsi' => 'Siapkan stok 2x lipat', 'icon' => '🎁'],
                ]),
                'created_at' => Carbon::create(2026, 5, 27, 10, 30, 0),
                'updated_at' => Carbon::create(2026, 5, 27, 10, 30, 0),
            ],
            [
                'tanggal_prediksi' => Carbon::create(2026, 4, 27, 14, 15, 0),
                'bulan_target' => '2026-05',
                'data_yang_dipakai' => 'November 2025 - April 2026',
                'hasil_prediksi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 2100, 'kenaikan' => 5.5],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1680, 'kenaikan' => 8.2],
                    ['nama_menu' => 'French Fries', 'prediksi' => 600, 'kenaikan' => 5.5],
                ]),
                'rata_rata_akurasi' => 86.2,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 2100, 'aktual' => 2050, 'akurasi' => 86.2, 'kategori' => 'Akurat'],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1680, 'aktual' => 1550, 'akurasi' => 85.5, 'kategori' => 'Akurat'],
                    ['nama_menu' => 'French Fries', 'prediksi' => 600, 'aktual' => 580, 'akurasi' => 86.8, 'kategori' => 'Akurat'],
                ]),
                'rekomendasi_promosi' => json_encode([]),
                'created_at' => Carbon::create(2026, 4, 27, 14, 15, 0),
                'updated_at' => Carbon::create(2026, 4, 27, 14, 15, 0),
            ],
            [
                'tanggal_prediksi' => Carbon::create(2026, 3, 27, 9, 45, 0),
                'bulan_target' => '2026-04',
                'data_yang_dipakai' => 'Oktober 2025 - Maret 2026',
                'hasil_prediksi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 1950, 'kenaikan' => 4.8],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1550, 'kenaikan' => 7.5],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 500, 'kenaikan' => 5.2],
                ]),
                'rata_rata_akurasi' => 91.5,
                'detail_akurasi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 1950, 'aktual' => 1900, 'akurasi' => 92.3, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1550, 'aktual' => 1450, 'akurasi' => 90.3, 'kategori' => 'Sangat Akurat'],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 500, 'aktual' => 480, 'akurasi' => 92.0, 'kategori' => 'Sangat Akurat'],
                ]),
                'rekomendasi_promosi' => json_encode([]),
                'created_at' => Carbon::create(2026, 3, 27, 9, 45, 0),
                'updated_at' => Carbon::create(2026, 3, 27, 9, 45, 0),
            ],
            [
                'tanggal_prediksi' => Carbon::create(2026, 2, 27, 11, 0, 0),
                'bulan_target' => '2026-03',
                'data_yang_dipakai' => 'September 2025 - Februari 2026',
                'hasil_prediksi' => json_encode([
                    ['nama_menu' => 'Teh / Es Teh', 'prediksi' => 1850, 'kenaikan' => 4.2],
                    ['nama_menu' => 'Donut / Bomboloni', 'prediksi' => 1450, 'kenaikan' => 6.8],
                    ['nama_menu' => 'Spicy Chicken Mushroom', 'prediksi' => 450, 'kenaikan' => 4.5],
                ]),
                'rata_rata_akurasi' => null,
                'detail_akurasi' => null,
                'rekomendasi_promosi' => json_encode([]),
                'created_at' => Carbon::create(2026, 2, 27, 11, 0, 0),
                'updated_at' => Carbon::create(2026, 2, 27, 11, 0, 0),
            ],
        ];

        foreach ($prediksiData as $data) {
            DB::table('riwayat_prediksi')->insert($data);
        }

        $this->command->info('✅ Riwayat Prediksi berhasil di-generate!');
    }
}