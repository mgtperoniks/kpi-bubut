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

    <form method="POST" action="{{ route('reject.store') }}">
        @csrf

        <div class="form-grid">

            {{-- TANGGAL REJECT --}}
            <div class="form-group">
                <label for="reject_date">Tanggal Reject</label>
                <input
                    type="date"
                    id="reject_date"
                    name="reject_date"
                    value="{{ old('reject_date', now()->toDateString()) }}"
                    required
                >
            </div>

            {{-- OPERATOR --}}
            <div class="form-group">
                <label for="operator_code">Operator</label>
                <select id="operator_code" name="operator_code" required>
                    <option value="">-- Pilih Operator --</option>
                    @foreach ($operators as $operator)
                        <option value="{{ $operator->code }}"
                            {{ old('operator_code') === $operator->code ? 'selected' : '' }}>
                            {{ $operator->name }} ({{ $operator->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MESIN --}}
            <div class="form-group">
                <label for="machine_code">Mesin</label>
                <select id="machine_code" name="machine_code" required>
                    <option value="">-- Pilih Mesin --</option>
                    @foreach ($machines as $machine)
                        <option value="{{ $machine->code }}"
                            {{ old('machine_code') === $machine->code ? 'selected' : '' }}>
                            {{ $machine->name }} ({{ $machine->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ITEM --}}
            <div class="form-group">
                <label for="item_code">Item</label>
                <select id="item_code" name="item_code" required>
                    <option value="">-- Pilih Item --</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->code }}"
                            {{ old('item_code') === $item->code ? 'selected' : '' }}>
                            {{ $item->code }} - {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- QTY REJECT --}}
            <div class="form-group">
                <label for="reject_qty">Qty Reject</label>
                <input
                    type="number"
                    id="reject_qty"
                    name="reject_qty"
                    min="1"
                    value="{{ old('reject_qty') }}"
                    required
                >
            </div>

            {{-- ALASAN REJECT --}}
            <div class="form-group form-span-3">
                <label for="reject_reason">Alasan Reject</label>
                <input
                    type="text"
                    id="reject_reason"
                    name="reject_reason"
                    value="{{ old('reject_reason') }}"
                    maxlength="255"
                    required
                >
            </div>

            {{-- CATATAN --}}
            <div class="form-group form-span-4">
                <label for="note">Catatan</label>
                <textarea
                    id="note"
                    name="note"
                    rows="3"
                >{{ old('note') }}</textarea>
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
