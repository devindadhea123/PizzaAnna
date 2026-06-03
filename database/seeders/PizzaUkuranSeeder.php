<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PizzaUkuranSeeder extends Seeder
{
    public function run(): void
    {
        // AMBIL MENU PIZZA DARI DATABASE (AMAN FK)
        $menus = DB::table('menu')
            ->where('nama_menu', 'like', '%Pizza%')
            ->get();

        if ($menus->isEmpty()) {
            return;
        }

        foreach ($menus as $menu) {

            DB::table('pizza_ukuran')->insert([
                [
                    'id_menu' => $menu->id_menu,
                    'ukuran' => 'S',
                    'harga' => $menu->harga,
                ],
                [
                    'id_menu' => $menu->id_menu,
                    'ukuran' => 'M',
                    'harga' => $menu->harga + 15000,
                ],
                [
                    'id_menu' => $menu->id_menu,
                    'ukuran' => 'L',
                    'harga' => $menu->harga + 30000,
                ],
            ]);
        }
    }
}