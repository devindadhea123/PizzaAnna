<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToppingSeeder extends Seeder
{
    public function run()
    {
        DB::table('toppings')->insert([
            ['id_topping' => 1, 'nama_topping' => 'Keju Pinggir', 'ukuran' => 'L', 'harga' => 25000],
            ['id_topping' => 2, 'nama_topping' => 'Keju Topping', 'ukuran' => 'L', 'harga' => 20000],
            ['id_topping' => 3, 'nama_topping' => 'Keju Bites', 'ukuran' => 'L', 'harga' => 25000],
            ['id_topping' => 4, 'nama_topping' => 'Sosis Bites', 'ukuran' => 'L', 'harga' => 15000],
            ['id_topping' => 5, 'nama_topping' => 'Tambah Keju', 'ukuran' => 'S', 'harga' => 5000],
        ]);
    }
}