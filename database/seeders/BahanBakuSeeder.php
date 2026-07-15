<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanBaku;

class BahanBakuSeeder extends Seeder
{
    public function run()
    {
        $bahan = [
            // ==================== BAHAN DASAR PIZZA ====================
            ['nama_bahan' => 'Tepung Terigu', 'satuan' => 'gram', 'stok' => 10000, 'stok_minimal' => 1000],
            ['nama_bahan' => 'Keju Mozarella', 'satuan' => 'gram', 'stok' => 5000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Saus Tomat', 'satuan' => 'ml', 'stok' => 3000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Saus Bolognese', 'satuan' => 'ml', 'stok' => 2000, 'stok_minimal' => 300],
            
            // ==================== TOPPING PIZZA ====================
            ['nama_bahan' => 'Ayam Suwir', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Jamur', 'satuan' => 'gram', 'stok' => 1500, 'stok_minimal' => 200],
            ['nama_bahan' => 'Pepperoni', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Daging Sapi Cincang', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Sosis', 'satuan' => 'gram', 'stok' => 1500, 'stok_minimal' => 200],
            ['nama_bahan' => 'Smoked Beef', 'satuan' => 'gram', 'stok' => 1500, 'stok_minimal' => 200],
            ['nama_bahan' => 'Bratwurst Sosis', 'satuan' => 'gram', 'stok' => 1500, 'stok_minimal' => 200],
            ['nama_bahan' => 'Tuna Kalengan', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Jagung Manis', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Paprika', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Bawang Bombay', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Cabai Bubuk', 'satuan' => 'gram', 'stok' => 500, 'stok_minimal' => 100],
            
            // ==================== BAHAN BURGER ====================
            ['nama_bahan' => 'Roti Burger', 'satuan' => 'buah', 'stok' => 100, 'stok_minimal' => 20],
            ['nama_bahan' => 'Dada Ayam Fillet', 'satuan' => 'gram', 'stok' => 3000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Daging Sapi Giling', 'satuan' => 'gram', 'stok' => 3000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Selada', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Tomat', 'satuan' => 'iris', 'stok' => 100, 'stok_minimal' => 20],
            ['nama_bahan' => 'Keju Slice', 'satuan' => 'lembar', 'stok' => 100, 'stok_minimal' => 20],
            ['nama_bahan' => 'Mayones', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Saus Sambal', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            
            // ==================== BAHAN LAINNYA ====================
            ['nama_bahan' => 'Kentang', 'satuan' => 'gram', 'stok' => 5000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Minyak Goreng', 'satuan' => 'ml', 'stok' => 5000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Garam', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Gula', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Telur', 'satuan' => 'butir', 'stok' => 100, 'stok_minimal' => 20],
            ['nama_bahan' => 'Mentega', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Tepung Roti', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Spaghetti', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Keju Parmesan', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            
            // ==================== BAHAN MINUMAN ====================
            ['nama_bahan' => 'Teh Celup', 'satuan' => 'sachet', 'stok' => 200, 'stok_minimal' => 50],
            ['nama_bahan' => 'Air', 'satuan' => 'ml', 'stok' => 10000, 'stok_minimal' => 1000],
            ['nama_bahan' => 'Es Batu', 'satuan' => 'gram', 'stok' => 5000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Sirup Jeruk', 'satuan' => 'ml', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Susu UHT', 'satuan' => 'ml', 'stok' => 5000, 'stok_minimal' => 500],
            ['nama_bahan' => 'Choco Blend Bubuk', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Strawberry Sirup', 'satuan' => 'ml', 'stok' => 2000, 'stok_minimal' => 300],
            ['nama_bahan' => 'Kopi Bubuk', 'satuan' => 'gram', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Es Krim Vanilla', 'satuan' => 'scoop', 'stok' => 100, 'stok_minimal' => 20],
            ['nama_bahan' => 'Saus Coklat', 'satuan' => 'ml', 'stok' => 1000, 'stok_minimal' => 200],
            ['nama_bahan' => 'Sprinkles', 'satuan' => 'gram', 'stok' => 500, 'stok_minimal' => 100],
        ];

        foreach ($bahan as $item) {
            BahanBaku::create($item);
        }

        $this->command->info('✅ Bahan baku berhasil diisi!');
    }
}