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
                    @if($order->status->value === "pending" && empty($order->transactions))
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
                        @if($order->transactions)
                        <li>
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold">Detail Pembayaran</h4>
                                <button id="btn-detail-transaction" class="rotate-180 cursor-pointer"><x-icon name="dropdown-bottom" class="size-5 text-green-500" /></button>
                            </div>
                        </li>
                        @else
                        <li>
                            <div class="text-center text-red-500">
                                <p>Belum melakukan pembayaran</p>
                            </div>
                        </li>
                        @endif
                    </ul>
                    @if($order->transactions)
                    <ul id="detail-transaction" class="h-full overflow-hidden">
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

                                $parsedData = $order->transactions->parsed_raw_response ?? [];
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
                                    <span class="text-gray-800">{{ $transactionTime }}</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            @if ($order->transactions->transaction_status == 'pending')
                            <div class="">
                                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 mb-8 rounded-lg">
                                    <h3 class="text-lg font-semibold mb-2">Instruksi Pembayaran</h3>
                                    @if ($order->transactions->payment_type == 'bank_transfer' && isset($order->transactions->parsed_raw_response['va_numbers'][0]))
                                    <p class="mb-1">Silakan transfer ke Virtual Account berikut:</p>
                                    <p class="mb-1"><strong>Bank:</strong> <span class="font-bold text-blue-900">{{ strtoupper($order->transactions->parsed_raw_response['va_numbers'][0]['bank']) }}</span></p>
                                    <p class="mb-4"><strong>Nomor Virtual Account:</strong> <span class="font-bold text-blue-900 text-lg select-all">{{ $order->transactions->parsed_raw_response['va_numbers'][0]['va_number'] }}</span></p>
                                    @elseif ($order->transactions->payment_type == 'qris')
                                    <p class="mb-2">Lakukan pembayaran pesanan ini segera.</p>
                                    @if (isset($parsedData['issuer']))
                                    <p class="mb-1">Penyedia QR: <strong>{{ strtoupper($parsedData['issuer']) }}</strong></p>
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
                                    <p class="mt-4"><strong>Batas Waktu Pembayaran:</strong> <span class="font-semibold text-red-600">{{ $expiryTime }}</span></p>
                                    @endif
                                    <p class="text-sm italic mt-2">Pastikan jumlah yang ditransfer/dibayar sesuai dengan Total Pembayaran.</p>
                                </div>
                                <a href="{{ route('home.transaction', $order->id) }}" class="block rounded-lg text-white p-2 text-center bg-secondary hover:bg-secondary/50">Selesaikan Pembayaran</a>
                            </div>
                            @elseif ($order->transactions->transaction_status == 'settlement')
                            <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-800 p-4 mb-8 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Detail Konfirmasi Pembayaran</h3>
                                <p>Pembayaran telah berhasil dikonfirmasi oleh Midtrans.</p>
                                @if (isset($order->transactions->parsed_raw_response['settlement_time']))
                                <p><strong>Waktu Konfirmasi:</strong> {{ $settlementTime }}</p>
                                @endif
                                @if (isset($order->transactions->parsed_raw_response['masked_card']) && $order->transactions->payment_type == 'credit_card')
                                <p><strong>Kartu Kredit:</strong> {{ $order->transactions->parsed_raw_response['masked_card'] }}</p>
                                @endif
                            </div>
                            @endif
                        </li>
                    </ul>
                    @endif
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

    const btnDetailTransaction = document.getElementById("btn-detail-transaction")
    const detailTransaction = document.getElementById("detail-transaction")

    btnDetailTransaction.addEventListener("click", function() {
        detailTransaction.classList.toggle("h-0")
        btnDetailTransaction.classList.toggle("rotate-180")
    })

    showOrderStatus()
</script>
@endsection