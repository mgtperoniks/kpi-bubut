@extends('layouts.app')

@section('title', 'Input Reject')

@section('content')
<x-card title="Input Reject Produksi">

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

    <form method="POST" action="{{ url('/reject/store') }}">
        @csrf

        <div class="form-grid">

            {{-- Tanggal Reject --}}
            <div class="form-group">
                <label>Tanggal Reject</label>
                <input
                    type="date"
                    name="reject_date"
                    value="{{ old('reject_date', date('Y-m-d')) }}"
                    required
                >
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
                <select name="item_code" required>
                    <option value="">-- Pilih Item --</option>
                    @foreach ($items as $it)
                        <option
                            value="{{ $it->code }}"
                            {{ old('item_code') == $it->code ? 'selected' : '' }}>
                            {{ $it->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Qty Reject --}}
            <div class="form-group">
                <label>Qty Reject</label>
                <input
                    type="number"
                    name="reject_qty"
                    min="1"
                    value="{{ old('reject_qty') }}"
                    required
                >
            </div>

            {{-- Alasan Reject --}}
            <div class="form-group form-span-3">
                <label>Alasan Reject</label>
                <input
                    type="text"
                    name="reject_reason"
                    value="{{ old('reject_reason') }}"
                    required
                >
            </div>

            {{-- Catatan --}}
            <div class="form-group form-span-4">
                <label>Catatan</label>
                <textarea name="note" rows="3">{{ old('note') }}</textarea>
            </div>

        </div>

        <div class="form-actions">
            <x-button type="submit">
                Simpan Reject
            </x-button>
        </div>

    </form>

</x-card>
@endsection
