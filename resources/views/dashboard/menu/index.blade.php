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
    <div class="bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <h2 class="text-lg font-semibold text-dark-blue">Menu</h2>
        <div class="flex items-center justify-between gap-6 py-4">
            <ul class="flex items-center gap-4">
                @foreach($categories as $category)
                <li class="rounded-md px-4 py-1 {{ $isActive('dashboard.menu') }}">
                    <a href="{{ route('dashboard.menu.product', $category->id) }}" class="flex items-center gap-4 text-white ">{{ $category->name }}</a>
                </li>
                @endforeach
            </ul>
            @if(auth()->check() && auth()->user()->role->value === 'admin')
            <a href="{{ route('dashboard.menu.product.create') }}" class="flex items-center rounded px-3 py-1 text-white bg-dark-blue hover:bg-dark-blue/70 cursor-pointer">
                Tambah
            </a>
            @endif
        </div>
    </div>
    <div class="bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
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
                        @if(auth()->check() && auth()->user()->role->value === 'admin')
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('dashboard.menu.product.update', $product->id) }}" class="text-royal-blue  cursor-pointer"><x-icon name="pencil" class="size-5" /></a>
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
                    <td colspan="4" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Data Product tidak tersedia.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection