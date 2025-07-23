@extends('layouts.dashboard')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="w-full flex items-center justify-between py-8">
        <h2 class="text-2xl font-bold">Transaksi Detail</h2>
        <a href="{{ route('dashboard.transactions',  ['page' => 1, 'limit' => 5]) }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded-lg hover:scale-110"><x-icon name="arrow-left" /> Back</a>
    </div>
    <ul class="bg-white rounded-lg p-8 shadow-xl">
        <li>
            <div class="text-center mb-8">
                @php
                $statusClass = '';
                $statusText = '';
                switch ($transaction->transaction_status) {
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

                $parsedData = $transaction->parsed_raw_response ?? [];
                $data = null;
                $penyediaLayanan = null;

                if(isset($parsedData['va_numbers'][0])) $data = $parsedData['va_numbers'][0];

                switch($transaction->payment_type) {
                    case "bank_transfer":
                        if(isset($parsedData['va_numbers'][0])) $penyediaLayanan = $parsedData['va_numbers'][0]['bank'];
                    break;
                    case "qris":
                        if($transaction->transaction_status == "pending") {
                            $penyediaLayanan = "--";
                        } else {
                            $penyediaLayanan = $parsedData['issuer'];
                        }
                    break;
                    default:
                        $penyediaLayanan = "--";
                };

                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }}">
                    @if($transaction->transaction_status == 'settlement')
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @elseif($transaction->transaction_status == 'pending')
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
                    <span class="text-gray-800 font-semibold">{{ $transaction->midtrans_transaction_id }}</span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-600 font-medium">Nomor Pesanan:</span>
                    <span class="text-gray-800 font-semibold">{{ $transaction->order_id }}</span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-600 font-medium">Total Pembayaran:</span>
                    <span class="text-emerald-700 text-xl font-bold">Rp{{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-600 font-medium">Metode Pembayaran:</span>
                    <span class="text-gray-800">{{ ucwords(str_replace('_', ' ', $transaction->payment_type)) }}</span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-600 font-medium">Penyedia Layanan:</span>
                    <span class="text-gray-800 uppercase">{{ ucwords(str_replace('_', ' ', $penyediaLayanan)) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Waktu Transaksi:</span>
                    <span class="text-gray-800">{{ $transactionTime }}</span>
                </div>
            </div>
        </li>
        <li>
            @if($transaction->transaction_status == 'settlement')
            <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-800 p-4 mb-8 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Detail Konfirmasi Pembayaran</h3>
                <p>Pembayaran telah berhasil dikonfirmasi oleh Midtrans.</p>
                @if (isset($transaction->parsed_raw_response['settlement_time']))
                <p><strong>Waktu Konfirmasi:</strong> {{ $settlementTime }}</p>
                @endif
                @if (isset($transaction->parsed_raw_response['masked_card']) && $transaction->payment_type == 'credit_card')
                <p><strong>Kartu Kredit:</strong> {{ $transaction->parsed_raw_response['masked_card'] }}</p>
                @endif
            </div>
            @endif
        </li>
    </ul>
</div>
@endsection