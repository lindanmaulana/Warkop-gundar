@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Edit Menu</h2>
    <p class="text-dark-blue mt-1">Perbarui informasi menu seperti nama, harga, stok, dan kategori.</p>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-semibold text-royal-blue">Form Edit Produk</h3>
        <a href="{{ route('dashboard.menu.products', ['page' => 1, 'limit' => 5]) }}" class="flex items-center gap-2 text-sm bg-dark-blue hover:bg-dark-blue/80 text-white px-3 py-1 rounded">
            <x-icon name="arrow-left" /> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md shadow-dark-blue/10">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-dark-blue mb-1">Nama Produk</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-dark-blue mb-1">Kategori</label>
                        <select id="category" name="category_id" class="w-full border border-dark-blue/20 px-4 py-2 rounded bg-white">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-semibold text-dark-blue mb-1">Harga</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded">
                            @error('price')
                            <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-dark-blue mb-1">Stok</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded">
                            @error('stock')
                            <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-dark-blue mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="w-full border border-dark-blue/20 px-4 py-2 rounded">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-3">
                    @if($product->image_url)
                    <div id="image-product-preview" class="relative w-full h-56 border rounded overflow-hidden">
                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    @endif

                    <div id="image-preview" class="hidden relative w-full h-56 border-2 border-dashed rounded overflow-hidden">
                        <img id="image-product" src="/images/image-placeholder.png" alt="Preview" class="w-full h-full object-contain">
                    </div>

                    <label for="image_url" class="block w-full text-sm font-semibold text-dark-blue">Upload Gambar Baru</label>
                    <label for="image_url" class="w-full cursor-pointer border-2 border-gray-200 rounded flex items-center justify-center h-12 bg-peach hover:border-royal-blue">
                        <x-icon name="image-up" class="text-green-500" />
                        <span class="ml-2 text-gray-700">Pilih Gambar</span>
                    </label>
                    <input type="file" name="image_url" id="image_url" accept="image/*" class="hidden">
                    @error('image_url')
                    <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" class="bg-royal-blue hover:bg-royal-blue/90 text-white font-semibold px-4 py-2 rounded text-sm">
                    Simpan
                </button>
                <button type="reset" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded text-sm">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    const imageInput = document.getElementById('image_url');
    const imageProduct = document.getElementById('image-product');
    const imagePreview = document.getElementById('image-preview');
    const imageUpload = document.getElementById('image-upload');
    const imageProductPreview = document.getElementById('image-product-preview');

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const objectUrl = URL.createObjectURL(file);
            imageProduct.src = objectUrl;
            imagePreview.style.display = "block";
            if (imageProductPreview) imageProductPreview.style.display = "none";
        }
    });
</script>
@endsection