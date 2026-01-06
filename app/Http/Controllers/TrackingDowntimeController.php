<?php

namespace App\Http\Controllers;

use App\Models\DowntimeLog;
use Illuminate\Support\Facades\DB;

// MASTER MIRROR (READ ONLY - SSOT)
use App\Models\MdMachineMirror;

class TrackingDowntimeController extends Controller
{
    /**
     * ===============================
     * LIST & SUMMARY DOWNTIME PER TANGGAL
     * ===============================
     */
    public function index()
    {
        /**
         * Ambil tanggal dari request
         * fallback ke tanggal downtime terbaru
         */
        $date = request('date') ?? DowntimeLog::max('downtime_date');

        if (!$date) {
            return back()->with('error', 'Tanggal downtime tidak ditemukan.');
        }

        /**
         * LIST DOWNTIME (DETAIL EVENT)
         * FACT TABLE — READ ONLY
         */
        $list = DowntimeLog::where('downtime_date', $date)
            ->orderBy('machine_code')
            ->orderByDesc('duration_minutes')
            ->get();

        /**
         * SUMMARY DOWNTIME PER MESIN (TOTAL MENIT)
         * AGGREGATE FACT
         */
        $summary = DowntimeLog::where('downtime_date', $date)
            ->select(
                'machine_code',
                DB::raw('SUM(duration_minutes) as total_minutes')
            )
            ->groupBy('machine_code')
            ->orderBy('machine_code')
            ->get();

        /**
         * Mapping kode mesin -> nama mesin
         * Mirror master (READ ONLY)
         */
        $machineNames = MdMachineMirror::pluck('name', 'code');

        return view('downtime.index', [
            'list'         => $list,
            'summary'      => $summary,
            'machineNames' => $machineNames,
            'date'         => $date,
        ]);
    }
}
