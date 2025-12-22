<?php

namespace App\Exports;

use App\Models\DailyKpiOperator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OperatorKpiExport implements FromCollection, WithHeadings
{
    protected string $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return DailyKpiOperator::where('kpi_date', $this->date)
            ->select(
                'operator_code',
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
            'Operator',
            'Jam Kerja',
            'Target',
            'Aktual',
            'KPI (%)',
        ];
    }
}

