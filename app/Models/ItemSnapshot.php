<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSnapshot extends Model {
    protected $table = 'items_snapshot';
    protected $fillable = ['item_code','item_name','cycle_time_sec','synced_at'];
}
