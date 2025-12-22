<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiOperator;
use App\Models\ProductionLog;

// MASTER MIRROR
use App\Models\MdOperator;

class TrackingOperatorController extends Controller
{
    /**
     * List KPI harian operator (by date)
     */
    public function index()
    {
        /**
         * Ambil tanggal dari request
         * fallback ke tanggal KPI terbaru
         */
        $date = request('date') ?? DailyKpiOperator::max('kpi_date');

        if (!$date) {
            return back()->with('error', 'Tanggal KPI tidak ditemukan');
        }

        /**
         * Data KPI operator untuk tanggal tersebut
         */
        $rows = DailyKpiOperator::where('kpi_date', $date)
            ->orderBy('operator_code')
            ->get();

        /**
         * Mapping kode operator -> nama operator
         * (mirror master, read-only)
         */
        $operatorNames = MdOperator::pluck('name', 'code');

        return view('tracking.operator.index', [
            'rows'          => $rows,
            'operatorNames' => $operatorNames,
            'date'          => $date,
        ]);
    }

    /**
     * Detail KPI operator per tanggal
     */
    public function show(string $operatorCode, string $date)
    {
        /**
         * Summary KPI
         */
        $summary = DailyKpiOperator::where('operator_code', $operatorCode)
            ->where('kpi_date', $date)
            ->firstOrFail();

        /**
         * Detail aktivitas produksi
         */
        $activities = ProductionLog::where('operator_code', $operatorCode)
            ->where('production_date', $date)
            ->orderBy('time_start')
            ->get();

        return view('tracking.operator.show', [
            'summary'  => $summary,
            'activities' => $activities,
        ]);
    }
}
