<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiExport extends Model
{
    protected $table = 'kpi_exports';

    protected $fillable = [
        'export_date',
        'status',
    ];
}

