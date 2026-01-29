<?php

namespace App\Http\Controllers;

use App\Models\MdMachineMirror;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DATE & SCOPE
        |--------------------------------------------------------------------------
        */
        // Use latest KPI date or yesterday as fallback
        $date = \App\Models\DailyKpiOperator::max('kpi_date')
            ?? \Carbon\Carbon::yesterday()->format('Y-m-d');

        $prevDate = \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d');

        /*
        |--------------------------------------------------------------------------
        | 1. CARD STATS (Daily Aggregate)
        |--------------------------------------------------------------------------
        */
        $dailyStats = \App\Models\DailyKpiOperator::where('kpi_date', $date)
            ->selectRaw('
                COALESCE(SUM(total_target_qty), 0) as total_target,
                COALESCE(SUM(total_actual_qty), 0) as total_actual
            ')
            ->first();

        // Calculate Efficiency safely
        $efficiency = $dailyStats->total_target > 0
            ? ($dailyStats->total_actual / $dailyStats->total_target) * 100
            : 0;

        // Overall KPI (Average of all operators)
        $overallKpi = \App\Models\DailyKpiOperator::where('kpi_date', $date)
            ->avg('kpi_percent') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 2. CHARTS DATA
        |--------------------------------------------------------------------------
        */

        // A. Weekly Production (Last 7 Days)
        $startDate = \Carbon\Carbon::parse($date)->subDays(6)->format('Y-m-d');

        $weeklyProduction = \App\Models\DailyKpiOperator::selectRaw('kpi_date, SUM(total_actual_qty) as total_actual, SUM(total_target_qty) as total_target')
            ->where('kpi_date', '>=', $startDate)
            ->where('kpi_date', '<=', $date)
            ->groupBy('kpi_date')
            ->orderBy('kpi_date')
            ->get();

        // B. Production by Line (Last 7 Days) - DYNAMIC LINES
        $productionByLine = \App\Models\ProductionLog::selectRaw('production_date, line, SUM(actual_qty) as total_qty')
            ->where('production_date', '>=', $startDate)
            ->where('production_date', '<=', $date)
            ->whereNotNull('line')
            ->groupBy('production_date', 'line')
            ->orderBy('production_date')
            ->get();

        // Transform for Chart.js: [ '2023-01-01' => ['Line 1' => 100, 'Line 2' => 50] ]
        $lineChartData = [];
        $allLines = [];

        foreach ($productionByLine as $record) {
            $d = $record->production_date;
            $l = $record->line;
            $q = (int) $record->total_qty;

            if (!isset($lineChartData[$d])) {
                $lineChartData[$d] = [];
            }
            $lineChartData[$d][$l] = $q;

            if (!in_array($l, $allLines)) {
                $allLines[] = $l;
            }
        }
        sort($allLines); // Ensure consistent order (Line 1, Line 2...)

        // C. Top 3 Reject Reasons (Current Month)
        // Note: RejectLog uses 'reject_date'
        $monthStart = \Carbon\Carbon::parse($date)->startOfMonth()->format('Y-m-d');
        $monthEnd = \Carbon\Carbon::parse($date)->endOfMonth()->format('Y-m-d');

        // Label for View: "1 - 27 Januari 2026"
        $monthLabel = \Carbon\Carbon::parse($monthStart)->format('j') . ' - ' .
            \Carbon\Carbon::parse($date)->translatedFormat('j F Y');

        $rejectAnalysis = \App\Models\RejectLog::selectRaw('reject_reason, SUM(reject_qty) as total_qty')
            ->whereBetween('reject_date', [$monthStart, $monthEnd])
            ->groupBy('reject_reason')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // D. Top 3 Operators (Monthly Average)
        $topOperators = \App\Models\DailyKpiOperator::selectRaw('operator_code, AVG(kpi_percent) as kpi_percent')
            ->whereBetween('kpi_date', [$monthStart, $monthEnd]) // Current Month Scope
            ->groupBy('operator_code')
            ->orderByDesc('kpi_percent')
            ->limit(3)
            ->get();

        // Map Operator Names found in Mirror
        $operatorCodes = $topOperators->pluck('operator_code');
        // Merge with Low performing codes later or query separate?
        // Query separate to ensure we have names for both lists

        // E. Low Performing Operators (Monthly Average < 90%)
        $lowOperators = \App\Models\DailyKpiOperator::selectRaw('operator_code, AVG(kpi_percent) as kpi_percent')
            ->whereBetween('kpi_date', [$monthStart, $monthEnd])
            ->groupBy('operator_code')
            ->having('kpi_percent', '<', 90)
            ->orderBy('kpi_percent') // Lowest first
            ->limit(3)
            ->get();

        // Combine codes to fetch names in one go
        $allOpCodes = $operatorCodes->merge($lowOperators->pluck('operator_code'))->unique();

        $operatorNames = \App\Models\MdOperatorMirror::whereIn('code', $allOpCodes)
            ->pluck('name', 'code');

        /*
        |--------------------------------------------------------------------------
        | MACHINE STATUS (ACTIVE ONLY)
        |--------------------------------------------------------------------------
        */
        // Existing Logic Preserved
        $machines = MdMachineMirror::where('status', 'active')
            ->orderBy('department_code')
            ->orderBy('code')
            ->get();

        $machineSummary = [
            'ONLINE' => MdMachineMirror::where('status', 'active')->where('runtime_status', 'ONLINE')->count(),
            'STALE' => MdMachineMirror::where('status', 'active')->where('runtime_status', 'STALE')->count(),
            'OFFLINE' => MdMachineMirror::where('status', 'active')->where('runtime_status', 'OFFLINE')->count(),
        ];

        return view('dashboard.index', compact(
            'date',
            'dailyStats',
            'efficiency',
            'overallKpi',
            'weeklyProduction',
            'lineChartData', // New passed variable
            'allLines',      // New passed variable
            'monthLabel',    // New: Date Range Context
            'rejectAnalysis',
            'topOperators',
            'lowOperators',
            'operatorNames',
            'machines',
            'machineSummary'
        ));
    }
}
