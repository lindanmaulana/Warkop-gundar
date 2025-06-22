@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
    <a href="{{ route('dashboard.menu') }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-px text-white rounded"><x-icon name="arrow-left" /> Back</a>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-dark-blue">Tambah Product</h2>
        <a href="{{ route('dashboard.admin.category') }}" class="bg-dark-blue px-2 rounded py-1 text-white flex items-center gap-1 text-sm"><x-icon name="arrow-left" />Back</a>
    </div>
    <div class="flex flex-col gap-4 bg-white px-2 py-6 rounded-lg shadow-sm shadow-dark-blue/10">
        <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-3">
                <label for="name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Nama:</span>
                    <input type="text" id="name" name="name" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="category" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Category:</span>
                    <select id="category" name="category_id" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="price" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Harga:</span>
                    <input type="number" id="price" name="price" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('price')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="stock" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Stock:</span>
                    <input type="number" id="stock" name="stock" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('stock')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="description" class="flex flex-col gap-3 rounded-md">
                    <span class="text-dark-blue font-semibold">Deskripsi:</span>
                    <input type="text" id="description" name="description" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('description')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Simpan</button>
                <button type="reset" class="px-4 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection