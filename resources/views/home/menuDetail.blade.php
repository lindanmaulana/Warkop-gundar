@extends('layouts.home')


@section('content')
<section class="mt-24">
    <div class="container max-w-6xl mx-auto px-4 lg:px-0">
        <div class="">
            <h2 class="text-3xl font-bold text-primary">Detail</h2>
        </div>
        <div class="flex items-center justify-between">
            <h3 class="text-2xl text-dark-blue font-semibold">{{ $product->name }}</h3>
            <a href="{{ route('home.menu', ['page' => 1, 'limit' => 5]) }}" class="flex items-center gap-2 text-sm text-secondary hover:text-white border hover:bg-secondary transition-global p-2 rounded">
                <x-icon name="arrow-left" /> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8">
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
                    <span class="block text-dark-blue font-semibold">Nama Produk</span>
                    <input type="text" value="{{ old('name', $product->name) }}" id="name" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                </label>
                <label for="name" class="block space-y-2">
                    <span class="block text-dark-blue font-semibold">Kategori</span>
                    <input type="text" value="{{ old('name', $product->category->name) }}" id="name" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label for="name" class="block space-y-2">
                        <span class="block text-dark-blue font-semibold">Harga</span>
                        <input type="text" value="{{ old('name', number_format($product->price, 0, ',', '.')) }}" id="name" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                    </label>
                    <label for="name" class="block space-y-2">
                        <span class="block text-dark-blue font-semibold">Stok</span>
                        <input type="text" value="{{ old('name', $product->stock) }}" id="name" class="w-full px-4 border border-dark-blue/30 py-2 rounded-lg" readonly>
                    </label>
                </div>
                <label for="name" class="block space-y-2">
                    <span class="block text-dark-blue font-semibold">Deskripsi</span>
                    <textarea id="name" class="w-full px-4 text-left border border-dark-blue/30 py-2 rounded-lg" readonly>
                    {{ old('description', $product->description) }}
                    </textarea>
                </label>
            </div>
        </div>
    </div>
</section>
@endsection