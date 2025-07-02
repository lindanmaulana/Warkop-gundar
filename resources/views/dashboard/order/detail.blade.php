@extends('layouts.dashboard')


@section('content')
<div class="space-y-4 pb-10 md:pb-0">
    <div class="w-full min-h-[200px] flex items-center justify-between bg-royal-blue px-6 md:px-12 -mb-14">
        <h2 class="text-lg font-semibold text-white">Order Detail</h2>
        <a href="{{ route('dashboard.orders') }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded"><x-icon name="arrow-left" /> Back</a>
    </div>
    <div class="relative max-w-[90%] mx-auto bg-white px-2 py-6 rounded shadow-sm shadow-dark-blue/10">
        <div class="absolute translate-x-1/2 right-1/2 -top-9 size-16 flex items-center justify-center rounded-full bg-royal-blue border-2 border-white">
            <x-icon name="coffee" class="size-10 text-white" />
        </div>
        <h3 class="text-center text-3xl font-bold text-dark-blue">Warkop Gundar</h3>
        <p id="order-status" class="text-sm text-center py-4" data-order-status="{{ $order->status }}"></p>

        <div class="px-4 md:px-0 md:max-w-2/3 mx-auto py-10 space-y-4">
            <ul class="w-full space-y-2 pb-4 border-b border-dark-blue/20">
                <li class="flex items-center justify-between">
                    <h4 class="font-semibold">Tanggal</h4>
                    <p class="text-dark-blue/60">{{ $order->created_at->format('d M Y H:i') }}</p>
                </li>
                <li class="flex items-center justify-between">
                    <h4 class="font-semibold">Lokasi Warkop</h4>
                    <p class="text-dark-blue/60">{{ $order->branch }}</p>
                </li>
                <li class="flex items-center justify-between">
                    <h4 class="font-semibold">Lokasi Antar </h4>
                    <p class="text-dark-blue/60">{{ $order->delivery_location }}</p>
                </li>
            </ul>

            <ul class="w-full space-y-2 pb-4 border-b border-dark-blue/20">
                @foreach($order->orderItems as $item)
                <li class="flex items-center justify-between">
                    <h4 class="font-semibold">{{ $item->product->name }} <span class="text-royal-blue">{{ $item->qty }}x</span></h4>
                    <p class="text-dark-blue/60">Rp{{ number_format($item->product->price, 0, ',', '.')  }}/<span class="text-xs">pcs</span></p>
                </li>
                @endforeach
            </ul>
            <ul class="w-full space-y-2 pb-4 border-b border-dark-blue/20">
                <li class="flex flex-col items-center justify-between">
                    <h4 class="font-semibold">Deskripsi</h4>
                    <p class="text-dark-blue/60">{{ $order->description}}</p>
                </li>
            </ul>
            <ul>
                <li class="flex items-center justify-between">
                    <h4 class="font-semibold text-xl">Total</h4>
                    <p class="text-dark-blue font-semibold text-xl">Rp{{ number_format($order->total_price, 0, ',', '.')  }}</p>
                </li>
            </ul>
        </div>
    </div>
    @if(auth()->check() && auth()->user()->role->value === 'customer')
    <div class="relative max-w-[90%] mx-auto bg-white px-2 py-6 rounded shadow-sm shadow-dark-blue/10 text-center">
        @php
        $statusValue = $order->status->value;
        @endphp

        @if($statusValue === 'pending')
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Terima Kasih, Pesananmu Sudah Masuk!</h3>
        <p class="text-gray-600">Pesananmu sedang menunggu antrean untuk kami proses.</p>
        <p class="text-sm text-gray-500 mt-2">Mohon tunggu konfirmasi dari kami ya.</p>

        @elseif($statusValue === 'processing')
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Pesananmu Sedang Diproses!</h3>
        <p class="text-gray-600">Kami sedang meracik pesananmu dengan sepenuh hati. Sebentar lagi siap dinikmati!</p>
        <p class="text-sm text-gray-500 mt-2">Tetap pantau statusnya ya.</p>

        @elseif($statusValue === 'done')
        <h3 class="text-xl font-semibold text-green-700 mb-2">Pesananmu Sudah Selesai!</h3>
        <p class="text-gray-600">Terima kasih telah berbelanja di Warkop {{ $order->branch }}. Semoga puas dengan pesananmu!</p>
        <p class="text-sm text-gray-500 mt-2">Kami tunggu orderan selanjutnya ya, Kak!</p>

        @elseif($statusValue === 'cancelled')
        <h3 class="text-xl font-semibold text-red-700 mb-2">Pesanan Dibatalkan.</h3>
        <p class="text-gray-600">Mohon maaf, pesanan ini telah dibatalkan.</p>
        <p class="text-sm text-gray-500 mt-2">Jika ada pertanyaan, silakan hubungi kami.</p>

        @else
        {{-- Status default atau tidak dikenal --}}
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Terima Kasih atas Pesanan Anda!</h3>
        <p class="text-gray-600">Status pesanan Anda saat ini adalah: {{ $order->status->label() }}.</p>
        @endif
    </div>
    @endif
</div>
@endsection


@section('script')
<script>
    const showOrderStatus = () => {
        const orderStatus = document.getElementById('order-status');
        const status = orderStatus.dataset.orderStatus

        const span = document.createElement('span')
        span.classList.add(status === "pending" ? "bg-yellow-600" : status === "processing" ? "bg-blue-800" : status === "done" ? "bg-green-800" : "bg-red-800", "rounded", "px-2", "py-1", "text-white")
        span.innerHTML = status

        orderStatus.appendChild(span)
    }

    showOrderStatus()
</script>
@endsection