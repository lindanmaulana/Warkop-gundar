@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <h2 class="text-3xl font-semibold text-primary">Detail Menu</h2>
    <p class="text-secondary mt-1 max-w-96">Berikut adalah rincian dari menu yang telah dipilih, termasuk harga, deskripsi, dan status ketersediaan.</p>
</div>
@endsection


@section('content')
<section>
    <div class="container max-w-6xl mx-auto pl-4">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl text-primary font-semibold">{{ $product->name }}</h3>
            <a href="{{ route('dashboard.menu.products', ['page' => 1, 'limit' => 5]) }}" class="flex items-center gap-2 text-sm bg-dark-blue hover:bg-dark-blue/80 text-white p-2 rounded">
                <x-icon name="arrow-left" /> Kembali
            </a>
        </div>

        <div class="flex gap-8 py-8">
            <div>
                @if($product->image_url)
                <figure class="w-full h-100 rounded overflow-hidden group cursor-pointer shadow-md">
                    <img src="{{ asset('storage/'. $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-125 transition-global">
                </figure>
                @else
                <img src="/images/image-placeholder.png" alt="">
                @endif
            </div>

            <div class="bg-white w-full p-6 rounded-lg shadow-md space-y-3">
                <label for="name" class="block space-y-2">
                    <span class="block text-secondary font-semibold">Nama Produk</span>
                    <input type="text" value="{{ old('name', $product->name) }}" id="name" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                </label>
                <label for="category" class="block space-y-2">
                    <span class="block text-secondary font-semibold">Kategori</span>
                    <input type="text" value="{{ old('name', $product->category->name) }}" id="category" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label for="price" class="block space-y-2">
                        <span class="block text-secondary font-semibold">Harga</span>
                        <input type="text" value="{{ old('name', number_format($product->price, 0, ',', '.')) }}" id="price" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                    </label>
                    <label for="stock" class="block space-y-2">
                        <span class="block text-secondary font-semibold">Stok</span>
                        <input type="text" value="{{ old('name', $product->stock) }}" id="stock" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                    </label>
                </div>
                <label for="description" class="block space-y-2">
                    <span class="block text-secondary font-semibold">Deskripsi</span>
                    <textarea id="description" class="w-full px-4 text-left border border-dark-blue/30 py-2 rounded-lg" readonly>
                        {{ old('description', $product->description) }}
                    </textarea>
                </label>
            </div>
        </div>
    </div>
</section>
@endsection