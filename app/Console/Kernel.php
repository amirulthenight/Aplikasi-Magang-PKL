<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Kirim pengingat otomatis setiap hari jam 8 pagi
        $schedule->command('peminjaman:kirim-pengingat')->dailyAt('08:00');

        // Opsional: Kirim pengingat tambahan jam 2 siang untuk yang terlambat
        // $schedule->command('peminjaman:kirim-pengingat')->dailyAt('14:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
