@extends('layouts.app')

@section('title', 'Tracking KPI Mesin')

@section('content')

<x-card title="KPI Harian Mesin">

<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Mesin</th>
            <th class="border p-2 text-right">Jam</th>
            <th class="border p-2 text-right">Target</th>
            <th class="border p-2 text-right">Aktual</th>
            <th class="border p-2 text-right">KPI (%)</th>
            <th class="border p-2">Detail</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td class="border p-2">{{ $row->kpi_date }}</td>
            <td class="border p-2">{{ $row->machine_code }}</td>
            <td class="border p-2 text-right">{{ $row->total_work_hours }}</td>
            <td class="border p-2 text-right">{{ $row->total_target_qty }}</td>
            <td class="border p-2 text-right">{{ $row->total_actual_qty }}</td>
            <td class="border p-2 text-right font-semibold">{{ $row->kpi_percent }}%</td>
            <td class="border p-2 text-center">
                <a href="{{ url('/tracking/mesin/'.$row->machine_code.'/'.$row->kpi_date) }}"
                   class="text-blue-600 hover:underline">Lihat</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

@endsection
