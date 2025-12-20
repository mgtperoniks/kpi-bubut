<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorSnapshot extends Model {
    protected $table = 'operators_snapshot';
    protected $fillable = ['operator_code','operator_name','status','synced_at'];
}
