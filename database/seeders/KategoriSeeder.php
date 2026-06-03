<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
    ['nama_kategori' => 'Pizza'],
    ['nama_kategori' => 'Burger'],
    ['nama_kategori' => 'Others Menu'],
    ['nama_kategori' => 'Beverage'],
]);
    }
}