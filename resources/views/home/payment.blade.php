@extends('layouts.home')


@section('content')
<section class="mt-24">
    <div class="container max-w-4xl mx-auto min-h-[400px]">
        <div class="flex items-center justify-between border-b border-black/10 pb-4">
            <h2 class="text-2xl font-semibold text-secondary">Pembayaran</h2>
            <a href="{{ route('home.order') }}" class="text-sm font-semibold bg-secondary text-white px-4 py-1 rounded">Kembali</a>
        </div>

        <div class="w-full h-full grid grid-cols-2 gap-4 py-10">
            <div class="h-full bg-peach shadow rounded p-6 space-y-6">
                <label for="" class="block rounded">
                    <span class="block text-lg text-secondary font-semibold">Metode Pembayaran</span>
                    <input type="text" value="{{ old('payment_id', $order->payment->name) }}" class="bg-green-500 text-white border-none outline-none rounded p-2" readonly>
                    <input type="text" id="paymentMethod" value="{{ old('payment_id', $order->payment->qr_code_url) }}" hidden>
                </label>

                <figure class="min-h-62 flex items-center justify-center" id="selectedPaymentImage">

                </figure>
            </div>
            <div class="h-full">
                <form action="{{ route('upload.payment', $order->id) }}" class="space-y-6" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($paymentProof)
                    <span class="block text-secondary font-semibold mb-4">Bukti Pembayaran</span>
                    <p class="bg-green-500 p-2 text-white rounded">Pesanan mu sudah di proses</p>
                    @else
                    <div class="flex items-center">
                        <input type="text" name="order_id" value="{{ old('order_id', $order->id) }}" hidden>
                        <div id="image-preview" class="relative w-full min-h-80 flex items-center justify-center rounded-lg overflow-hidden">
                            <label for="image_url" class="absolute inset-0 flex items-center justify-center cursor-pointer text-center z-10 group"></label>
                            <span class="block text-secondary font-semibold mb-4">Bukti Pembayaran</span>
                            <figure class="rounded-md overflow-hidden h-80">
                                <img src="/images/qrcode-default.png" id="image-product" alt="" class="w-full h-full object-contain">
                            </figure>
                        </div>

                        <div id="image-upload" class="w-full h-full self-end space-y-2">
                            <span class="block text-dark-blue font-semibold">Upload Bukti Pembayaran </span>
                            <label for="image_url" class="border-2 rounded-lg border-gray-200 flex items-center justify-center gap-1 cursor-pointer bg-peach h-[200px]">
                                <x-icon name="image-up" class="text-green-500" />
                                <span class="text-gray-500">Upload Image Here</span>
                                @error('image_url')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <input type="file" name="image_url" id="image_url" accept="image/*" class="hidden">
                    </div>
                    <button type="submit" class="bg-green-500 w-full py-2 rounded text-white">Kirim Bukti Pembayaran</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
@parent
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

    const paymentMethodSelect = document.getElementById('paymentMethod');
    const displayDiv = document.getElementById('selectedPaymentImage');

    function updateQrCodeImage() {
        const selectedImageUrl = paymentMethodSelect.value;

        if (displayDiv) {
            console.log({
                selectedImageUrl
            })
            const img = document.createElement('img');
            const imageUrl = `/storage/${selectedImageUrl}`;
            img.src = imageUrl
            img.alt = 'Qr Code'
            img.classList.add('max-w-[400px]')
            displayDiv.innerHTML = '';
            displayDiv.appendChild(img);
        }
    }

    document.addEventListener('DOMContentLoaded', updateQrCodeImage);

    paymentMethodSelect.addEventListener('change', updateQrCodeImage);
</script>
@endsection