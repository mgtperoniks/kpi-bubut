<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

// MASTER MIRROR (READ ONLY)
use App\Models\MdOperator;
use App\Models\MdMachine;

class DowntimeController extends Controller
{
    /**
     * ===============================
     * FORM INPUT DOWNTIME
     * ===============================
     */
    public function create()
    {
        return view('downtime.input', [
            'operators' => MdOperator::active()
                ->orderBy('code')
                ->get(),

            'machines'  => MdMachine::active()
                ->orderBy('code')
                ->get(),
        ]);
    }

    /**
     * ===============================
     * SIMPAN DOWNTIME (HARD STOP)
     * ===============================
     */
    public function store(Request $request)
    {
        /**
         * 1. VALIDASI INPUT DASAR
         * Server adalah source of truth
         */
        $validated = $request->validate([
            'downtime_date'     => 'required|date',
            'operator_code'     => 'required|string',
            'machine_code'      => 'required|string',
            'duration_minutes'  => 'required|integer|min:1',
            'note'              => 'nullable|string|max:255',
        ]);

        /**
         * 2. LOAD MASTER DATA (FAIL FAST)
         * SSOT DEFENSIVE LAYER
         */
        $operator = MdOperator::where('code', $validated['operator_code'])->firstOrFail();
        $machine  = MdMachine::where('code', $validated['machine_code'])->firstOrFail();

        /**
         * 3. HARD STOP — MASTERDATA GUARD
         * KPI TIDAK BOLEH TERCEMAR
         */
        if ($operator->status !== 'active') {
            throw ValidationException::withMessages([
                'operator_code' => 'Operator inactive tidak boleh digunakan dalam downtime.',
            ]);
        }

        if ($machine->status !== 'active') {
            throw ValidationException::withMessages([
                'machine_code' => 'Machine inactive tidak boleh digunakan dalam downtime.',
            ]);
        }

        /**
         * 4. SIMPAN KE FACT TABLE
         * Snapshot (NO FK)
         */
        DB::table('downtime_logs')->insert([
            'downtime_date'    => $validated['downtime_date'],
            'operator_code'    => $this->normalizeCode($validated['operator_code']),
            'machine_code'     => $this->normalizeCode($validated['machine_code']),
            'duration_minutes' => $validated['duration_minutes'],
            'note'             => $validated['note'],
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()
            ->route('downtime.input')
            ->with('success', 'Downtime berhasil disimpan');
    }

    /**
     * ===============================
     * HELPER NORMALISASI KODE
     * ===============================
     */
    private function normalizeCode(string $value): string
    {
        return strtolower(trim($value));
    }
}
