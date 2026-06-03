<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
DB::table('toppings')->delete();
DB::table('toppings')->insert([

            // KEJU TOPPING
            [
                'nama_topping' => 'Keju Topping',
                'ukuran' => 'S',
                'harga' => 5000,
            ],
            [
                'nama_topping' => 'Keju Topping',
                'ukuran' => 'M',
                'harga' => 10000,
            ],
            [
                'nama_topping' => 'Keju Topping',
                'ukuran' => 'L',
                'harga' => 20000,
            ],

            // KEJU PINGGIR
            [
                'nama_topping' => 'Keju Pinggir',
                'ukuran' => 'S',
                'harga' => 20000,
            ],
            [
                'nama_topping' => 'Keju Pinggir',
                'ukuran' => 'M',
                'harga' => 25000,
            ],
            [
                'nama_topping' => 'Keju Pinggir',
                'ukuran' => 'L',
                'harga' => 40000,
            ],

            // KEJU BITES
            [
                'nama_topping' => 'Keju Bites',
                'ukuran' => 'S',
                'harga' => 20000,
            ],
            [
                'nama_topping' => 'Keju Bites',
                'ukuran' => 'M',
                'harga' => 25000,
            ],
            [
                'nama_topping' => 'Keju Bites',
                'ukuran' => 'L',
                'harga' => 40000,
            ],

            // SOSIS BITES
            [
                'nama_topping' => 'Sosis Bites',
                'ukuran' => 'S',
                'harga' => 5000,
            ],
            [
                'nama_topping' => 'Sosis Bites',
                'ukuran' => 'M',
                'harga' => 10000,
            ],
            [
                'nama_topping' => 'Sosis Bites',
                'ukuran' => 'L',
                'harga' => 15000,
            ],

        ]);
    }
}