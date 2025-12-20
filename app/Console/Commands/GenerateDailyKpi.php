<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DailyKpiService;
use Carbon\Carbon;

class GenerateDailyKpi extends Command
{
    protected $signature = 'kpi:generate-daily {date?}';
    protected $description = 'Generate KPI harian operator';

    public function handle()
    {
        $date = $this->argument('date')
            ?? Carbon::yesterday()->toDateString();

        DailyKpiService::generateOperatorDaily($date);
         DailyKpiService::generateMachineDaily($date);

        $this->info("KPI harian operator generated for {$date}");
    }
}
