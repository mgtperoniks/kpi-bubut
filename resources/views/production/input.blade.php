@extends('layouts.app')

@section('title', 'Input Produksi Bubut')

@section('content')
<x-card title="Input Hasil Operator Bubut">

    <form method="POST" action="{{ url('/produksi/store') }}" class="grid grid-cols-4 gap-4">
        @csrf

        {{-- Tanggal Produksi --}}
        <div>
            <label class="text-sm">Tanggal</label>
            <input
                type="date"
                name="production_date"
                class="w-full border rounded p-2"
                required
            >
        </div>

        {{-- Operator --}}
        <div>
            <label class="text-sm">Operator</label>
            <input
                type="text"
                name="operator"
                class="w-full border rounded p-2"
                placeholder="Nama Operator"
                required
            >
        </div>

        {{-- Mesin --}}
        <div>
            <label class="text-sm">Mesin</label>
            <input
                type="text"
                name="machine"
                class="w-full border rounded p-2"
                placeholder="Kode Mesin"
                required
            >
        </div>

        {{-- Item --}}
        <div>
            <label class="text-sm">Item</label>
            <input
                type="text"
                name="item"
                class="w-full border rounded p-2"
                placeholder="Nama Item"
                required
            >
        </div>

        {{-- Jam Mulai --}}
        <div>
            <label class="text-sm">Jam Mulai</label>
            <input
                type="time"
                name="time_start"
                class="w-full border rounded p-2"
                required
            >
        </div>

        {{-- Jam Selesai --}}
        <div>
            <label class="text-sm">Jam Selesai</label>
            <input
                type="time"
                name="time_end"
                class="w-full border rounded p-2"
                required
            >
        </div>

        {{-- Qty Aktual --}}
        <div>
            <label class="text-sm">Qty Aktual</label>
            <input
                type="number"
                name="actual_qty"
                class="w-full border rounded p-2"
                min="0"
                required
            >
        </div>

        {{-- Spacer agar grid rapi --}}
        <div></div>

        {{-- Tombol Simpan --}}
        <div class="col-span-4 mt-6">
            <x-button type="submit">
                Simpan
            </x-button>
        </div>

    </form>

</x-card>
@endsection
