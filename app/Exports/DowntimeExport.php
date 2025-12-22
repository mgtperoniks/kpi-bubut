<?php

namespace App\Exports;

use App\Models\DowntimeLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DowntimeExport implements FromCollection, WithHeadings
{
    protected string $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return DowntimeLog::whereDate('downtime_date', $this->date)
            ->select(
                'downtime_date',
                'machine_code',
                'operator_code',
                'duration_minutes',
                'note'
            )
            ->orderBy('machine_code')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Machine',
            'Operator',
            'Durasi (Menit)',
            'Keterangan',
        ];
    }
}
