@extends('layouts.app')

@section('title', 'Input Reject')

@section('content')

<x-card title="Input Reject Produksi">

<form method="POST" action="{{ url('/reject/store') }}" class="grid grid-cols-4 gap-4">
@csrf

    <div>
        <label class="text-sm">Tanggal</label>
        <input type="date" name="reject_date" class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="text-sm">Operator</label>
        <input type="text" name="operator_code" class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="text-sm">Mesin</label>
        <input type="text" name="machine_code" class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="text-sm">Item</label>
        <input type="text" name="item_code" class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="text-sm">Qty Reject</label>
        <input type="number" name="reject_qty" class="w-full border p-2 rounded">
    </div>

    <div class="col-span-3">
        <label class="text-sm">Alasan Reject</label>
        <input type="text" name="reject_reason" class="w-full border p-2 rounded">
    </div>

    <div class="col-span-4">
        <label class="text-sm">Catatan</label>
        <textarea name="note" class="w-full border p-2 rounded"></textarea>
    </div>

</form>

<div class="mt-4">
    <x-button>Simpan Reject</x-button>
</div>

</x-card>

@endsection
