@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Edit Jenis Pembayaran</h2>
    <p class="text-dark-blue mt-1">Perbarui informasi metode pembayaran dan QR Code-nya.</p>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-royal-blue">Form Edit Pembayaran</h2>
        <a href="{{ route('dashboard.payments') }}" class="flex items-center gap-2 text-sm bg-dark-blue hover:bg-dark-blue/80 text-white px-3 py-1 rounded">
            <x-icon name="arrow-left" /> Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md shadow-dark-blue/10">
        <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-dark-blue mb-1">Nama Metode Pembayaran</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $payment->name) }}" class="w-full border border-dark-blue/30 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-royal-blue">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="block text-sm font-semibold text-dark-blue mb-2">Status</p>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="is_active" value="1" {{ old('is_active', $payment->is_active) == 1 ? 'checked' : '' }}>
                                <span class="text-sm text-white bg-royal-blue px-3 py-1 rounded">Aktif</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="is_active" value="0" {{ old('is_active', $payment->is_active) == 0 ? 'checked' : '' }}>
                                <span class="text-sm text-white bg-red-500 px-3 py-1 rounded">Tidak Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-sm font-semibold text-dark-blue">QR Code</p>

                    @if($payment->qr_code_url)
                    <div id="image-old" class="w-full h-48 rounded-md overflow-hidden border border-gray-300 relative">
                        <img src="{{ asset('storage/' . $payment->qr_code_url) }}" alt="{{ $payment->name }}" class="w-full h-full object-cover">
                        <span class="absolute top-1 left-1 bg-royal-blue text-white text-xs px-2 py-0.5 rounded">Saat Ini</span>
                    </div>
                    @endif

                    <div id="image-preview" class="hidden w-full h-48 rounded-md overflow-hidden border-2 border-dashed border-gray-400">
                        <img id="image-payment-preview" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>

                    <label for="image_url" class="cursor-pointer bg-peach border border-gray-300 hover:border-royal-blue flex items-center justify-center gap-2 px-4 py-3 rounded-md text-gray-700 text-sm">
                        <x-icon name="image-up" class="text-green-500" />
                        Upload Gambar QR Baru
                    </label>

                    <input type="file" name="qr_code_url" id="image_url" accept="image/*" class="hidden">
                    @error('qr_code_url')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">
                <button type="submit" class="bg-royal-blue hover:bg-royal-blue/90 text-white font-semibold px-4 py-2 rounded">Simpan</button>
                <button type="reset" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    const imagePreview = document.getElementById('image-preview');
    const imageTag = document.getElementById('image-payment-preview');
    const imageInput = document.getElementById('image_url');
    const oldImage = document.getElementById('image-old');

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const imageUrl = URL.createObjectURL(file);
            imageTag.src = imageUrl;
            imagePreview.style.display = "block";
            if (oldImage) oldImage.style.display = "none";
        }
    });
</script>
@endsection