<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdOperatorMirror extends Model
{
    protected $table = 'md_operators_mirror';

    protected $fillable = [
        'code',
        'name',
        'department_code',
        'employment_seq',
        'status',
        'source_updated_at',
        'last_sync_at',
    ];

    public $timestamps = false;
}
