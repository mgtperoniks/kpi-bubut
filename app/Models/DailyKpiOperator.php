<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyKpiOperator extends Model
{
    protected $table = 'daily_kpi_operator';

    protected $fillable = [
        'kpi_date',
        'operator_code',
        'total_work_hours',
        'total_target_qty',
        'total_actual_qty',
        'kpi_percent',
    ];
}
