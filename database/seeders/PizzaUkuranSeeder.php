<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PizzaUkuranSeeder extends Seeder
{
    public function run()
    {
        DB::table('pizza_ukuran')->insert([
            // Spicy Chicken Mushroom (id_menu = 1)
            ['id_menu' => 1, 'ukuran' => 'S', 'harga' => 25000],
            ['id_menu' => 1, 'ukuran' => 'M', 'harga' => 42000],
            ['id_menu' => 1, 'ukuran' => 'L', 'harga' => 71000],
            
            // Pepperoni Mushroom (id_menu = 2)
            ['id_menu' => 2, 'ukuran' => 'S', 'harga' => 23000],
            ['id_menu' => 2, 'ukuran' => 'M', 'harga' => 38000],
            ['id_menu' => 2, 'ukuran' => 'L', 'harga' => 63000],
            
            // Meat Lover (id_menu = 3)
            ['id_menu' => 3, 'ukuran' => 'S', 'harga' => 26000],
            ['id_menu' => 3, 'ukuran' => 'M', 'harga' => 43000],
            ['id_menu' => 3, 'ukuran' => 'L', 'harga' => 75000],
            
            // Margerita (id_menu = 4)
            ['id_menu' => 4, 'ukuran' => 'S', 'harga' => 19000],
            ['id_menu' => 4, 'ukuran' => 'M', 'harga' => 30000],
            ['id_menu' => 4, 'ukuran' => 'L', 'harga' => 53000],
            
            // Favorite Pizza (id_menu = 5)
            ['id_menu' => 5, 'ukuran' => 'S', 'harga' => 35000],
            ['id_menu' => 5, 'ukuran' => 'M', 'harga' => 61000],
            ['id_menu' => 5, 'ukuran' => 'L', 'harga' => 95000],
            
            // Bratwurst Pizza (id_menu = 6)
            ['id_menu' => 6, 'ukuran' => 'S', 'harga' => 30000],
            ['id_menu' => 6, 'ukuran' => 'M', 'harga' => 51000],
            ['id_menu' => 6, 'ukuran' => 'L', 'harga' => 86000],
            
            // Beef Bolognese Pizza (id_menu = 7)
            ['id_menu' => 7, 'ukuran' => 'S', 'harga' => 30000],
            ['id_menu' => 7, 'ukuran' => 'M', 'harga' => 51000],
            ['id_menu' => 7, 'ukuran' => 'L', 'harga' => 80000],
            
            // Smoked Beef & Corn (id_menu = 8)
            ['id_menu' => 8, 'ukuran' => 'S', 'harga' => 25000],
            ['id_menu' => 8, 'ukuran' => 'M', 'harga' => 41000],
            ['id_menu' => 8, 'ukuran' => 'L', 'harga' => 75000],
            
            // Tuna Onion Pizza (id_menu = 9)
            ['id_menu' => 9, 'ukuran' => 'S', 'harga' => 26000],
            ['id_menu' => 9, 'ukuran' => 'M', 'harga' => 43000],
            ['id_menu' => 9, 'ukuran' => 'L', 'harga' => 76000],
            
            // Completo (id_menu = 22)
            ['id_menu' => 22, 'ukuran' => 'S', 'harga' => 29000],
            ['id_menu' => 22, 'ukuran' => 'M', 'harga' => 47000],
            ['id_menu' => 22, 'ukuran' => 'L', 'harga' => 79000],
        ]);
    }
}