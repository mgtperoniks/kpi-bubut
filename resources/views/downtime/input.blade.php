@extends('layouts.app')

@section('title', 'Input Downtime')

@section('content')
<x-card title="Input Downtime">

    <form method="POST" action="{{ url('/downtime/store') }}">
        @csrf

        <div class="form-grid">

            {{-- Tanggal --}}
            <div class="form-group">
                <label>Tanggal</label>
                <input
                    type="date"
                    name="downtime_date"
                    value="{{ old('downtime_date', date('Y-m-d')) }}"
                    required
                >
            </div>

            {{-- Mesin --}}
            <div class="form-group">
                <label>Mesin</label>
                <select name="machine_code" required>
                    <option value="">-- Pilih Mesin --</option>
                    {{-- contoh static, bisa diganti master mesin --}}
                    <option value="CNC-01">CNC-01</option>
                </select>
            </div>

            {{-- Durasi --}}
            <div class="form-group">
                <label>Durasi (menit)</label>
                <input
                    type="number"
                    name="duration_min"
                    min="1"
                    value="{{ old('duration_min') }}"
                    required
                >
            </div>

            {{-- Catatan --}}
            <div class="form-group form-span-3">
                <label>Catatan</label>
                <textarea
                    name="note"
                    rows="3"
                >{{ old('note') }}</textarea>
            </div>

        </div>

        <div class="form-actions">
            <x-button type="submit">
                Simpan Downtime
            </x-button>
        </div>

    </form>

</x-card>
@endsection
