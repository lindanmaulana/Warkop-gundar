@extends('layouts.dashboard')

@section('header')
<div class="flex items-center justify-between py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <div>
        <h2 class="text-3xl font-semibold text-primary">Ringkasan Dashboard</h2>
        <p class="text-secondary mt-1">Selamat datang, {{ auth()->user()->name }}! Ini ringkasan operasional warkop hari ini.</p>
    </div>
    <div class="hidden md:flex items-center gap-3">
        <a href="{{ route('dashboard.orders', ['page' => 1, 'limit' => 5]) }}" class="relative">
            <x-icon name="bell" class="size-5 text-secondary" />
            <div data-total-order="{{ $totalOrderPending }}" class="totalOrder absolute -top-3 right-0 rounded-full size-4 flex items-center justify-center bg-red-500">
                <span class=" text-sm text-white">{{ $totalOrderPending }}</span>
            </div>
        </a>
        <h4 class="text-lg text-primary font-semibold capitalize">{{ auth()->user()->role }}</h4>
        <div class="bg-primary rounded-full size-10 flex items-center justify-center">
            <span id="initialName" data-profile-name="{{ auth()->user()->name }}" class="text-base font-bold text-white"></span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-lg font-semibold text-primary">Overview</h2>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-3 py-4 md:py-4">
        <div class="hover:-translate-y-2 transition-global duration-300 flex gap-2 max-h-[120px] bg-white border border-dark-blue/10 shadow-md p-5 rounded-md space-y-5 *:text-royal-blue">
            <div class="bg-[#E0F2FE] size-10 rounded-full flex items-center justify-center">
                <x-icon name="trending-up" class="text-[#1D4ED8]" />
            </div>
            <div>
                <h3 class="text-dark-blue/80 font-semibold text-sm">Total Hari Ini</h3>
                <p class="text-xl font-bold text-dark-blue ">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="hover:-translate-y-2 transition-global duration-300 flex gap-2 max-h-[120px] bg-white border border-dark-blue/10 shadow-md p-5 rounded-md space-y-5 *:text-royal-blue">
            <div class="bg-[#E0F7FA]/20 size-10 rounded-full flex items-center justify-center">
                <x-icon name="toolskitchen" class="text-[#0288D1]" />
            </div>
            <div>
                <h3 class="text-dark-blue/80 font-semibold text-sm">Total Menu</h3>
                <p class="text-xl font-bold text-dark-blue ">{{ $totalProducts }}</p>
            </div>
        </div>
        <div class="hover:-translate-y-2 transition-global duration-300 flex gap-2 max-h-[120px] bg-white border border-dark-blue/10 shadow-md p-5 rounded-md space-y-5 *:text-royal-blue">
            <div class="bg-[#ECEFF1] size-10 rounded-full flex items-center justify-center">
                <x-icon name="shopping-cart" class="text-[#37474F]" />
            </div>
            <div>
                <h3 class="text-dark-blue/80 font-semibold text-sm">Total Pesanan</h3>
                <p class="text-xl font-bold text-dark-blue ">{{ $totalOrders }}</p>
            </div>
        </div>
        <div class="hover:-translate-y-2 transition-global duration-300 flex gap-2 max-h-[120px] bg-white border border-dark-blue/10 shadow-md p-5 rounded-md space-y-5 *:text-royal-blue">
            <div class="bg-[#E8F5E9] size-10 rounded-full flex items-center justify-center">
                <x-icon name="transaction" class="text-[#2E7D32]" />
            </div>
            <div>
                <h3 class="text-dark-blue/80 font-semibold text-sm">Total Transaksi</h3>
                <p class="text-xl font-bold text-dark-blue ">{{ $totalTransactions }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow-md p-5 rounded">
    <h2 class="text-xl font-semibold text-primary">Pesanan Terbaru</h2>

    <div class="overflow-x-auto py-8 min-w-full">
        <table class="w-full">
            <thead class="*:text-xs text-gray-400 uppercase">
                <th class="font-medium py-4">No</th>
                <th class="font-medium py-4">Pelanggan</th>
                <th class="font-medium py-4">Tempat</th>
                <th class="font-medium py-4">Lokasi Antar</th>
                <th class="font-medium py-4">Total</th>
                <th class="font-medium py-4">Status</th>
                <th class="font-medium py-4">Waktu</th>
            </thead>
            @if($latestOrdersData->isNotEmpty())
            <?php $no = 1; ?>
            @foreach($latestOrdersData as $order)
            <tr class="border-b border-dark-blue/20 hover:bg-dark-blue/20 *:text-sm">
                <td class="text-gray-500 px-6 py-2">{{ $no++ }}</td>
                <td class="text-gray-500 px-2 py-3">{{ $order->user->name }}</td>
                <td class="text-gray-500 px-2 py-3">{{ $order->branch }}</td>
                <td class="text-gray-500 px-2 py-3">{{ $order->delivery_location }}</td>
                <td class="text-gray-500 px-2 py-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td class="text-gray-500 px-2 py-3">
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
                <td class="text-gray-500">
                    <?php if ($order->created_at): ?>
                        {{ $order->created_at->format('d M Y H:i') }}
                    <?php else: ?>
                        - <?php endif; ?>
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
    <a href="{{ route('dashboard.orders', ['page' => 1, 'limit' => 5]) }}" class="flex items-center justify-end text-sm text-primary">Lihat semua pesanan -></a>
</div>
@endsection

@section('script')
<script>
    const showInitialNameUser = (spanElement) => {
        const inisial = document.getElementById('initialName')
        const username = inisial.dataset.profileName;

        inisial.innerHTML = username.slice(0, 1)
    }


    const showTotalOrder = () => {
        const totalOrder = document.querySelector(".totalOrder")
        const totalOrderView = totalOrder.dataset.totalOrder;

        if (Number(totalOrderView) === 0) totalOrder.style.display = "none"
    }

    showTotalOrder()
    showInitialNameUser()
</script>
@endsection