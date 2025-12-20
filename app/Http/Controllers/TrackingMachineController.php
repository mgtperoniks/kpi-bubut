<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiMachine;
use App\Models\ProductionLog;

class TrackingMachineController extends Controller
{
    /**
     * List KPI harian mesin
     */
    public function index()
    {
        $data = DailyKpiMachine::orderBy('kpi_date', 'desc')
            ->orderBy('machine_code')
            ->get();

        return view('tracking.machine.index', compact('data'));
    }

    /**
     * Detail KPI mesin per tanggal
     */
    public function show(string $machine, string $date)
    {
        $summary = DailyKpiMachine::where('machine_code', $machine)
            ->where('kpi_date', $date)
            ->firstOrFail();

        $activities = ProductionLog::where('machine_code', $machine)
            ->where('production_date', $date)
            ->orderBy('time_start')
            ->get();

        return view('tracking.machine.show', compact('summary', 'activities'));
    }
}
