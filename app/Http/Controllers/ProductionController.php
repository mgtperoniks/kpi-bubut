<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ProductionLog;

// MASTER MIRROR (READ ONLY)
use App\Models\MdOperator;
use App\Models\MdMachine;
use App\Models\MdItem;

class ProductionController extends Controller
{
    /**
     * =================================
     * FORM INPUT PRODUKSI
     * =================================
     */
    public function create()
    {
        return view('production.input', [
            'operators' => MdOperator::active()->orderBy('name')->get(),
            'machines'  => MdMachine::active()->orderBy('name')->get(),
            'items'     => MdItem::active()
                ->orderBy('code')
                ->get(['code', 'name', 'cycle_time_sec']),
        ]);
    }

    /**
     * =================================
     * SIMPAN DATA PRODUKSI (HARD STOP)
     * =================================
     */
    public function store(Request $request)
    {
        /**
         * 1. VALIDASI INPUT DASAR
         * Server adalah source of truth
         */
        $validated = $request->validate([
            'production_date' => 'required|date',
            'shift'           => 'required|string|max:10',

            'operator_code'   => 'required|string',
            'machine_code'    => 'required|string',
            'item_code'       => 'required|string',

            'time_start'      => 'required|date_format:H:i',
            'time_end'        => 'required|date_format:H:i|after:time_start',

            'actual_qty'      => 'required|integer|min:0',
        ]);

        /**
         * 2. LOAD MASTER DATA (FAIL FAST)
         * SSOT DEFENSIVE LAYER
         */
        $item = MdItem::where('code', $validated['item_code'])->firstOrFail();
        $machine = MdMachine::where('code', $validated['machine_code'])->firstOrFail();
        $operator = MdOperator::where('code', $validated['operator_code'])->firstOrFail();

        /**
         * 3. HARD STOP — MASTERDATA GUARD
         * KPI TIDAK BOLEH TERCEMAR
         */
        if ($item->status !== 'active') {
            throw ValidationException::withMessages([
                'item_code' => 'Item inactive tidak boleh digunakan dalam produksi.',
            ]);
        }

        if ($machine->status !== 'active') {
            throw ValidationException::withMessages([
                'machine_code' => 'Machine inactive tidak boleh digunakan dalam produksi.',
            ]);
        }

        if ($operator->status !== 'active') {
            throw ValidationException::withMessages([
                'operator_code' => 'Operator inactive tidak boleh digunakan dalam produksi.',
            ]);
        }

        /**
         * 4. HITUNG DURASI KERJA
         */
        $workSeconds = strtotime($validated['time_end'])
            - strtotime($validated['time_start']);

        if ($workSeconds <= 0) {
            return back()
                ->withErrors(['time_end' => 'Jam selesai harus lebih besar dari jam mulai'])
                ->withInput();
        }

        $workHours = round($workSeconds / 3600, 2);

        /**
         * 5. HITUNG TARGET PRODUKSI
         * Snapshot cycle time dari MASTER
         */
        $cycleTimeSec = (int) $item->cycle_time_sec;

        if ($cycleTimeSec <= 0) {
            throw ValidationException::withMessages([
                'item_code' => 'Cycle time item tidak valid.',
            ]);
        }

        $targetQty = intdiv($workSeconds, $cycleTimeSec);

        /**
         * 6. HITUNG ACHIEVEMENT
         */
        $actualQty = (int) $validated['actual_qty'];

        $achievementPercent = $targetQty > 0
            ? round(($actualQty / $targetQty) * 100, 2)
            : 0;

        /**
         * 7. SIMPAN KE FACT TABLE (SNAPSHOT)
         * NO FK — KPI IMMUTABLE
         */
        ProductionLog::create([
            'production_date'     => $validated['production_date'],
            'shift'               => $validated['shift'],

            'operator_code'       => $this->normalizeCode($validated['operator_code']),
            'machine_code'        => $this->normalizeCode($validated['machine_code']),
            'item_code'           => $this->normalizeCode($validated['item_code']),

            'time_start'          => $validated['time_start'],
            'time_end'            => $validated['time_end'],
            'work_hours'          => $workHours,

            // SNAPSHOT NILAI KRITIS
            'cycle_time_used_sec' => $cycleTimeSec,
            'target_qty'          => $targetQty,
            'actual_qty'          => $actualQty,
            'achievement_percent' => $achievementPercent,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data produksi berhasil disimpan');
    }

    /**
     * =================================
     * HELPER NORMALISASI KODE
     * =================================
     */
    private function normalizeCode(string $value): string
    {
        return strtolower(trim($value));
    }
}
