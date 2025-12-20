@extends('layouts.app')

@section('title', 'Tracking KPI Operator')

@section('content')

<x-card title="KPI Harian Operator">

<x-table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Operator</th>
            <th class="border p-2 text-right">Jam Kerja</th>
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
            <td class="border p-2">{{ $row->operator_code }}</td>
            <td class="border p-2 text-right">{{ $row->total_work_hours }}</td>
            <td class="border p-2 text-right">{{ $row->total_target_qty }}</td>
            <td class="border p-2 text-right">{{ $row->total_actual_qty }}</td>
            <td class="border p-2 text-right">
    <span class="{{ $row->kpi_percent >= 100 ? 'kpi-good' : 'kpi-bad' }}">
        {{ $row->kpi_percent }}%
    </span>
</td>
            <td class="border p-2 text-center">
                <a href="{{ url('/tracking/operator/'.$row->operator_code.'/'.$row->kpi_date) }}"
                   class="text-blue-600 hover:underline">
                    Lihat
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</x-table>

</x-card>

@endsection
