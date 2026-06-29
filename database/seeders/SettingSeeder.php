<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        Setting::set('prediction_day', 27);      // Default tanggal 27
        Setting::set('prediction_hour', 12);     // Default jam 12
        Setting::set('prediction_minute', 0);    // Default menit 0
        Setting::set('prediction_enabled', 1);   // Default aktif
    }
}