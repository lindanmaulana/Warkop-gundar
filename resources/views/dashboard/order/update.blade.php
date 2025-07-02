@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-dark-blue">Edit Product</h2>
        <a href="{{ route('dashboard.orders') }}" class="bg-dark-blue px-2 rounded py-1 text-white flex items-center gap-1 text-sm"><x-icon name="arrow-left" />Back</a>
    </div>
    <div class="flex flex-col gap-4 bg-white px-2 py-6 rounded-lg shadow-sm shadow-dark-blue/10">
        <form action="{{ route('order.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method("PATCH")
            <div class="space-y-3">
                <label for="customer_name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Nama Pembeli:</span>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('name', $order->customer_name ) }}" class="w-full border-2 text-dark-blue/70 border-dark-blue/20 px-4 py-1 rounded-sm" readonly>
                </label>
                @error('customer_name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="branch" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Lokasi Warkop:</span>
                    <input type="text" id="branch" name="branch" value="{{ old('name', $order->branch ) }}" class="w-full border-2 text-dark-blue/70 border-dark-blue/20 px-4 py-1 rounded-sm" readonly>
                </label>
                @error('branch')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="status" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Status:</span>
                    <select id="status" name="status" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                        <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="done" {{ old('status', $order->status) == 'done' ? 'selected' : '' }}>Done</option>
                        <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </label>
                @error('status')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Update</button>
                <button type="reset" class="px-4 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection