@extends('layouts.app')

@section('title', 'Tracking Downtime')

@section('content')

<x-card title="Ringkasan Downtime per Mesin">

<form method="GET" class="flex gap-2 mb-4">
    <input type="date" name="date"
           value="{{ request('date', $date) }}"
           class="border rounded px-2 py-1 text-sm">

    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
        Filter
    </button>
</form>

<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Mesin</th>
            <th class="border p-2 text-right">Total Downtime (menit)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($summary as $row)
        <tr>
            <td class="border p-2">{{ $row->downtime_date }}</td>
            <td class="border p-2">{{ $row->machine_code }}</td>
            <td class="border p-2 text-right">{{ $row->total_minutes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

<x-card title="Detail Downtime">
<div class="flex justify-between items-center mb-4">
    <div class="text-sm text-gray-500">
        Data Downtime
    </div>

    <a href="{{ url('/export/downtime/'.$date) }}"
       class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded">
        Download Excel
    </a>
</div>


<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Operator</th>
            <th class="border p-2">Mesin</th>
            <th class="border p-2 text-right">Durasi (menit)</th>
            <th class="border p-2">Catatan</th>
        </tr>
    </thead>
    <tbody>
    @foreach($list as $row)
        <tr>
            <td class="border p-2">{{ $row->downtime_date }}</td>
            <td class="border p-2">{{ $row->operator_code }}</td>
            <td class="border p-2">{{ $row->machine_code }}</td>
            <td class="border p-2 text-right">{{ $row->duration_minutes }}</td>
            <td class="border p-2">{{ $row->note }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

@endsection
