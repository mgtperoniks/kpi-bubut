<?php

namespace App\Http\Controllers;

use App\Models\DailyKpiMachine;
use App\Models\ProductionLog;

// MASTER MIRROR (READ ONLY - SSOT)
use App\Models\MdMachineMirror;
use Barryvdh\DomPDF\Facade\Pdf;

class TrackingMachineController extends Controller
{
    /**
     * ===============================
     * LIST KPI HARIAN MESIN
     * ===============================
     */
    public function index()
    {
        /**
         * Ambil tanggal dari request
         * fallback ke tanggal KPI terbaru
         */
        $latestDate = DailyKpiMachine::max('kpi_date');
        $date = request('date') ?? $latestDate ?? date('Y-m-d');


        /**
         * Data KPI mesin untuk tanggal tersebut
         * (SUMBER RESMI KPI)
         */
        $rows = DailyKpiMachine::where('kpi_date', $date)
            ->orderBy('machine_code')
            ->get();

        /**
         * Mapping kode mesin -> nama mesin
         * Mirror master (READ ONLY)
         */
        $machineNames = MdMachineMirror::pluck('name', 'code');

        /**
         * Mapping kode mesin -> shift
         */
        $shifts = ProductionLog::where('production_date', $date)
            ->select('machine_code', 'shift')
            ->distinct()
            ->get()
            ->groupBy('machine_code')
            ->map(function ($items) {
                return $items->pluck('shift')->implode(', ');
            });

        return view('tracking.machine.index', [
            'rows' => $rows,
            'machineNames' => $machineNames,
            'shifts' => $shifts,
            'date' => $date,
        ]);
    }

    /**
     * ===============================
     * DETAIL KPI MESIN PER TANGGAL
     * ===============================
     */
    public function show(string $machineCode, string $date)
    {
        /**
         * Summary KPI mesin (IMMUTABLE FACT)
         */
        $summary = DailyKpiMachine::with('machine')
            ->where('machine_code', $machineCode)
            ->where('kpi_date', $date)
            ->firstOrFail();

        /**
         * Detail aktivitas produksi (FACT LOG)
         */
        $activities = ProductionLog::with(['operator', 'item'])
            ->where('machine_code', $machineCode)
            ->where('production_date', $date)
            ->orderBy('time_start')
            ->get();

        return view('tracking.machine.show', [
            'summary' => $summary,
            'activities' => $activities,
            'machine' => $machineCode,
            'date' => $date,
        ]);
    }
    /**
     * ===============================
     * EXPORT PDF
     * ===============================
     */
    public function exportPdf(string $date)
    {
        $rows = ProductionLog::with(['operator', 'item'])
            ->where('production_date', $date)
            ->orderBy('machine_code')
            ->orderBy('shift')
            ->get();

        $machineNames = MdMachineMirror::pluck('name', 'code');

        $pdf = Pdf::loadView('tracking.machine.pdf', [
            'rows' => $rows,
            'machineNames' => $machineNames,
            'date' => $date,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('KPI-Mesin-' . $date . '.pdf');
    }
}
