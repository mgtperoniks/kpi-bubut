<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionLog;
use App\Models\OperatorSnapshot;
use App\Models\MachineSnapshot;
use App\Models\ItemSnapshot;
use App\Services\KpiCalculatorService;

class ProductionController extends Controller
{
    /**
     * Form input produksi
     * Autofill dari master snapshot
     */
    public function create()
    {
        return view('production.input', [
            'operators' => OperatorSnapshot::where('status', 'ACTIVE')->get(),
            'machines'  => MachineSnapshot::all(),
            'items'     => ItemSnapshot::all(),
        ]);
    }

    /**
     * Simpan data produksi
     * Hardening input + snapshot nilai kritis
     */
    public function store(Request $request)
    {
        /**
         * 1️⃣ VALIDASI DASAR (WAJIB)
         */
        $validated = $request->validate([
            'production_date' => 'required|date',
            'shift'           => 'nullable|string|max:10',
            'operator'        => 'required|string',
            'machine'         => 'required|string',
            'item'            => 'required|string',
            'time_start'      => 'required',
            'time_end'        => 'required',
            'actual_qty'      => 'required|integer|min:0',
        ]);

        /**
         * 1️⃣a VALIDASI JAM (ANTI JAM MUNDUR)
         * Penting secara bisnis
         */
        if (strtotime($validated['time_end']) <= strtotime($validated['time_start'])) {
            return back()
                ->withErrors(['time_end' => 'Jam selesai harus lebih besar dari jam mulai'])
                ->withInput();
        }

        /**
         * 2️⃣ NORMALISASI MINIMAL
         * Tanpa master data pun tetap disiplin
         */
        $operatorCode = strtolower(trim($validated['operator']));
        $machineCode  = strtolower(trim($validated['machine']));
        $itemCode     = strtolower(trim($validated['item']));

        /**
         * 3️⃣ HITUNG JAM KERJA
         */
        $workHours = KpiCalculatorService::workHours(
            $validated['time_start'],
            $validated['time_end']
        );

        /**
         * 4️⃣ SNAPSHOT CYCLE TIME SAAT TRANSAKSI
         * (nilai historis tidak boleh berubah)
         */
        $cycleTimeSec = ItemSnapshot::where('item_code', $itemCode)
            ->value('cycle_time_sec');

        if (!$cycleTimeSec) {
            return back()
                ->withErrors(['item' => 'Cycle time item tidak ditemukan'])
                ->withInput();
        }

        /**
         * 5️⃣ HITUNG TARGET & ACHIEVEMENT
         */
        $targetQty = KpiCalculatorService::targetQty(
            $workHours,
            $cycleTimeSec
        );

        $actualQty = (int) $validated['actual_qty'];

        $achievement = KpiCalculatorService::achievement(
            $actualQty,
            $targetQty
        );

        /**
         * 6️⃣ SIMPAN KE FACT TABLE
         */
        ProductionLog::create([
            'production_date'     => $validated['production_date'],
            'shift'               => $validated['shift'] ?? 'A',

            // Normalized snapshot reference
            'operator_code'       => $operatorCode,
            'machine_code'        => $machineCode,
            'item_code'           => $itemCode,

            'time_start'          => $validated['time_start'],
            'time_end'            => $validated['time_end'],
            'work_hours'          => $workHours,

            // Snapshot nilai kritis
            'cycle_time_used_sec' => $cycleTimeSec,

            'target_qty'          => $targetQty,
            'actual_qty'          => $actualQty,
            'achievement_percent' => $achievement,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data produksi berhasil disimpan');
    }
}
