<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Setting;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        try {
            $day = (int) Setting::get('prediction_day', 27);
            $hour = (int) Setting::get('prediction_hour', 12);
            $minute = (int) Setting::get('prediction_minute', 0);
            $enabled = (int) Setting::get('prediction_enabled', 1);
            
            if ($enabled && $day >= 1 && $day <= 31) {
                $cron = sprintf('%s %s %s * *', $minute, $hour, $day);
                $schedule->command('prediksi:otomatis')->cron($cron);
            }
        } catch (\Exception $e) {
            // Fallback jika tabel settings belum ada
            $schedule->command('prediksi:otomatis')->dailyAt('12:00');
        }
    }
    
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}