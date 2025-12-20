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
                <select name="operator" required>
                    <option value="">-- Pilih Operator --</option>
                    @foreach($operators as $op)
                        <option
                            value="{{ $op->operator_code }}"
                            {{ old('operator') == $op->operator_code ? 'selected' : '' }}>
                            {{ $op->operator_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mesin --}}
            <div class="form-group">
                <label>Mesin</label>
                <select name="machine" required>
                    <option value="">-- Pilih Mesin --</option>
                    @foreach($machines as $m)
                        <option
                            value="{{ $m->machine_code }}"
                            {{ old('machine') == $m->machine_code ? 'selected' : '' }}>
                            {{ $m->machine_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Item --}}
            <div class="form-group">
                <label>Item</label>
                <select name="item" id="item_select" required>
                    <option value="">-- Pilih Item --</option>
                    @foreach($items as $it)
                        <option
                            value="{{ $it->item_code }}"
                            data-cycle="{{ $it->cycle_time_sec }}"
                            {{ old('item') == $it->item_code ? 'selected' : '' }}>
                            {{ $it->item_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cycle Time Preview --}}
            <div class="form-group">
                <label>Cycle Time (detik)</label>
                <input
                    type="number"
                    id="cycle_time_preview"
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
                    id="target_preview"
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

{{-- JS Ringan: Auto-fill Cycle Time & Target Preview --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const itemSelect    = document.getElementById('item_select');
    const cyclePreview  = document.getElementById('cycle_time_preview');
    const targetPreview = document.getElementById('target_preview');
    const timeStart     = document.getElementById('time_start');
    const timeEnd       = document.getElementById('time_end');

    function calculateTarget() {
        const cycle = parseInt(cyclePreview.value);
        if (!cycle || !timeStart.value || !timeEnd.value) {
            targetPreview.value = '';
            return;
        }

        const start = new Date(`1970-01-01T${timeStart.value}:00`);
        const end   = new Date(`1970-01-01T${timeEnd.value}:00`);

        let diffSec = (end - start) / 1000;
        if (diffSec < 0) diffSec += 86400;

        targetPreview.value = Math.floor(diffSec / cycle);
    }

    function updateCycleFromSelected() {
        const option = itemSelect.options[itemSelect.selectedIndex];
        cyclePreview.value = option?.dataset?.cycle || '';
        calculateTarget();
    }

    itemSelect.addEventListener('change', updateCycleFromSelected);
    timeStart.addEventListener('change', calculateTarget);
    timeEnd.addEventListener('change', calculateTarget);

    updateCycleFromSelected();
});
</script>
@endsection
