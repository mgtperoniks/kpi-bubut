@extends('layouts.app')

@section('title', 'Dashboard KPI Bubut')

@section('content')

@if(isset($empty))
<x-card title="Dashboard KPI Bubut">
    <p class="text-gray-500">Belum ada data KPI.</p>
</x-card>
@else

<x-card title="Dashboard KPI Bubut ({{ $date }})">

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

    <div class="bg-white border rounded p-4 text-center">
        <div class="text-sm text-gray-500">Avg KPI Operator</div>
        <div class="text-xl font-bold">
            {{ number_format($avgKpiOperator, 2) }}%
        </div>
    </div>

    <div class="bg-white border rounded p-4 text-center">
        <div class="text-sm text-gray-500">Avg KPI Mesin</div>
        <div class="text-xl font-bold">
            {{ number_format($avgKpiMachine, 2) }}%
        </div>
    </div>

    <div class="bg-white border rounded p-4 text-center">
        <div class="text-sm text-gray-500">Total Output</div>
        <div class="text-xl font-bold">
            {{ $totalOutput }}
        </div>
    </div>

    <div class="bg-white border rounded p-4 text-center">
        <div class="text-sm text-gray-500">Total Downtime</div>
        <div class="text-xl font-bold">
            {{ $totalDowntime }} menit
        </div>
    </div>

    <div class="bg-white border rounded p-4 text-center">
        <div class="text-sm text-gray-500">Operator Aktif</div>
        <div class="text-xl font-bold">
            {{ $activeOperators }}
        </div>
    </div>

    <div class="bg-white border rounded p-4 text-center">
        <div class="text-sm text-gray-500">Mesin Aktif</div>
        <div class="text-xl font-bold">
            {{ $activeMachines }}
        </div>
    </div>

</div>

</x-card>

@endif

@endsection
