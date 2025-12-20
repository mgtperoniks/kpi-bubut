@extends('layouts.app')

@section('title', 'Tracking Downtime')

@section('content')

<x-card title="Ringkasan Downtime per Mesin">

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
