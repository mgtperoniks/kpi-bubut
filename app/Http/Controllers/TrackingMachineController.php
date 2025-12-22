<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiMachine;
use App\Models\ProductionLog;

class TrackingMachineController extends Controller
{
    /**
     * List KPI harian mesin (by date)
     */
    public function index()
    {
        // Ambil tanggal dari request, fallback ke tanggal KPI terbaru
        $date = request('date')
            ?? DailyKpiMachine::max('kpi_date');
if (!$date) {
        return back()->with('error', 'Tanggal tidak ditemukan');
    }
        // Data KPI mesin untuk tanggal tersebut
        $data = DailyKpiMachine::where('kpi_date', $date)
            ->orderBy('machine_code')
            ->get();

        return view('tracking.machine.index', compact('data', 'date'));
    }

    /**
     * Detail KPI mesin per tanggal
     */
    public function show(string $machine, string $date)
    {
        // Summary KPI
        $summary = DailyKpiMachine::where('machine_code', $machine)
            ->where('kpi_date', $date)
            ->firstOrFail();

        // Detail aktivitas produksi
        $activities = ProductionLog::where('machine_code', $machine)
            ->where('production_date', $date)
            ->orderBy('time_start')
            ->get();

        return view('tracking.machine.show', compact('summary', 'activities'));
    }
}
