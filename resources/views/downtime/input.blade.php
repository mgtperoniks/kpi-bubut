
@extends('layouts.app')
@section('title','Input Downtime')
@section('content')
<x-card title="Input Downtime">
<form class="grid grid-cols-3 gap-4">
    <div><label class="text-sm">Tanggal</label><input type="date" class="w-full border rounded p-2"></div>
    <div><label class="text-sm">Mesin</label><select class="w-full border rounded p-2"><option>CNC-01</option></select></div>
    <div><label class="text-sm">Durasi (menit)</label><input type="number" class="w-full border rounded p-2"></div>
    <div class="col-span-3"><label class="text-sm">Catatan</label><textarea class="w-full border rounded p-2"></textarea></div>
</form>
<div class="mt-6"><x-button>Simpan</x-button></div>
</x-card>
@endsection
