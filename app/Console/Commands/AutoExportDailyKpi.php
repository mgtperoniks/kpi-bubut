<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyKpiOperator;
use App\Models\KpiExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OperatorKpiExport;
use App\Exports\MachineKpiExport;
use App\Exports\DowntimeExport;
use App\Services\DailyKpiService;

class AutoExportDailyKpi extends Command
{
    protected $signature = 'kpi:auto-export';

    protected $description = 'Auto export semua KPI yang belum pernah diexport (catch-up / mitigasi scheduler)';

    public function handle(): int
    {
        // 1️⃣ Ambil semua tanggal KPI yang ADA
        $dates = DailyKpiOperator::select('kpi_date')
            ->distinct()
            ->orderBy('kpi_date')
            ->pluck('kpi_date');

        if ($dates->isEmpty()) {
            $this->info('Tidak ada KPI untuk diexport');
            return Command::SUCCESS;
        }

        // 2️⃣ Loop tanggal, export yang BELUM pernah diexport
        foreach ($dates as $date) {

            $alreadyExported = KpiExport::where('export_date', $date)->exists();
            if ($alreadyExported) {
                continue;
            }

            // 3️⃣ Generate ulang KPI (AMAN & IDEMPOTENT)
            DailyKpiService::generateOperatorDaily($date);
            DailyKpiService::generateMachineDaily($date);

            // 4️⃣ Siapkan folder export
            $dir = "exports/{$date}";
            Storage::makeDirectory($dir);

            // 5️⃣ Export file Excel
            Excel::store(
                new OperatorKpiExport($date),
                "{$dir}/kpi_operator_{$date}.xlsx"
            );

            Excel::store(
                new MachineKpiExport($date),
                "{$dir}/kpi_machine_{$date}.xlsx"
            );

            Excel::store(
                new DowntimeExport($date),
                "{$dir}/downtime_{$date}.xlsx"
            );

            // 6️⃣ Tandai tanggal ini sudah diexport
            KpiExport::create([
                'export_date' => $date,
                'status'      => 'DONE',
            ]);

            $this->info("Export selesai untuk {$date}");
        }

        return Command::SUCCESS;
    }
}
