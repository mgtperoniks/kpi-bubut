@extends('layouts.app')

@section('title', 'Input Downtime')

@section('content')
<x-card title="Input Downtime">

    {{-- FEEDBACK --}}
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

    <form method="POST" action="{{ url('/downtime/store') }}">
        @csrf

        <div class="form-grid">

            {{-- Tanggal Downtime --}}
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
                    @foreach ($machines as $mc)
                        <option
                            value="{{ $mc->code }}"
                            {{ old('machine_code') == $mc->code ? 'selected' : '' }}>
                            {{ $mc->name }} ({{ $mc->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jam Mulai --}}
            <div class="form-group">
                <label>Jam Mulai</label>
                <input
                    type="time"
                    name="time_start"
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
                    value="{{ old('time_end') }}"
                    required
                >
            </div>

            {{-- Alasan Downtime --}}
            <div class="form-group form-span-3">
                <label>Alasan Downtime</label>
                <input
                    type="text"
                    name="reason"
                    value="{{ old('reason') }}"
                    required
                >
            </div>

            {{-- Catatan --}}
            <div class="form-group form-span-4">
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
