@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Jenis Pembayaran</h2>
    <p class="text-dark-blue mt-1">Kelola daftar metode pembayaran yang tersedia, lengkap dengan gambar QR Code.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-dark-blue">Tipe Pembayaran</h2>
        <a href="{{ route('dashboard.categories.create') }}" class="flex items-center rounded px-3 py-1 text-white bg-dark-blue hover:bg-dark-blue/70 cursor-pointer">
            Tambah
        </a>
    </div>
    <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-500">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal px-2 py-4">Payment</th>
                <th class="font-normal px-2 py-4">Status</th>
                <th class="font-normal px-2 py-4">Image</th>
                <th class="font-normal px-2 py-4">Waktu</th>
                <th class="font-normal px-2 py-4"></th>
            </thead>
            <tbody>
                @if($payments->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($payments as $payment)
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800">
                    <td class="px-6 py-2">{{ $no++ }}</td>
                    <td class="px-2 py-4">{{ $payment->name }}</td>
                    <td class="px-2 py-4">{{ $payment->is_active }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $payment->qr_code_url) }}" alt="<?= htmlspecialchars($payment->name ?? 'Payment'); ?>">
                    </td>
                    </td>
                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                    <td class="px-2 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard.orders.detail', $payment->id) }}" class="text-green-500 cursor-pointer"><x-icon name="receipt-text" /></a>
                            @if(auth()->check() && auth()->user()->role->value == 'admin')
                            <a href="{{ route('dashboard.orders.update', $payment->id) }}" class="text-royal-blue cursor-pointer"><x-icon name="pencil" /></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" />Payment Kosong.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection