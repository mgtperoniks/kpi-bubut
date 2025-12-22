@extends('layouts.app')

@section('title', 'Tracking KPI Operator')

@section('content')

<x-card title="KPI Harian Operator">

    <div class="flex justify-between items-center mb-4">
        <div class="text-sm text-gray-500">
            Data KPI Operator
        </div>

        <a href="{{ url('/export/operator/'.$date) }}"
           class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded">
            Download Excel
        </a>
    </div>

    {{-- FILTER TANGGAL --}}
    <form method="GET" class="flex gap-2 mb-4">
        <input
            type="date"
            name="date"
            value="{{ request('date', $date) }}"
            class="border rounded px-2 py-1 text-sm"
        >

        <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
            Filter
        </button>
    </form>

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
        @forelse ($rows as $row)
            <tr>
                <td class="border p-2">
                    {{ $row->kpi_date }}
                </td>

                {{-- OPERATOR (MAPPING DARI MASTER) --}}
                <td class="border p-2">
                    {{ $operatorNames[$row->operator_code] ?? $row->operator_code }}
                </td>

                <td class="border p-2 text-right">
                    {{ number_format($row->total_work_hours, 2) }}
                </td>

                <td class="border p-2 text-right">
                    {{ $row->total_target_qty }}
                </td>

                <td class="border p-2 text-right">
                    {{ $row->total_actual_qty }}
                </td>

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
        @empty
            <tr>
                <td colspan="7" class="border p-4 text-center text-gray-500">
                    Data KPI tidak ditemukan untuk tanggal ini
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>

</x-card>

@endsection
