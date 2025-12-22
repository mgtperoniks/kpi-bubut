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
    // 16:30 → Generate KPI harian (H-1)
    $schedule->command('kpi:generate-daily')
        ->dailyAt('16:30')
        ->withoutOverlapping();

    // 16:40 → Export CSV KPI
    $schedule->command('kpi:export-csv')
        ->dailyAt('16:40')
        ->withoutOverlapping();

    // Auto cek
    $schedule->command('kpi:auto-export')
    ->everyThirtyMinutes()
    ->withoutOverlapping();

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
