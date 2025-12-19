<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionLog;
use App\Services\KpiCalculatorService;

class ProductionController extends Controller
{
    /**
     * Form input produksi
     */
    public function create()
    {
        return view('production.input');
    }

    /**
     * Simpan data produksi
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'production_date' => 'required|date',
            'operator'        => 'required|string|max:50',
            'machine'         => 'required|string|max:50',
            'item'            => 'required|string|max:100',
            'time_start'      => 'required',
            'time_end'        => 'required',
            'actual_qty'      => 'required|integer|min:0',
        ]);

        // Hitung jam kerja menggunakan service
        $workHours = KpiCalculatorService::workHours(
            $request->time_start,
            $request->time_end
        );

        // Sementara: cycle time default (detik)
        $cycleTimeSec = 600; // 10 menit

        // Hitung target quantity
        $targetQty = KpiCalculatorService::targetQty(
            $workHours,
            $cycleTimeSec
        );

        $actualQty = (int) $request->actual_qty;

        // Hitung achievement (%)
        $achievement = KpiCalculatorService::achievement(
            $actualQty,
            $targetQty
        );

        // Simpan ke database
        ProductionLog::create([
            'production_date'       => $request->production_date,
            'shift'                 => $request->shift ?? 'A',
            'operator_code'         => $request->operator,
            'machine_code'          => $request->machine,
            'item_code'             => $request->item,
            'time_start'            => $request->time_start,
            'time_end'              => $request->time_end,
            'work_hours'            => $workHours,
            'cycle_time_used_sec'   => $cycleTimeSec,
            'target_qty'            => $targetQty,
            'actual_qty'            => $actualQty,
            'achievement_percent'   => $achievement,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data produksi berhasil disimpan');
    }
}
