<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function schedule(Schedule $schedule): void  // ⭐ TAMBAHKAN METHOD INI
    {
        // Jadwal prediksi otomatis setiap tanggal 6 pukul 17:10
        $schedule->command('prediksi:otomatis')->cron('05 22 5 * *');
    }
    
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    
}