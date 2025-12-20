@extends('layouts.app')

@section('title', 'Detail KPI Mesin')

@section('content')

<x-card title="Ringkasan KPI Mesin">
    <div class="grid grid-cols-4 gap-4">
        <div><div class="text-sm text-gray-500">Mesin</div><div class="font-bold">{{ $summary->machine_code }}</div></div>
        <div><div class="text-sm text-gray-500">Tanggal</div><div class="font-bold">{{ $summary->kpi_date }}</div></div>
        <div><div class="text-sm text-gray-500">Jam</div><div class="font-bold">{{ $summary->total_work_hours }}</div></div>
        <div><div class="text-lg">
    <span class="{{ $summary->kpi_percent >= 100 ? 'kpi-good' : 'kpi-bad' }}">
        {{ $summary->kpi_percent }}%
    </span>
</div>
</div>
    </div>
</x-card>

<x-card title="Detail Aktivitas Mesin">

<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Operator</th>
            <th class="border p-2">Item</th>
            <th class="border p-2">Jam</th>
            <th class="border p-2 text-right">Target</th>
            <th class="border p-2 text-right">Aktual</th>
            <th class="border p-2 text-right">KPI (%)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($activities as $act)
        <tr>
            <td class="border p-2">{{ $act->operator_code }}</td>
            <td class="border p-2">{{ $act->item_code }}</td>
            <td class="border p-2">{{ $act->time_start }} - {{ $act->time_end }}</td>
            <td class="border p-2 text-right">{{ $act->target_qty }}</td>
            <td class="border p-2 text-right">{{ $act->actual_qty }}</td>
            <td class="border p-2 text-right">{{ $act->achievement_percent }}%</td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

@endsection
