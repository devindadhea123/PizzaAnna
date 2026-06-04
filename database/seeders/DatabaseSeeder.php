<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(KategoriSeeder::class);
        $this->call(MenuSeeder::class);           
        $this->call(PizzaUkuranSeeder::class);
        $this->call(ToppingSeeder::class);
        $this->call(DataJanuariMei2026Seeder::class);
    }
}