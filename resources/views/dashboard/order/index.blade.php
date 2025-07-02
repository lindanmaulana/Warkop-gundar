@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Daftar Pesanan</h2>
    <p class="text-dark-blue mt-1">Pantau dan kelola semua pesanan pelanggan yang masuk di Warkop.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Order</h2>
        @if(auth()->check() && auth()->user()->role->value === 'customer')
        <a href="{{ route('dashboard.orders.cart') }}" class="relative px-4">
            <x-icon name="shopping-cart" class="text-royal-blue" />
            <span class="absolute -top-3 left-4 flex items-center justify-center text-sm rounded-full bg-royal-blue size-5 text-white" id="total-cart"></span>
        </a>
        @endif
    </div>
    <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-500">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal px-2 py-4">Pelanggan</th>
                <th class="font-normal px-2 py-4">Tempat</th>
                <th class="font-normal px-2 py-4">Lokasi Antar</th>
                <th class="font-normal px-2 py-4">Total</th>
                <th class="font-normal px-2 py-4">Status</th>
                <th class="font-normal px-2 py-4">Deskripsi</th>
                <th class="font-normal px-2 py-4">Waktu</th>
                <th class="font-normal px-2 py-4"></th>
            </thead>
            <tbody>
                @if($orders->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($orders as $order)
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800">
                    <td class="px-6 py-2">{{ $no++ }}</td>
                    <td class="px-2 py-4">{{ $order->customer_name }}</td>
                    <td class="px-2 py-4">{{ $order->branch }}</td>
                    <td class="px-2 py-4">{{ $order->delivery_location }}</td>
                    <td class="px-2 py-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-2 py-4">
                        @php
                        $statusOrder = $order->status->value;
                        @endphp

                        @if($statusOrder === 'pending')
                        <p class="text-sm rounded px-2 py-1 text-center bg-yellow-600 text-white">Pending</p>
                        @elseif($statusOrder === "processing")
                        <p class="text-sm rounded px-2 py-1 text-center bg-blue-800 text-white">Processing</p>
                        @elseif($statusOrder === "done")
                        <p class="text-sm rounded px-2 py-1 text-center bg-green-800 text-white">Done</p>
                        @else
                        <p class="text-sm rounded px-2 py-1 text-center bg-red-800 text-white">Cancelled</p>
                        @endif
                    </td>
                    <td class="px-2 py-4 line-clamp-1 truncate max-w-[160px]">{{ $order->description }}
                    </td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-2 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard.orders.detail', $order->id) }}" class="text-green-500 cursor-pointer"><x-icon name="receipt-text" /></a>
                            @if(auth()->check() && auth()->user()->role->value == 'admin')
                            <a href="{{ route('dashboard.orders.update', $order->id) }}" class="text-royal-blue cursor-pointer"><x-icon name="pencil" /></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Pesanan kosong.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection


@section('script')
<script>
    let totalCart = document.getElementById('total-cart')

    let localstorageCart = localStorage.getItem('cart')
    let cart = localstorageCart ? JSON.parse(localstorageCart) : []

    if (cart.length === 0) totalCart.style.display = "none"

    totalCart.innerHTML = cart.length
</script>
@endsection