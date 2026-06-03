<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
public function run(): void
{
   DB::table('users')->updateOrInsert(
    ['username' => 'admin'],
    [
        'nama_lengkap' => 'Bening (Pemilik)',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'updated_at' => now(),
        'created_at' => now(),
    ]
);

DB::table('users')->updateOrInsert(
    ['username' => 'kasir'],
    [
        'nama_lengkap' => 'Linda (Kasir)',
        'password' => bcrypt('kasir123'),
        'role' => 'kasir',
        'updated_at' => now(),
        'created_at' => now(),
    ]
);
}
}