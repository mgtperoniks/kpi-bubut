<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdItemMirror extends Model
{
    protected $table = 'md_items_mirror';

    protected $fillable = [
        'code',
        'name',
        'department_code',
        'cycle_time_sec',
        'status',
        'source_updated_at',
        'last_sync_at',
    ];
}
