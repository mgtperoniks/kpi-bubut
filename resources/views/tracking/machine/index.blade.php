@extends('layouts.app')

@section('title', 'Tracking KPI Mesin')

@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">KPI Harian Mesin</h1>
            <p class="text-gray-500">Rekapitulasi data harian per tanggal {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM Y') }}</p>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('tracking.mesin.pdf', $date) }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </a>
            <a href="{{ url('/export/machine/' . $date) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-wrap gap-4 items-center justify-between">
            <form method="GET" class="flex gap-2 items-center">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <input type="date" name="date" value="{{ request('date', $date) }}"
                           class="pl-10 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 py-2">
                </div>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                    Filter
                </button>
            </form>

            <form action="{{ route('api.manual.sync') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ request('date', $date) }}">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-amber-700 bg-amber-100 hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Data
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100 font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Shift</th>
                        <th class="px-6 py-3">Mesin</th>
                        <th class="px-6 py-3 text-right">Jam Kerja</th>
                        <th class="px-6 py-3 text-right">Target</th>
                        <th class="px-6 py-3 text-right">Aktual</th>
                        <th class="px-6 py-3 text-center">KPI (%)</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rows as $row)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap">
                                {{ $row->kpi_date }}
                            </td>
                            <td class="px-6 py-4">
                                @if(isset($shifts[$row->machine_code]))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        Shift {{ $shifts[$row->machine_code] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $machineNames[$row->machine_code] ?? $row->machine_code }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $row->machine_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-gray-600">
                                {{ number_format($row->total_work_hours, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-600">
                                {{ $row->total_target_qty }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                {{ $row->total_actual_qty }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $kpiClass = 'bg-red-100 text-red-700';
                                    if ($row->kpi_percent >= 100) $kpiClass = 'bg-green-100 text-green-700';
                                    elseif ($row->kpi_percent >= 85) $kpiClass = 'bg-orange-100 text-orange-700';
                                @endphp
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $kpiClass }}">
                                    {{ number_format($row->kpi_percent, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ url('/tracking/mesin/' . $row->machine_code . '/' . $row->kpi_date) }}"
                                   class="text-blue-600 hover:text-blue-900 font-medium text-xs uppercase tracking-wide">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <p>Data KPI mesin tidak ditemukan untuk tanggal ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection