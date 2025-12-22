<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionLog;

// MASTER MIRROR (READ-ONLY)
use App\Models\MdOperator;
use App\Models\MdMachine;
use App\Models\MdItem;

class ProductionController extends Controller
{
    /**
     * Form input produksi
     * Autofill dari master data mirror (md_*)
     */
    public function create()
    {
        return view('production.input', [
            'operators' => MdOperator::where('active', 1)
                ->orderBy('name')
                ->get(),

            'machines' => MdMachine::where('active', 1)
                ->orderBy('name')
                ->get(),

            'items' => MdItem::where('active', 1)
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Simpan data produksi
     * Semua perhitungan KRITIS dilakukan di server
     */
    public function store(Request $request)
    {
        /**
         * 1️⃣ VALIDASI WAJIB (SERVER AUTHORITY)
         */
        $validated = $request->validate([
            'production_date' => 'required|date',
            'shift'           => 'required|string|max:10',

            'operator_code'   => 'required|string',
            'machine_code'    => 'required|string',
            'item_code'       => 'required|string',

            'time_start'      => 'required',
            'time_end'        => 'required|after:time_start',

            'actual_qty'      => 'required|numeric|min:0',
        ]);

        /**
         * 2️⃣ AMBIL MASTER ITEM (FAIL FAST)
         * Master mirror = single source of truth
         */
        $item = MdItem::where('code', $validated['item_code'])
            ->where('active', 1)
            ->firstOrFail();

        /**
         * 3️⃣ HITUNG JAM KERJA (JAM DESIMAL)
         * Asumsi format 24 jam (disepakati)
         */
        $workSeconds = strtotime($validated['time_end'])
            - strtotime($validated['time_start']);

        $workHours = $workSeconds / 3600;

        /**
         * 4️⃣ HITUNG TARGET BERDASARKAN CYCLE TIME
         * FULL SERVER-SIDE
         */
        $cycleTimeSec = (int) $item->cycle_time_sec;

        $targetQty = $cycleTimeSec > 0
            ? floor($workSeconds / $cycleTimeSec)
            : 0;

        /**
         * 5️⃣ HITUNG ACHIEVEMENT
         */
        $actualQty = (int) $validated['actual_qty'];

        $achievement = $targetQty > 0
            ? round(($actualQty / $targetQty) * 100, 2)
            : 0;

        /**
         * 6️⃣ SIMPAN KE FACT TABLE (HISTORICAL SAFE)
         */
        ProductionLog::create([
            'production_date'     => $validated['production_date'],
            'shift'               => $validated['shift'],

            // Snapshot kode (NO FK)
            'operator_code'       => strtolower(trim($validated['operator_code'])),
            'machine_code'        => strtolower(trim($validated['machine_code'])),
            'item_code'           => strtolower(trim($validated['item_code'])),

            'time_start'          => $validated['time_start'],
            'time_end'            => $validated['time_end'],
            'work_hours'          => $workHours,

            // Snapshot nilai kritis (WAJIB)
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
