@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
    <a href="{{ route('dashboard.menu.products') }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded"><x-icon name="arrow-left" /> Back</a>
</div>
@endsection

@section('content')
<h3 class="mb-5 text-xl text-dark-blue font-semibold">Daftar {{ $category->name }}</h3>
<div class="overflow-x-auto bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
    <table class="w-full text-left rounded-md md:overflow-hidden">
        <thead class="*:text-gray-500">
            <th class="py-4 px-6 font-normal">No</th>
            <th class="py-4 px-2 font-normal">Nama</th>
            <th class="py-4 px-2 font-normal">Kategori</th>
            <th class="py-4 px-2 font-normal">Harga</th>
            <th class="py-4 px-2 font-normal">Stok</th>
            <th class="py-4 px-2 font-normal">Deskripsi</th>
            <th class="py-4 px-6 font-normal"></th>
        </thead>
        <tbody>
            @if($products->isNotEmpty())
            <?php $no = 1; ?>
            @foreach($products as $product)
            <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800">
                <td class="py-4 px-6">{{ $no++ }}</td>
                <td class="py-4 px-2">{{ $product->name }}</td>
                <td class="py-4 px-2">{{ $product->category->name }}</td>
                <td class="py-4 px-2">{{ $product->price }}</td>
                <td class="py-4 px-2">{{ $product->stock }}</td>
                <td class="py-4 px-2">{{ $product->description }}</td>
                <td class="py-4 px-6">
                    @if(auth()->check() && auth()->user()->role->value === 'admin')
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('dashboard.menu.products.edit', $product->id) }}" class="text-royal-blue  cursor-pointer"><x-icon name="pencil" class="size-5" /></a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 cursor-pointer"><x-icon name="trash" class="size-5" /></button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="text-center py-4 text-red-500">
                    <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Menu {{ $category->name }} tidak tersedia.</p>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection