<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RejectLog;

// MASTER MIRROR
use App\Models\MdOperator;
use App\Models\MdMachine;
use App\Models\MdItem;

class RejectController extends Controller
{
    /**
     * List data reject
     */
    public function index()
    {
        $rows = RejectLog::orderBy('reject_date', 'desc')->get();

        return view('reject.index', [
            'rows' => $rows,
        ]);
    }

    /**
     * Form input reject
     * Autofill dari master mirror
     */
    public function create()
    {
        return view('reject.input', [
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
     * Simpan data reject
     */
    public function store(Request $request)
    {
        /**
         * VALIDASI MINIMAL (WAJIB)
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

        RejectLog::create([
            'reject_date'   => $validated['reject_date'],
            'operator_code' => strtolower(trim($validated['operator_code'])),
            'machine_code'  => strtolower(trim($validated['machine_code'])),
            'item_code'     => strtolower(trim($validated['item_code'])),
            'reject_qty'    => $validated['reject_qty'],
            'reject_reason' => $validated['reject_reason'],
            'note'          => $validated['note'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data reject berhasil disimpan');
    }
}
