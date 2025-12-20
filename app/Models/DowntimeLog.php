<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DowntimeLog extends Model
{
    protected $table = 'downtime_logs';

    protected $fillable = [
        'downtime_date',
        'operator_code',
        'machine_code',
        'duration_minutes',
        'note',
    ];
}
