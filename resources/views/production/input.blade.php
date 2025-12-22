@extends('layouts.app')

@section('title', 'Input Produksi Bubut')

@section('content')
<x-card title="Input Hasil Operator Bubut">

    {{-- FEEDBACK KE USER --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/produksi/store') }}">
        @csrf

        <div class="form-grid">

            {{-- Tanggal Produksi --}}
            <div class="form-group">
                <label>Tanggal Produksi</label>
                <input
                    type="date"
                    name="production_date"
                    value="{{ old('production_date', date('Y-m-d')) }}"
                    required
                >
            </div>

            {{-- Shift --}}
            <div class="form-group">
                <label>Shift</label>
                <select name="shift" required>
                    <option value="A" {{ old('shift') == 'A' ? 'selected' : '' }}>Shift A</option>
                    <option value="B" {{ old('shift') == 'B' ? 'selected' : '' }}>Shift B</option>
                    <option value="C" {{ old('shift') == 'C' ? 'selected' : '' }}>Shift C</option>
                </select>
            </div>

            {{-- Operator --}}
            <div class="form-group">
                <label>Operator</label>
                <select name="operator_code" required>
                    <option value="">-- Pilih Operator --</option>
                    @foreach ($operators as $op)
                        <option
                            value="{{ $op->code }}"
                            {{ old('operator_code') == $op->code ? 'selected' : '' }}>
                            {{ $op->name }} ({{ $op->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mesin --}}
            <div class="form-group">
                <label>Mesin</label>
                <select name="machine_code" required>
                    <option value="">-- Pilih Mesin --</option>
                    @foreach ($machines as $mc)
                        <option
                            value="{{ $mc->code }}"
                            {{ old('machine_code') == $mc->code ? 'selected' : '' }}>
                            {{ $mc->name }} ({{ $mc->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Item --}}
            <div class="form-group">
                <label>Item</label>
                <select name="item_code" id="item_select" required>
                    <option value="">-- Pilih Item --</option>
                    @foreach ($items as $it)
                        <option
                            value="{{ $it->code }}"
                            data-cycle="{{ $it->cycle_time_sec }}"
                            {{ old('item_code') == $it->code ? 'selected' : '' }}>
                            {{ $it->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cycle Time Preview --}}
            <div class="form-group">
                <label>Cycle Time (detik)</label>
                <input
                    type="number"
                    id="preview_cycle_time"
                    readonly
                >
            </div>

            {{-- Jam Mulai --}}
            <div class="form-group">
                <label>Jam Mulai</label>
                <input
                    type="time"
                    name="time_start"
                    id="time_start"
                    value="{{ old('time_start') }}"
                    required
                >
            </div>

            {{-- Jam Selesai --}}
            <div class="form-group">
                <label>Jam Selesai</label>
                <input
                    type="time"
                    name="time_end"
                    id="time_end"
                    value="{{ old('time_end') }}"
                    required
                >
            </div>

            {{-- Qty Aktual --}}
            <div class="form-group">
                <label>Qty Aktual</label>
                <input
                    type="number"
                    name="actual_qty"
                    min="0"
                    value="{{ old('actual_qty') }}"
                    required
                >
            </div>

            {{-- Target Preview --}}
            <div class="form-group">
                <label>Target (Preview)</label>
                <input
                    type="number"
                    id="preview_target"
                    readonly
                >
            </div>

        </div>

        <div class="form-actions">
            <x-button type="submit">
                Simpan Data Produksi
            </x-button>
        </div>

    </form>

</x-card>

{{-- JS Ringan: Preview Cycle Time & Target --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const itemSelect = document.getElementById('item_select');
    const startInput = document.getElementById('time_start');
    const endInput   = document.getElementById('time_end');

    const cyclePreview  = document.getElementById('preview_cycle_time');
    const targetPreview = document.getElementById('preview_target');

    function calculatePreview() {
        const option = itemSelect.options[itemSelect.selectedIndex];
        const cycle  = option?.dataset?.cycle;

        if (!cycle || !startInput.value || !endInput.value) {
            cyclePreview.value  = '';
            targetPreview.value = '';
            return;
        }

        cyclePreview.value = cycle;

        const start = new Date(`1970-01-01T${startInput.value}:00`);
        const end   = new Date(`1970-01-01T${endInput.value}:00`);
        let seconds = (end - start) / 1000;

        if (seconds <= 0) {
            targetPreview.value = '';
            return;
        }

        targetPreview.value = Math.floor(seconds / cycle);
    }

    itemSelect.addEventListener('change', calculatePreview);
    startInput.addEventListener('change', calculatePreview);
    endInput.addEventListener('change', calculatePreview);

    calculatePreview();
});
</script>
@endsection
