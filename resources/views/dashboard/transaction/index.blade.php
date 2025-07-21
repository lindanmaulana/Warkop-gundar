@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Manajemen Transaksi</h2>
    <p class="text-dark-blue mt-1">Kelola transaksi untuk melihat seluruh proses transaksi pesanan</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Daftar Transaksi</h2>
        <a href="{{ route('dashboard.transactions') }}" class="flex items-center rounded px-3 py-1 text-white bg-green-500 hover:bg-green-300 cursor-pointer">
            Tambah
        </a>
    </div>

    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <p class="text-green-700 ">
            <strong class="bold">Success!</strong> {{session('success')}}
        </p>
    </div>
    @endif


    <div class="bg-white p-2 rounded-lg shadow-md shadow-dark-blue/10 py-4">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-400  *:border-b *:border-dark-blue/10">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal p-2">Nama</th>
                <th class="font-normal p-2">Payment Type</th>
                <th class="font-normal p-2">Total Price</th>
                <th class="font-normal p-2">Information</th>
                <th class="font-normal p-2">Transaction Date</th>
                <th class="font-normal p-2 text-center">Aksi</th>
            </thead>
            <tbody>
                @if($transactions->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($transactions as $transaction)
                @php
                $parsedResponse = $transaction->raw_response ? json_decode($transaction->raw_response, true) : [];
                $vaNumber = null;

                if (isset($parsedResponse['va_numbers'][0]['va_number'])) {
                    $vaName = $parsedResponse['va_numbers'][0]['bank'];
                }
                @endphp
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                    <td class="py-4 px-6">{{ $no++ }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $transaction->midtrans_transaction_id }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $transaction->payment_type }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $transaction->gross_amount }}</td>
                    @if($transaction->payment_type == "bank_transfer")
                    <td class="px-2 py-4 text-dark-blue">
                        @if ($vaName)
                        <div class="flex items-center gap-2">
                            <p class="uppercase">{{ $vaName }}</p>
                        </div>
                        @else
                        -
                        @endif
                    </td>
                    @else
                    <td class="px-2 py-4 text-dark-blue">-</td>
                    @endif
                    <td class="px-2 py-4 text-dark-blue">{{ $transaction->transaction_time }}</td>
                    <td class=" py-4 px-6">
                        <div class="flex items-center justify-center gap-3 *:text-sm">
                            <a href="{{ route('dashboard.transactions.detail', $transaction->id) }}" class="text-royal-blue font-medium cursor-pointer">Detail</a>
                            <a href="{{ route('dashboard.transactions', $transaction->id) }}" class="text-royal-blue font-medium cursor-pointer">Edit</a>
                            <form action="{{ route('dashboard.transactions', $transaction) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 font-medium cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Data Category tidak tersedia.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection