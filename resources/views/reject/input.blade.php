@extends('layouts.app')

@section('title', 'Input Reject')

@section('content')
<x-card title="Input Reject Produksi">

    <form method="POST" action="{{ url('/reject/store') }}">
        @csrf

        <div class="form-grid">

            {{-- Tanggal Reject --}}
            <div class="form-group">
                <label>Tanggal</label>
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
                <input
                    type="text"
                    name="operator_code"
                    value="{{ old('operator_code') }}"
                    required
                >
            </div>

            {{-- Mesin --}}
            <div class="form-group">
                <label>Mesin</label>
                <input
                    type="text"
                    name="machine_code"
                    value="{{ old('machine_code') }}"
                    required
                >
            </div>

            {{-- Item --}}
            <div class="form-group">
                <label>Item</label>
                <input
                    type="text"
                    name="item_code"
                    value="{{ old('item_code') }}"
                    required
                >
            </div>

            {{-- Qty Reject --}}
            <div class="form-group">
                <label>Qty Reject</label>
                <input
                    type="number"
                    name="reject_qty"
                    min="0"
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
