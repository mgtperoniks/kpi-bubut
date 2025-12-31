<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register Artisan custom commands.
     */
    protected $commands = [
        \App\Console\Commands\PullMasterItems::class,
        \App\Console\Commands\PullMasterOperators::class,
        \App\Console\Commands\PullMasterMachines::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // =========================
        // KPI DAILY JOBS
        // =========================

        // 16:30 - Generate KPI harian (H-1)
        $schedule->command('kpi:generate-daily')
            ->dailyAt('16:30')
            ->withoutOverlapping();

        // 16:40 - Export CSV KPI
        $schedule->command('kpi:export-csv')
            ->dailyAt('16:40')
            ->withoutOverlapping();

        // Auto export KPI setiap 30 menit
        $schedule->command('kpi:auto-export')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // =========================
        // MASTER DATA SYNC
        // =========================

        // Pull master items setiap 10 menit
        $schedule->command('pull:master-items')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // Pull master operators setiap 10 menit
        $schedule->command('pull:master-operators')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // Pull master machines setiap 5 menit
        $schedule->command('pull:master-machines')
            ->everyFiveMinutes()
            ->withoutOverlapping();
    }

    /**
     * Register commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
