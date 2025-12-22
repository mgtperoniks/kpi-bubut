<?php

namespace App\Exports;

use App\Models\DailyKpiMachine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MachineKpiExport implements FromCollection, WithHeadings
{
    protected string $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return DailyKpiMachine::where('kpi_date', $this->date)
            ->select(
                'machine_code',
                'total_work_hours',
                'total_target_qty',
                'total_actual_qty',
                'kpi_percent'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Machine',
            'Jam Jalan',
            'Target',
            'Aktual',
            'KPI (%)',
        ];
    }
}

