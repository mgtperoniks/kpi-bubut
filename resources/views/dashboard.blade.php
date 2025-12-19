
@extends('layouts.app')
@section('title','Dashboard KPI Bubut')
@section('content')
<x-card title="Ringkasan KPI Hari Ini">
    <div class="grid grid-cols-4 gap-4 text-center">
        <div class="bg-gray-50 p-4 rounded"><div class="text-sm">Avg KPI</div><div class="text-xl font-bold">98.5%</div></div>
        <div class="bg-gray-50 p-4 rounded"><div class="text-sm">Operator</div><div class="text-xl font-bold">12</div></div>
        <div class="bg-gray-50 p-4 rounded"><div class="text-sm">Mesin</div><div class="text-xl font-bold">8</div></div>
        <div class="bg-gray-50 p-4 rounded"><div class="text-sm">Output</div><div class="text-xl font-bold">1.240</div></div>
    </div>
</x-card>
@endsection
