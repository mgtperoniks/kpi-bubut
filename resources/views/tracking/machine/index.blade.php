@extends('layouts.app')

@section('title', 'Tracking KPI Mesin')

@section('content')

<x-card title="KPI Harian Mesin">

    <table>

        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Mesin</th>
                <th class="text-right">Jam</th>
                <th class="text-right">Target</th>
                <th class="text-right">Aktual</th>
                <th class="text-right">KPI (%)</th>
                <th class="text-center">Status</th>
                <th class="text-center">Detail</th>
            </tr>
        </thead>

        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->kpi_date }}</td>
                <td>{{ $row->machine_code }}</td>
                <td class="text-right">{{ $row->total_work_hours }}</td>
                <td class="text-right">{{ $row->total_target_qty }}</td>
                <td class="text-right">{{ $row->total_actual_qty }}</td>

                {{-- KPI Percent --}}
                <td class="text-right">
                    <span class="{{ $row->kpi_percent >= 100 ? 'kpi-good' : 'kpi-bad' }}">
                        {{ number_format($row->kpi_percent, 1) }}%
                    </span>
                </td>

                {{-- Status Badge --}}
                <td class="text-center">
                    @if($row->kpi_percent >= 100)
                        <span class="kpi-badge kpi-ok">OK</span>
                    @elseif($row->kpi_percent >= 90)
                        <span class="kpi-badge kpi-warning">WARNING</span>
                    @else
                        <span class="kpi-badge kpi-bad">BAD</span>
                    @endif
                </td>

                {{-- Detail --}}
                <td class="text-center">
                    <a href="{{ url('/tracking/mesin/'.$row->machine_code.'/'.$row->kpi_date) }}"
                       class="link-detail">
                        Lihat
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

</x-card>

@endsection
