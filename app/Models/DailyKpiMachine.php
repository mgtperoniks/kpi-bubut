<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyKpiMachine extends Model
{
    protected $table = 'daily_kpi_machine';

    protected $fillable = [
        'kpi_date',
        'machine_code',
        'total_work_hours',
        'total_target_qty',
        'total_actual_qty',
        'kpi_percent',
    ];
}
