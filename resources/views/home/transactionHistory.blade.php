@extends('layouts.home')


@section('content')
<section class="py-20">
    <div class="container max-w-6xl mx-auto">
        @if(session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <p class="text-green-700 ">
                <strong class="bold">Success!</strong> {{session('success')}}
            </p>
        </div>
        @endif

        <div class="max-w-xl mx-auto">
            <div class="w-full flex items-center justify-between py-8">
                <h2 class="text-2xl font-bold">Detail Transaksi</h2>
                <a href="{{ route('home') }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded-lg hover:scale-110"><x-icon name="arrow-left" /> Back</a>
            </div>
            <ul class="bg-white rounded-lg p-8 shadow-xl">
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
        </div>
    </div>
</section>
@endsection