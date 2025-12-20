@extends('layouts.app')

@section('title', 'Data Reject')

@section('content')

<x-card title="Data Reject Produksi">

<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Operator</th>
            <th class="border p-2">Mesin</th>
            <th class="border p-2">Item</th>
            <th class="border p-2 text-right">Qty</th>
            <th class="border p-2">Alasan</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td class="border p-2">{{ $row->reject_date }}</td>
            <td class="border p-2">{{ $row->operator_code }}</td>
            <td class="border p-2">{{ $row->machine_code }}</td>
            <td class="border p-2">{{ $row->item_code }}</td>
            <td class="border p-2 text-right">{{ $row->reject_qty }}</td>
            <td class="border p-2">{{ $row->reject_reason }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

@endsection
