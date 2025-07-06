@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Tambah Menu</h2>
    <p class="text-dark-blue mt-1">Masukkan item menu baru seperti Kopi Susu, Indomie Goreng, dll</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Menu</h2>
        <a href="{{ route('dashboard.menu.products') }}" class="bg-dark-blue hover:bg-dark-blue/70 px-3 rounded py-1 text-white flex items-center gap-1 text-sm"><x-icon name="arrow-left" />Back</a>
    </div>
    <div class="flex flex-col gap-4 bg-white px-2 py-6 rounded-lg shadow-sm shadow-dark-blue/10">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="space-y-3">
                <label for="name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Nama:</span>
                    <input type="text" id="name" name="name" placeholder="Kopi susu..." class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
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

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label for="price" class="flex flex-col gap-3">
                            <span class="text-dark-blue font-semibold">Harga:</span>
                            <input type="number" id="price" name="price" placeholder="80xxx" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                        </label>
                        @error('price')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror

                        <label for="stock" class="flex flex-col gap-3">
                            <span class="text-dark-blue font-semibold">Stock:</span>
                            <input type="number" id="stock" name="stock" placeholder="4xxx" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                        </label>
                        @error('stock')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror

                        <label for="description" class="flex flex-col gap-3 rounded-md">
                            <span class="text-dark-blue font-semibold">Deskripsi: (Optional)</span>
                            <input type="text" id="description" name="description" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                        </label>
                        @error('description')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div id="image-preview" class="relative w-full max-h-54 flex items-center justify-center border-2 rounded-lg border-gray-200">
                            <label for="image_url" class="absolute inset-0 flex items-center justify-center cursor-pointer text-center group"></label>
                            <figure class="w-full h-full rounded-md overflow-hidden">
                                <img src="" id="image-product" alt="" class="w-full h-full object-cover">
                            </figure>
                        </div>

                        <div id="image-upload" class="w-full h-full self-end space-y-2">
                            <span class="block text-dark-blue font-semibold">Attachment</span>
                            <label for="image_url" class="border-2 rounded-lg border-gray-200 flex items-center justify-center gap-1 cursor-pointer bg-peach w-full h-[85%]">
                                <x-icon name="image-up" class="text-green-500" />
                                <span class="text-gray-500">Upload Image Here</span>
                                @error('image_url')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <input type="file" name="image_url" id="image_url" accept="image/*" class="hidden">
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Simpan</button>
                <button type="reset" class="px-4 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('script')
<script>
    const imagePreview = document.getElementById('image-preview')
    const imageUpload = document.getElementById('image-upload')

    imagePreview.style.display = "none"

    const imageUrl = document.getElementById('image_url').addEventListener('change', function(event) {
        const imageProduct = document.getElementById("image-product")

        console.log({
            event
        })

        if (event.target.files && event.target.files[0]) {
            const selectedFile = event.target.files[0]

            if (selectedFile.type.startsWith('image/')) {
                const objUrl = URL.createObjectURL(selectedFile)
                imageProduct.src = objUrl


                imagePreview.onload = () => {
                    URL.revokeObjectURL(objectUrl);
                };
            }
        }

        imagePreview.style.display = "block"
        imageUpload.style.display = "none"
    })
</script>
@endsection