<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectLog extends Model
{
    protected $fillable = [
        'reject_date',
        'operator_code',
        'machine_code',
        'item_code',
        'reject_qty',
        'reject_reason',
        'note',
    ];
}
