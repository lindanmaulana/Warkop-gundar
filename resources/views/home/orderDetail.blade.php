@extends('layouts.home')


@section('content')
<section class="mt-20">
    <div class="container max-w-6xl mx-auto px-4 lg:px-0">
        <div class="space-y-12 lg:space-y-8 py-8">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-secondary">Detail Pesanan</h2>
                <a href="{{ route('home.order') }}" class="bg-secondary px-4 rounded text-white text-sm py-1">Back</a>
            </div>
            <div class="relative md:max-w-1/2 mx-auto bg-peach px-2 py-6 rounded shadow-sm shadow-dark-blue/10">
                <div class="absolute translate-x-1/2 right-1/2 -top-9 size-16 flex items-center justify-center rounded-full bg-primary border-2 border-white">
                    <x-icon name="coffee" class="size-10 text-white" />
                </div>
                <h3 class="text-center text-3xl font-bold text-dark-blue">Warkop Gundar</h3>
                <div class="flex flex-col items-center justify-center">
                    <p id="order-status" class="text-sm text-center py-4" data-order-status="{{ $order->status }}"></p>
                    @if($order->status->value === "pending" && empty($paymentProofs))
                    <form onsubmit="confirmCancelOrder(event)">
                        <button type="submit" data-order-id="{{ $order->id }}" class="text-red-500 cursor-pointer font-semibold">Batalkan Pesanan!</button>
                    </form>
                    @endif
                </div>
                <div class="px-4 w-full md:px-0 md:max-w-2/3 mx-auto py-10 space-y-4">
                    <ul class="w-full space-y-2 pb-4 border-b border-dark-blue/20">
                        <li class="flex items-center justify-between">
                            <h4 class="font-semibold">Nama</h4>
                            <p class="text-dark-blue/60">{{ $order->user->name}}</p>
                        </li>
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
                            <h4 class="font-semibold">{{ $item->product->name }} <span class="text-green-500">{{ $item->qty }}x</span></h4>
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
            <div class="relative w-full md:max-w-1/2 mx-auto bg-pale-peach px-2 py-6 rounded shadow-sm shadow-dark-blue/10 text-center">
                @php
                $statusValue = $order->status->value;
                @endphp

                @if($statusValue === 'pending')
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pesanan berhasil dikirim, tunggu respons dari admin ya.</h3>
                <p class="text-gray-600">Jangan lupa untuk segera lakukan pembayaran dan unggah bukti pembayarannya agar pesanan bisa segera diproses.</p>
                <p class="text-sm text-gray-500 mt-2">Kami akan konfirmasi setelah pembayaran kamu kami terima.</p>

                @elseif($statusValue === 'processing')
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pesananmu Sedang Diproses!</h3>
                <p class="text-gray-600">Kami sedang meracik pesananmu dengan sepenuh hati. Sebentar lagi siap dinikmati!</p>
                <p class="text-sm text-gray-500 mt-2">Tetap pantau statusnya ya.</p>

                @elseif($statusValue === 'done')
                <h3 class="text-xl font-semibold text-green-700 mb-2">Pesananmu Sudah Selesai!</h3>
                <p class="text-gray-600">Terima kasih telah berbelanja di Warkop {{ $order->branch }}. Semoga puas dengan pesananmu!</p>
                <p class="text-sm text-gray-500 mt-2">Kami tunggu orderan selanjutnya ya, Kak!</p>

                @elseif($statusValue === 'cancelled')
                <h3 class="text-xl font-semibold text-red-700 mb-2">Pesanan Telah Dibatalkan</h3>
                <p class="text-gray-600">Pesanan ini tidak dapat diproses karena telah dibatalkan.</p>
                <p class="text-sm text-gray-500 mt-2">Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami.</p>


                @else
                {{-- Status default atau tidak dikenal --}}
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Terima Kasih atas Pesanan Anda!</h3>
                <p class="text-gray-600">Status pesanan Anda saat ini adalah: {{ $order->status->label() }}.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection


@section('script')
@parent
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const showOrderStatus = () => {
        const orderStatus = document.getElementById('order-status');
        const status = orderStatus.dataset.orderStatus

        const span = document.createElement('span')
        span.classList.add(status === "pending" ? "bg-yellow-600" : status === "processing" ? "bg-blue-800" : status === "done" ? "bg-green-800" : "bg-red-800", "rounded", "px-2", "py-1", "text-white")
        span.innerHTML = status

        orderStatus.appendChild(span)
    }

    const confirmCancelOrder = (e) => {
        e.preventDefault()
        const button = e.submitter
        const orderId = button.dataset.orderId

        Swall.fire({
            title: "Anda yakin ingin membatalkan Pesanan ini ? ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
        }).then((result) => {
            if (result.isConfirmed) {
                handleCancelOrder(orderId)
            }
        })
    }

    const handleCancelOrder = async (orderId) => {
        try {
            const response = await fetch(`/order/${orderId}/cancel`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
            })

            const result = await response.json()

            if (result.error) throw new Error(result.error)

            Swall.fire({
                title: 'Pesanan berhasil di batalkan.',
                icon: 'success',
            })

            console.log({
                result
            })
            window.location.replace(`/order/${orderId}/detail`)

            return result
        } catch (err) {
            console.log({
                err
            })
            Swall.fire({
                title: err.message,
                icon: 'error',
            })
        }
    }

    showOrderStatus()
</script>
@endsection