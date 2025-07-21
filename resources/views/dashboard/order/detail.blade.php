@extends('layouts.dashboard')


@section('content')
<div class="space-y-4 pb-10 md:pb-0">
    <div class="w-full min-h-[200px] flex items-center justify-between bg-royal-blue px-6 md:px-12 -mb-14">
        <h2 class="text-lg font-semibold text-white">Order Detail</h2>
        <a href="{{ route('dashboard.orders', ['page' => 1, 'limit' => 5]) }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded"><x-icon name="arrow-left" /> Back</a>
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
                <li>
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold">Detail Pembayaran</h4>
                        <button id="btn-detail-transaction" class="rotate-180 cursor-pointer"><x-icon name="dropdown-bottom" class="size-5 text-green-500" /></button>
                    </div>
                </li>
            </ul>
            <ul id="detail-transaction" class="h-0 overflow-hidden">
                <li>
                    <div class="text-center mb-8">
                        @php
                        $statusClass = '';
                        $statusText = '';
                        switch ($order->transactions->transaction_status) {
                        case 'settlement':
                        $statusClass = 'bg-emerald-100 text-emerald-800';
                        $statusText = 'Pembayaran Lunas';
                        break;
                        case 'pending':
                        $statusClass = 'bg-yellow-100 text-yellow-800';
                        $statusText = 'Menunggu Pembayaran';
                        break;
                        case 'expire':
                        $statusClass = 'bg-red-100 text-red-800';
                        $statusText = 'Pembayaran Kedaluwarsa';
                        break;
                        case 'cancel':
                        case 'deny':
                        case 'refund':
                        $statusClass = 'bg-red-100 text-red-800';
                        $statusText = 'Pembayaran Dibatalkan';
                        break;
                        default:
                        $statusClass = 'bg-gray-100 text-gray-800';
                        $statusText = 'Status Tidak Dikenal';
                        }
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }}">
                            @if($order->transactions->transaction_status == 'settlement')
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @elseif($order->transactions->transaction_status == 'pending')
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @else
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                            {{ $statusText }}
                        </span>
                    </div>
                </li>
                <li>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-600 font-medium">ID Transaksi Midtrans:</span>
                            <span class="text-gray-800 font-semibold">{{ $order->transactions->midtrans_transaction_id }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-600 font-medium">Nomor Pesanan:</span>
                            <span class="text-gray-800 font-semibold">{{ $order->transactions->order_id }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-600 font-medium">Total Pembayaran:</span>
                            <span class="text-emerald-700 text-xl font-bold">Rp{{ number_format($order->transactions->gross_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-600 font-medium">Metode Pembayaran:</span>
                            <span class="text-gray-800">{{ ucwords(str_replace('_', ' ', $order->transactions->payment_type)) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Waktu Transaksi:</span>
                            <span class="text-gray-800">{{ \Carbon\Carbon::parse($order->transactions->transaction_time)->format('d M Y, H:i:s T') }}</span>
                        </div>
                    </div>
                </li>
                <li>
                    @if ($order->transactions->transaction_status == 'pending')
                    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 mb-8 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Instruksi Pembayaran</h3>
                        @if ($order->transactions->payment_type == 'bank_transfer' && isset($order->transactions->parsed_raw_response['va_numbers'][0]))
                        <p class="mb-1">Silakan transfer ke Virtual Account berikut:</p>
                        <p class="mb-1"><strong>Bank:</strong> <span class="font-bold text-blue-900">{{ strtoupper($order->transactions->parsed_raw_response['va_numbers'][0]['bank']) }}</span></p>
                        <p class="mb-4"><strong>Nomor Virtual Account:</strong> <span class="font-bold text-blue-900 text-lg select-all">{{ $order->transactions->parsed_raw_response['va_numbers'][0]['va_number'] }}</span></p>
                        @elseif ($order->transactions->payment_type == 'qris')
                        <p class="mb-2">Scan QR code ini dari aplikasi e-wallet Anda.</p>
                        @if (isset($order->transactions->parsed_raw_response['actions']))
                        @foreach ($order->transactions->parsed_raw_response['actions'] as $action)
                        @if ($action['name'] == 'generate_qr_code' && isset($action['url']))
                        <img src="{{ $action['url'] }}" alt="QR Code" class="w-48 h-48 mx-auto border border-gray-300 rounded-lg mb-4">
                        @endif
                        @endforeach
                        @endif

                        @if (isset($order->transactions->parsed_raw_response['acquirer']))
                        <p class="mb-1">Penyedia QR: <strong>{{ strtoupper($order->transactions->parsed_raw_response['acquirer']) }}</strong></p>
                        @endif

                        @elseif (in_array($order->transactions->payment_type, ['gopay', 'shopeepay', 'ovo', 'dana']))
                        <p class="mb-1">Selesaikan pembayaran melalui aplikasi {{ strtoupper($order->transactions->payment_type) }}.</p>
                        @if (isset($order->transactions->parsed_raw_response['actions']))
                        @foreach ($order->transactions->parsed_raw_response['actions'] as $action)
                        @if ($action['name'] == 'deeplink' && isset($action['url']))
                        <p class="mb-2">Klik <a href="{{ $action['url'] }}" target="_blank" class="text-blue-600 hover:underline font-semibold">tautan ini</a> untuk membuka aplikasi {{ strtoupper($order->transactions->payment_type) }}.</p>
                        @endif
                        @endforeach
                        @endif
                        @endif

                        @if (isset($order->transactions->parsed_raw_response['expiry_time']))
                        <p class="mt-4"><strong>Batas Waktu Pembayaran:</strong> <span class="font-semibold text-red-600">{{ \Carbon\Carbon::parse($order->transactions->parsed_raw_response['expiry_time'])->format('d M Y, H:i:s T') }}</span></p>
                        @endif
                        <p class="text-sm italic mt-2">Pastikan jumlah yang ditransfer/dibayar sesuai dengan Total Pembayaran.</p>
                    </div>
                    @elseif ($order->transactions->transaction_status == 'settlement')
                    <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-800 p-4 mb-8 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Detail Konfirmasi Pembayaran</h3>
                        <p>Pembayaran telah berhasil dikonfirmasi oleh Midtrans.</p>
                        @if (isset($order->transactions->parsed_raw_response['settlement_time']))
                        <p><strong>Waktu Konfirmasi:</strong> {{ \Carbon\Carbon::parse($order->transactions->parsed_raw_response['settlement_time'])->format('d M Y, H:i:s T') }}</p>
                        @endif
                        @if (isset($order->transactions->parsed_raw_response['masked_card']) && $order->transactions->payment_type == 'credit_card')
                        <p><strong>Kartu Kredit:</strong> {{ $order->transactions->parsed_raw_response['masked_card'] }}</p>
                        @endif
                    </div>
                    @endif
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

    const btnDetailTransaction = document.getElementById("btn-detail-transaction")
    const detailTransaction = document.getElementById("detail-transaction")

    btnDetailTransaction.addEventListener("click", function() {
        detailTransaction.classList.toggle("h-0")
        btnDetailTransaction.classList.toggle("rotate-180")
    })

    showOrderStatus()
</script>
@endsection