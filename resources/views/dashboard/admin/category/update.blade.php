@php
// Fungsi helper untuk memeriksa apakah rute saat ini cocok dengan nama rute yang diberikan
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
        <h2 class="text-2xl font-semibold text-dark-blue">Edit Kategori</h2>
        <a href="{{ route('dashboard.admin.category') }}" class="bg-dark-blue px-2 rounded py-1 text-white flex items-center gap-1 text-sm"><x-icon name="arrow-left" />Back</a>
    </div>
    <div class="flex flex-col gap-4 bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <form action="{{ route('category.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            <div class="space-y-3">
                <label for="name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Nama:</span>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <label for="description" class="flex flex-col gap-3 rounded-md">
                    <span class="text-dark-blue font-semibold">Deskripsi:</span>
                    <input type="text" id="description" name="description" value="{{ old('description', $category->description) }}" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('description')
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