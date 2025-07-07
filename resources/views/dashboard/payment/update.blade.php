@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Tambah Jenis Pembayaran</h2>
    <p class="text-dark-blue mt-1">Masukan metode pembayaran yang tersedia, lengkap dengan gambar QR Code.</p>
</div>
@endsection


@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Pembayaran</h2>
        <a href="{{ route('dashboard.payments') }}" class="bg-dark-blue hover:bg-dark-blue/70 px-3 rounded py-1 text-white flex items-center gap-1 text-sm"><x-icon name="arrow-left" />Back</a>
    </div>
    <div class="flex flex-col gap-4 bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('patch')
            <div class="space-y-3">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2 space-y-4">
                        <label for="name" class="flex flex-col gap-3">
                            <span class="text-dark-blue font-semibold">Nama:</span>
                            <input type="text" id="name" name="name" value="{{ old('name', $payment->name) }}" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                        </label>
                        @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror

                        <div>
                            <h4 class="text-dark-blue font-semibold mb-3">Status</h4>
                            <label for="qr-active">
                                <input type="radio" id="qr-active" name="is_active" value="1" {{ old('is_active', $payment->is_active) == 1 ? 'checked' : '' }}>
                                <span class="text-white bg-royal-blue border px-2 py-px rounded uppercase text-sm">Active</span>
                            </label>

                            <label for="qr-inactive">
                                <input type="radio" id="qr-inactive" name="is_active" value="0" {{ old('is_active', $payment->is_active) == 0 ? 'checked' : '' }}>
                                <span class="text-white bg-red-500 border px-2 py-px rounded uppercase text-sm">InActive</span>
                            </label>
                        </div>
                    </div>


                    <div class="flex items-center">
                        <div id="image-preview" class="relative w-full max-h-54 flex items-center justify-center border-2 rounded-lg border-gray-200 overflow-hidden">
                            <label for="image_url" class="absolute inset-0 flex items-center justify-center cursor-pointer text-center z-10 group"></label>
                            <figure class="w-full h-full rounded-md overflow-hidden scale-80">
                                <img src="/images/qrcode-default.png" id="image-payment-preview" alt="default" class="w-full h-full object-cover">
                            </figure>
                        </div>
                        @if($payment->qr_code_url)
                        <div id="image-qrcode" class="relative w-full max-h-54 flex items-center justify-center border-2 rounded-lg border-gray-200 overflow-hidden">
                            <label for="image_url" class="absolute inset-0 flex items-center justify-center cursor-pointer z-10"></label>
                            <figure class="w-full h-full rounded-md overflow-hidden scale-80">
                                <img src="{{ asset('storage/'. $payment->qr_code_url) }}" alt="{{ $payment->name }}" class="w-full h-full object-cover">
                            </figure>
                        </div>
                        @else
                        <div id="image-upload" class="w-full h-full self-end space-y-2">
                            <span class="block text-dark-blue font-semibold">Attachment</span>
                            <label for="image_url" class="border-2 rounded-lg border-gray-200 flex items-center justify-center gap-1 cursor-pointer bg-peach w-full h-full">
                                <x-icon name="image-up" class="text-green-500" />
                                <span class="text-gray-500">Upload Image Here</span>
                                @error('image_url')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        @endif
                        <input type="file" name="qr_code_url" id="image_url" accept="image/*" class="hidden">
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-3 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Simpan</button>
                <button type="reset" class="px-3 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('script')
<script>
    const imagePreview = document.getElementById('image-preview')
    const imageQrCode = document.getElementById('image-qrcode')
    const imageUpload = document.getElementById('image-upload')

    imagePreview.style.display = "none"

    const imageUrl = document.getElementById('image_url').addEventListener('change', function(event) {
        const imageProduct = document.getElementById("image-payment-preview")

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

        if (imageQrCode) imageQrCode.style.display = "none"

        imagePreview.style.display = "block"
        imageUpload.style.display = "none"

    })
</script>
@endsection