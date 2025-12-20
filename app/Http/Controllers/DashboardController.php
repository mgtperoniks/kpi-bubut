<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiOperator;
use App\Models\DailyKpiMachine;
use App\Models\ProductionLog;
use App\Models\DowntimeLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tanggal terakhir yang ada KPI operator
        $date = DailyKpiOperator::max('kpi_date');

        if (!$date) {
            return view('dashboard', ['empty' => true]);
        }

        $avgKpiOperator = DailyKpiOperator::where('kpi_date', $date)
            ->avg('kpi_percent');

        $avgKpiMachine = DailyKpiMachine::where('kpi_date', $date)
            ->avg('kpi_percent');

        $totalOutput = ProductionLog::where('production_date', $date)
            ->sum('actual_qty');

        $totalDowntime = DowntimeLog::where('downtime_date', $date)
            ->sum('duration_minutes');

        $activeOperators = DailyKpiOperator::where('kpi_date', $date)->count();
        $activeMachines  = DailyKpiMachine::where('kpi_date', $date)->count();

        return view('dashboard', compact(
            'date',
            'avgKpiOperator',
            'avgKpiMachine',
            'totalOutput',
            'totalDowntime',
            'activeOperators',
            'activeMachines'
        ));
    }
}
