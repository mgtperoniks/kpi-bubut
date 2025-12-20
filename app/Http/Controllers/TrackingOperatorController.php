<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiOperator;
use App\Models\ProductionLog;
use Illuminate\Http\Request;

class TrackingOperatorController extends Controller
{
    /**
     * List KPI harian operator
     */
    public function index()
    {
        $data = DailyKpiOperator::orderBy('kpi_date', 'desc')
            ->orderBy('operator_code')
            ->get();

        return view('tracking.operator.index', compact('data'));
    }

    /**
     * Detail KPI operator per tanggal
     */
    public function show(string $operator, string $date)
    {
        $summary = DailyKpiOperator::where('operator_code', $operator)
            ->where('kpi_date', $date)
            ->firstOrFail();

        $activities = ProductionLog::where('operator_code', $operator)
            ->where('production_date', $date)
            ->orderBy('time_start')
            ->get();

        return view('tracking.operator.show', compact('summary', 'activities'));
    }
}