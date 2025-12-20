<?php

namespace App\Http\Controllers;

use App\Models\DowntimeLog;
use Illuminate\Support\Facades\DB;

class TrackingDowntimeController extends Controller
{
    public function index()
    {
        $list = DowntimeLog::orderBy('downtime_date', 'desc')
            ->orderBy('machine_code')
            ->get();

        $summary = DowntimeLog::select(
                'downtime_date',
                'machine_code',
                DB::raw('SUM(duration_minutes) as total_minutes')
            )
            ->groupBy('downtime_date', 'machine_code')
            ->orderBy('downtime_date', 'desc')
            ->get();

        return view('downtime.index', compact('list', 'summary'));
    }
}
