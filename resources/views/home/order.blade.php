@extends('layouts.home')

@section('content')
<section class="pt-28 pb-20">
    <div class="container max-w-6xl mx-auto px-4 lg:px-0">
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-black/10 pb-6">
                <h2 class="text-2xl font-semibold text-secondary">Pesanan</h2>
                <p id="totalItems" class="text-xl font-semibold text-secondary"></p>
            </div>

            @if(session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <p class="text-green-700 ">
                    <strong class="bold">Success!</strong> {{session('success')}}
                </p>
            </div>
            @endif

            <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
                <table class="w-full text-left rounded-md overflow-hidden">
                    <thead class="*:text-gray-500 bg-gray-300">
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
                            <td class="px-2 py-4">{{ $order->user->name }}</td>
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
                            <td class="px-2 py-4 line-clamp-1 truncate max-w-[160px]">{{ $order->description }}</td>
                            <td>
                                <p class="line-clamp-1">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </td>
                            <td class="px-2 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('home.order.detail', $order->id) }}" class="text-green-500 cursor-pointer"><x-icon name="receipt-text" /></a>
                                    @if($order->status->value == "pending")
                                    <a href="{{ route('home.transaction', $order->id) }}" class="bg-secondary text-white px-2 py-1 text-sm rounded font-semibold cursor-pointer">Pembayaran</a>
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
    </div>
</section>
@endsection


@section('script')
@parent
<script>
    const componentTotalItems = document.getElementById('totalItems')

    const showMappingCart = () => {
        let btnCompleteOrder = document.getElementById('btn-complete-order') //button complete disable saat cart kosong
        const tableBody = document.getElementById('table-body')
        tableBody.innerHTML = ""

        if (cart.length === 0) {
            const row = document.createElement('tr')
            row.classList.add('border-b', 'border-dark-blue/20', 'hover:bg-dark-blue/20');

            btnCompleteOrder.style.display = "none"

            row.innerHTML = `
                <td colspan="6" class="py-4">
                    <div class="flex items-center justify-center gap-3 *:text-red-500">
                        <x-icon name="shopping-cart" />
                        <p>Keranjang kosong!</p>
                    </div>
                </td>
            `
            showTotalPrice()

            tableBody.appendChild(row)

            return;
        }


        const storageBaseUrl = "http://localhost:8000/storage/"
        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-dark-blue/20', 'hover:bg-dark-blue/20');

            const imageUrl = item.image_url ? storageBaseUrl + item.image_url : '/images/image-placeholder.png';

            row.innerHTML = `
                        <tr class="border-b border-dark-blue/20 hover:bg-dark-blue/20">
                            <td class="py-2 px-6">${index + 1}</td>
                            <td class="py-2 ">
                                <div class="flex gap-4">
                                    <figure class="w-40 h-28">
                                        <img src="${imageUrl}" class="w-full h-full object-cover" />
                                    </figure>
                                    <div class="flex flex-col items-start justify-center gap-1">
                                        <h4 class="text-lg font-semibold">${item.productName}</h4>
                                        <bold class="block text-green-500 text-sm">${item.category}</bold>
                                        <button onclick="handleDeleteCart(${item.productId})" class="cursor-pointer text-sm text-secondary/60 hover:text-red-500 transition-global">remove</button>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 ">
                                <div class="flex items-center gap-1">
                                    <button onclick="{handleQty(this, 'dec')}" class="${item.qty === 1 && "hidden"} cursor-pointer text-2xl" id="btn-decrement"  data-item-user-id="${item.userId}" data-item-product-id="${item.productId}" data-item-qty="${item.qty}" data-item-product-price="${item.price}" data-item-product-total-price="${item.totalPrice}">-</button>
                                    <div class="border border-black/20 rounded w-8 h-6 flex items-center justify-center">
                                        <span class="text-base text-secondary/70 font-semibold">${item.qty}</span>
                                    </div>
                                    <button onclick="{handleQty(this, 'inc')}" class="cursor-pointer text-2xl" id="btn-increment"  data-item-user-id="${item.userId}" data-item-product-id="${item.productId}" data-item-qty="${item.qty}" data-item-product-price="${item.price}" data-item-product-total-price="${item.totalPrice}">+</button>
                                </div>
                            </td>
                            <td class="py-2 ">${item.price}</td>
                            <td class="py-2 ">${item.totalPrice}</td>
                        </tr>
                    `

            tableBody.appendChild(row)
        })

        showTotalPrice()
    }
</script>
@endsection