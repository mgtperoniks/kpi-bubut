<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiOperator;
use App\Models\ProductionLog;

// MASTER MIRROR (READ ONLY - SSOT)
use App\Models\MdOperatorMirror;
use Barryvdh\DomPDF\Facade\Pdf;

class TrackingOperatorController extends Controller
{
    /**
     * ===============================
     * LIST KPI HARIAN OPERATOR
     * ===============================
     */
    public function index()
    {
        /**
         * Ambil tanggal dari request
         * fallback ke tanggal KPI terbaru
         */
        $latestDate = DailyKpiOperator::max('kpi_date');
        $date = request('date') ?? $latestDate ?? date('Y-m-d');


        /**
         * Data KPI operator untuk tanggal tersebut
         */
        $rows = DailyKpiOperator::where('kpi_date', $date)
            ->orderBy('operator_code')
            ->get();

        /**
         * Mapping kode operator -> nama operator
         * Mirror master (READ ONLY)
         */
        $operatorNames = MdOperatorMirror::pluck('name', 'code');

        /**
         * Mapping kode operator -> shift (ambil dari logs)
         */
        $shifts = ProductionLog::where('production_date', $date)
            ->select('operator_code', 'shift')
            ->distinct()
            ->get()
            ->groupBy('operator_code')
            ->map(function ($items) {
                return $items->pluck('shift')->implode(', ');
            });

        return view('tracking.operator.index', [
            'rows' => $rows,
            'operatorNames' => $operatorNames,
            'shifts' => $shifts,
            'date' => $date,
        ]);
    }

    /**
     * ===============================
     * DETAIL KPI OPERATOR PER TANGGAL
     * ===============================
     */
    public function show(string $operatorCode, string $date)
    {
        /**
         * Summary KPI (IMMUTABLE FACT)
         */
        $summary = DailyKpiOperator::with('operator')
            ->where('operator_code', $operatorCode)
            ->where('kpi_date', $date)
            ->firstOrFail();

        /**
         * Detail aktivitas produksi (FACT LOG)
         */
        $activities = ProductionLog::with(['machine', 'item'])
            ->where('operator_code', $operatorCode)
            ->where('production_date', $date)
            ->orderBy('time_start')
            ->get();

        return view('tracking.operator.show', [
            'summary' => $summary,
            'activities' => $activities,
        ]);
    }
    /**
     * ===============================
     * EXPORT PDF
     * ===============================
     */
    public function exportPdf(string $date)
    {
        $rows = ProductionLog::with(['machine', 'item'])
            ->where('production_date', $date)
            ->orderBy('shift')
            ->orderBy('operator_code')
            ->get();

        $operatorNames = MdOperatorMirror::pluck('name', 'code');

        $pdf = Pdf::loadView('tracking.operator.pdf', [
            'rows' => $rows,
            'operatorNames' => $operatorNames,
            'date' => $date,
        ]);

        $pdf->setPaper('A4', 'landscape'); // Landscape for better width

        return $pdf->download('KPI-Harian-' . $date . '.pdf');
    }
}
