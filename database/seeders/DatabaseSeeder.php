<?php

namespace Database\Seeders;

use App\Models\PizzaUkuran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
         UserSeeder::class,          
            KategoriSeeder::class,       
            MenuSeeder::class,            
            ToppingSeeder::class,         
            PizzaUkuranSeeder::class,    
            PesananSeeder::class,         
            RiwayatPrediksiSeeder::class, 
        ]);
    }
}