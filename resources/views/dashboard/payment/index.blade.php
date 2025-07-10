@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Jenis Pembayaran</h2>
    <p class="text-dark-blue mt-1">Kelola daftar metode pembayaran yang tersedia, lengkap dengan gambar QR Code.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Jenis Pembayaran</h2>
        <a href="{{ route('dashboard.payments.create') }}" class="flex items-center rounded px-3 py-1 text-white bg-green-500 hover:bg-green-300 cursor-pointer">
            Tambah
        </a>
    </div>

    @if(session('message'))
    <div id="alert" class="bg-green-200 rounded p-4">
        <p class="text-green-700 font-semibold">
            {{session('message')}}
        </p>
    </div>
    @endif

    <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-500">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal px-2 py-4">Image</th>
                <th class="font-normal px-2 py-4">Payment</th>
                <th class="font-normal px-2 py-4">Status</th>
                <th class="font-normal px-2 py-4">Waktu</th>
                <th class="font-normal px-2 py-4"></th>
            </thead>
            <tbody>
                @if($payments->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($payments as $payment)
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800">
                    <td class="px-6 py-2">{{ $no++ }}</td>
                    <td>
                        @if($payment->qr_code_url)
                        <img src="{{ asset('storage/' . $payment->qr_code_url) }}" alt="<?= htmlspecialchars($payment->name ?? 'Payment'); ?>" class="h-24">
                        @else
                        <img src="/images/image-placeholder.png" alt="{{ $payment->name }}" class="h-24">
                        @endif
                    </td>
                    <td class="px-2 py-4">{{ $payment->name }}</td>
                    <td class="px-2 py-4">
                        @if($payment->is_active == "1")
                        <span class="bg-royal-blue px-2 text-white text-sm">Aktif</span>
                        @else
                        <span class="bg-red-500 px-2 text-white text-sm">Tidak Aktif</span>
                        @endif
                    </td>
                    </td>
                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                    <td class="px-2 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard.payments.detail', $payment->id) }}" class="text-green-500 cursor-pointer"><x-icon name="receipt-text" /></a>
                            <a href="{{ route('dashboard.payments.update', $payment->id) }}" class="text-royal-blue cursor-pointer"><x-icon name="pencil" /></a>
                            <form action="{{ route('payments.delete', $payment->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-red-500 cursor-pointer"><x-icon name="trash" /></button>
                            </form>
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

@section('script')

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const alertComponent = document.getElementById('alert')

    const handleHideAlert = (alert) => {
        if (alert) {
            setTimeout(() => {
                alert.style.display = "none"
            }, 1500);
        }
    }

    handleHideAlert(alertComponent)
</script>

@endsection