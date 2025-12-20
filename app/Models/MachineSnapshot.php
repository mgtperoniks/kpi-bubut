<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineSnapshot extends Model {
    protected $table = 'machines_snapshot';
    protected $fillable = ['machine_code','machine_name','line','synced_at'];
}

