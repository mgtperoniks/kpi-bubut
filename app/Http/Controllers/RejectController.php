<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RejectLog;

class RejectController extends Controller
{
    public function index()
    {
        $data = RejectLog::orderBy('reject_date', 'desc')->get();
        return view('reject.index', compact('data'));
    }

    public function create()
    {
        return view('reject.input');
    }

    public function store(Request $request)
    {
        RejectLog::create($request->only([
            'reject_date',
            'operator_code',
            'machine_code',
            'item_code',
            'reject_qty',
            'reject_reason',
            'note',
        ]));

        return redirect()->back()->with('success', 'Reject berhasil disimpan');
    }
}
