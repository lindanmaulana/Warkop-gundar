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
<h3 class="mb-5 text-xl text-dark-blue font-semibold">Daftar {{ $category->name }}</h3>
<table class=" w-full text-left rounded-md overflow-hidden">
    <thead class="bg-royal-blue hover:bg-royal-blue/70 text-white">
        <th class="p-2">No</th>
        <th class="p-2">Nama</th>
        <th class="p-2">Kategori</th>
        <th class="p-2">Harga</th>
        <th class="p-2">Stok</th>
        <th class="p-2">Deskripsi</th>
        <th class="p-2"></th>
    </thead>
    <tbody>
        @if($products->isNotEmpty())
        <?php $no = 1; ?>
        @foreach($products as $product)
        <tr class="border-b border-dark-blue/20 hover:bg-dark-blue/20">
            <td class="p-2">{{ $no++ }}</td>
            <td class="p-2">{{ $product->name }}</td>
            <td class="p-2">{{ $product->category->name }}</td>
            <td class="p-2">{{ $product->price }}</td>
            <td class="p-2">{{ $product->stock }}</td>
            <td class="p-2">{{ $product->description }}</td>
            <td class="p-2">

            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="4" class="text-center py-4 text-red-500">
                <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Menu {{ $category->name }} tidak tersedia.</p>
            </td>
        </tr>
        @endif
    </tbody>
</table>
@endsection