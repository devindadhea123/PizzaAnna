<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        DB::table('kategori')->insert([
            ['id_kategori' => 1, 'nama_kategori' => 'Pizza', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 2, 'nama_kategori' => 'Burger', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 3, 'nama_kategori' => 'Others', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 4, 'nama_kategori' => 'Beverage', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}