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
        'status',
    ];
}
