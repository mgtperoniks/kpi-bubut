<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\RejectLog;

// MASTER MIRROR (READ ONLY)
use App\Models\MdOperator;
use App\Models\MdMachine;
use App\Models\MdItem;

class RejectController extends Controller
{
    /**
     * ===============================
     * LIST DATA REJECT
     * ===============================
     */
    public function index()
    {
        $rows = RejectLog::orderBy('reject_date', 'desc')->get();

        return view('reject.index', [
            'rows' => $rows,
        ]);
    }

    /**
     * ===============================
     * FORM INPUT REJECT
     * ===============================
     */
    public function create()
    {
        return view('reject.input', [
            'operators' => MdOperator::active()
                ->orderBy('name')
                ->get(),

            'machines' => MdMachine::active()
                ->orderBy('name')
                ->get(),

            'items' => MdItem::active()
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * ===============================
     * SIMPAN DATA REJECT (HARD STOP)
     * ===============================
     */
    public function store(Request $request)
    {
        /**
         * 1. VALIDASI INPUT DASAR
         */
        $validated = $request->validate([
            'reject_date'   => 'required|date',
            'operator_code' => 'required|string',
            'machine_code'  => 'required|string',
            'item_code'     => 'required|string',
            'reject_qty'    => 'required|integer|min:1',
            'reject_reason' => 'required|string|max:255',
            'note'          => 'nullable|string',
        ]);

        /**
         * 2. LOAD MASTER DATA (FAIL FAST)
         * SSOT DEFENSIVE LAYER
         */
        $operator = MdOperator::where('code', $validated['operator_code'])->firstOrFail();
        $machine  = MdMachine::where('code', $validated['machine_code'])->firstOrFail();
        $item     = MdItem::where('code', $validated['item_code'])->firstOrFail();

        /**
         * 3. HARD STOP — MASTERDATA GUARD
         * QUALITY KPI TIDAK BOLEH TERCEMAR
         */
        if ($operator->status !== 'active') {
            throw ValidationException::withMessages([
                'operator_code' => 'Operator inactive tidak boleh digunakan dalam reject.',
            ]);
        }

        if ($machine->status !== 'active') {
            throw ValidationException::withMessages([
                'machine_code' => 'Machine inactive tidak boleh digunakan dalam reject.',
            ]);
        }

        if ($item->status !== 'active') {
            throw ValidationException::withMessages([
                'item_code' => 'Item inactive tidak boleh digunakan dalam reject.',
            ]);
        }

        /**
         * 4. SIMPAN KE FACT TABLE
         * Snapshot (NO FK)
         */
        RejectLog::create([
            'reject_date'   => $validated['reject_date'],
            'operator_code' => $this->normalizeCode($validated['operator_code']),
            'machine_code'  => $this->normalizeCode($validated['machine_code']),
            'item_code'     => $this->normalizeCode($validated['item_code']),
            'reject_qty'    => $validated['reject_qty'],
            'reject_reason' => $validated['reject_reason'],
            'note'          => $validated['note'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data reject berhasil disimpan');
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
