@extends('layouts.app')

@section('title', 'Tracking KPI Mesin')

@section('content')

<x-card title="KPI Harian Mesin">

    <div class="flex justify-between items-center mb-4">
        <div class="text-sm text-gray-500">
            Data KPI Mesin
        </div>

        <a href="{{ url('/export/machine/'.$date) }}"
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
                <th class="border p-2">Mesin</th>
                <th class="border p-2 text-right">Jam</th>
                <th class="border p-2 text-right">Target</th>
                <th class="border p-2 text-right">Aktual</th>
                <th class="border p-2 text-right">KPI (%)</th>
                <th class="border p-2 text-center">Status</th>
                <th class="border p-2 text-center">Detail</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($rows as $row)
            <tr>
                <td class="border p-2">
                    {{ $row->kpi_date }}
                </td>

                {{-- MESIN (MAPPING DARI MASTER) --}}
                <td class="border p-2">
                    {{ $machineNames[$row->machine_code] ?? $row->machine_code }}
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

                {{-- KPI Percent --}}
                <td class="border p-2 text-right">
                    <span class="{{ $row->kpi_percent >= 100 ? 'kpi-good' : 'kpi-bad' }}">
                        {{ number_format($row->kpi_percent, 1) }}%
                    </span>
                </td>

                {{-- Status Badge --}}
                <td class="border p-2 text-center">
                    @if ($row->kpi_percent >= 100)
                        <span class="kpi-badge kpi-ok">OK</span>
                    @elseif ($row->kpi_percent >= 90)
                        <span class="kpi-badge kpi-warning">WARNING</span>
                    @else
                        <span class="kpi-badge kpi-bad">BAD</span>
                    @endif
                </td>

                {{-- Detail --}}
                <td class="border p-2 text-center">
                    <a href="{{ url('/tracking/mesin/'.$row->machine_code.'/'.$row->kpi_date) }}"
                       class="text-blue-600 hover:underline">
                        Lihat
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="border p-4 text-center text-gray-500">
                    Data KPI mesin tidak ditemukan untuk tanggal ini
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>

</x-card>

@endsection
