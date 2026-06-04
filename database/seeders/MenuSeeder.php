<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        DB::table('menu')->insert([
            // PIZZA (ID 1-9)
            ['id_menu' => 1, 'nama_menu' => 'Spicy Chicken Mushroom', 'deskripsi' => 'Ayam, jamur, keju', 'id_kategori' => 1, 'harga' => 25000],
            ['id_menu' => 2, 'nama_menu' => 'Pepperoni Mushroom', 'deskripsi' => 'Keju, pepperoni, jamur', 'id_kategori' => 1, 'harga' => 42000],
            ['id_menu' => 3, 'nama_menu' => 'Meat Lover', 'deskripsi' => 'Sosis, bratwurst, kornet, keju', 'id_kategori' => 1, 'harga' => 26000],
            ['id_menu' => 4, 'nama_menu' => 'Margerita', 'deskripsi' => 'Keju & tomat', 'id_kategori' => 1, 'harga' => 19000],
            ['id_menu' => 5, 'nama_menu' => 'Favorite Pizza', 'deskripsi' => 'Sosis, beef, kornet, pepperoni', 'id_kategori' => 1, 'harga' => 35000],
            ['id_menu' => 6, 'nama_menu' => 'Bratwurst Pizza', 'deskripsi' => 'Bratwurst, keju, onion, paprika', 'id_kategori' => 1, 'harga' => 30000],
            ['id_menu' => 7, 'nama_menu' => 'Beef Bolognese Pizza', 'deskripsi' => 'Beef, keju, jamur, paprika', 'id_kategori' => 1, 'harga' => 38000],
            ['id_menu' => 8, 'nama_menu' => 'Smoked Beef & Corn', 'deskripsi' => 'Smoked beef, keju, jagung', 'id_kategori' => 1, 'harga' => 26000],
            ['id_menu' => 9, 'nama_menu' => 'Tuna Onion Pizza', 'deskripsi' => 'Tuna, keju, onion', 'id_kategori' => 1, 'harga' => 26000],
            
            // BURGER (ID 10-11)
            ['id_menu' => 10, 'nama_menu' => 'Chicken Burger', 'deskripsi' => null, 'id_kategori' => 2, 'harga' => 15000],
            ['id_menu' => 11, 'nama_menu' => 'Beef Burger', 'deskripsi' => null, 'id_kategori' => 2, 'harga' => 16000],
            
            // OTHERS (ID 12-15)
            ['id_menu' => 12, 'nama_menu' => 'Donut / Bomboloni', 'deskripsi' => 'Isi 5 pcs', 'id_kategori' => 3, 'harga' => 10000],
            ['id_menu' => 13, 'nama_menu' => 'Spaghetti Bolognese', 'deskripsi' => null, 'id_kategori' => 3, 'harga' => 18000],
            ['id_menu' => 14, 'nama_menu' => 'Mozarella Stick', 'deskripsi' => null, 'id_kategori' => 3, 'harga' => 12000],
            ['id_menu' => 15, 'nama_menu' => 'French Fries', 'deskripsi' => null, 'id_kategori' => 3, 'harga' => 9000],
            
            // BEVERAGE (ID 16-21)
            ['id_menu' => 16, 'nama_menu' => 'Sundae Ice Cream', 'deskripsi' => null, 'id_kategori' => 4, 'harga' => 9000],
            ['id_menu' => 17, 'nama_menu' => 'Teh / Es Teh', 'deskripsi' => null, 'id_kategori' => 4, 'harga' => 4000],
            ['id_menu' => 18, 'nama_menu' => 'Jeruk / Es Jeruk', 'deskripsi' => null, 'id_kategori' => 4, 'harga' => 5000],
            ['id_menu' => 19, 'nama_menu' => 'Cappucino', 'deskripsi' => null, 'id_kategori' => 4, 'harga' => 6000],
            ['id_menu' => 20, 'nama_menu' => 'Choco Blend', 'deskripsi' => null, 'id_kategori' => 4, 'harga' => 12000],
            ['id_menu' => 21, 'nama_menu' => 'Strawberry Blend', 'deskripsi' => null, 'id_kategori' => 4, 'harga' => 12000],
            
            // EXTRA (ID 22)
            ['id_menu' => 22, 'nama_menu' => 'Completo', 'deskripsi' => 'Pizza completa', 'id_kategori' => 1, 'harga' => 79000],
        ]);
    }
}