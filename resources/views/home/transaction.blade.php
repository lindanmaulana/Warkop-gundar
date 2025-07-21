@extends('layouts.home')


@section('content')
<section class="py-20">
    <div class="container max-w-2xl mx-auto p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Konfirmasi Pesanan Anda</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Pesanan Anda telah berhasil dibuat. Silakan selesaikan pembayaran untuk memproses pesanan.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 text-sm">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-gray-700">Lokasi</h3>
                        <p class="text-gray-500 mt-1">{{ $order->delivery_location }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-gray-700">Cabang</h3>
                        <p class="text-gray-500 mt-1">{{ $order->branch }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-gray-700">Status</h3>
                        <p class="text-gray-500 mt-1">{{ $order->status }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Produk</h3>
                    <ul class="divide-y divide-gray-100 border border-gray-200 rounded-xl">
                        @foreach ($order->orderItems as $item)
                        <li class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-4">
                                @if ($item->product->image_url)
                                <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                    class="w-16 h-16 rounded-lg object-cover border" alt="{{ $item->product->name }}">
                                @endif
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Qty: {{ $item->qty }} × Rp{{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right font-bold text-lg text-emerald-600">
                                Rp{{ number_format($item->qty * $item->price, 0, ',', '.') }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-xl font-bold text-gray-700">
                            Total Bayar
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-600">
                            Rp{{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 p-6 border-t border-gray-200">
                <form onsubmit="handleSubmit(event)" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ old('order_id', $order->id) }}" hidden>
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-semibold text-lg shadow-md transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Bayar Sekarang
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection


@section('script')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}" onload="console.log('Snap.js berhasil dimuat!');">
</script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const handleSubmit = (e) => {
        e.preventDefault()
        const order_id = e.target.order_id.value;
        loadSnap(order_id)
    }

    const loadSnap = async (order_id) => {
        try {
            const response = await fetch("/api/v1/transaction/snap", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    order_id
                })
            });

            const result = await response.json();

            if (result.snapToken) {
                // Pastikan Snap.pay() dipanggil setelah mendapatkan token
                window.snap.pay(result.snapToken, {
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil!");
                        window.location.href = '/payment-success/' + result.order_id;
                    },
                    onPending: function(result) {
                        alert("Pembayaran dalam proses.");
                        window.location.href = '/payment-pending/' + result.order_id;
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal.");
                    },
                    onClose: function() {
                        alert("Pop-up pembayaran ditutup.");
                    }
                });
            } else {
                alert('Gagal mendapatkan token pembayaran: ' + (result.message || 'Error tidak diketahui.'));
            }

        } catch (err) {
            console.error('Error fetching Snap Token:', err);
            alert('Terjadi kesalahan jaringan atau server.');
        }
    }
</script>
@endsection