@extends('layouts.home')

@section('content')
<section class="pt-28 pb-20">
    <div class="container max-w-xl mx-auto ">
        <div class="space-y-6 pb-10 md:pb-0">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-dark-blue">Checkout</h2>
                <a href="{{ route('home.menu') }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded"><x-icon name="arrow-left" /> Back</a>
            </div>
            <div class="w-full flex flex-col p-2 gap-6">
                <div class="bg-white p-4 rounded-lg shadow-sm shadow-dark-blue/10 space-y-2" id="cart-list">
                    <h3 class="text-dark-blue font-semibold text-lg">Informasi Akun</h3>
                    <input type="text" value="{{ old('email', Auth::user()->email) }}" class="w-full px-4 py-2 rounded-lg border border-dark-blue/20 text-dark-blue/50 text-sm" readonly>
                    <p class="ml-1 text-xs text-dark-blue/50">Pastikan email yang Anda masukkan benar, karena kami akan menyimpan informasi pemesanan ini.</p>
                </div>

                <div class="" id="cart-list">
                    <form onsubmit="handleSubmit(event)" class="space-y-4">
                        <div class="space-y-2 flex flex-col gap-4">
                            <div class="w-full bg-white space-y-4 p-4 shadow-sm shadow-dark-blue/10 rounded-lg">
                                <h3 class="text-dark-blue font-semibold text-lg">Informasi Pembeli</h3>
                                <label for="customer_name" class="flex flex-col gap-2">
                                    <span class="text-dark-blue text-sm">Nama:</span>
                                    <input type="text" id="customer_name" name="customer_name" value="{{ old('name', Auth::user()->name) }}" class="w-full border border-dark-blue/20 text-dark-blue/50 px-4 py-1 rounded-lg" readonly>
                                </label>
                                <label for="branch" class="flex flex-col gap-2">
                                    <span class="text-dark-blue text-sm">Lokasi Warkop:</span>
                                    <select name="branch" id="branch" class="w-full border border-dark-blue/20 px-4 py-1 rounded-lg">
                                        <option value="wg-sudirman">WG-Sudirman</option>
                                        <option value="wg-tebet">WG-Tebet</option>
                                        <option value="wg-depok">WG-Depok</option>
                                    </select>
                                </label>
                                <label for="delivery_location" class="flex flex-col gap-2">
                                    <span class="text-dark-blue text-sm">Lokasi Pengantaran:</span>
                                    <input type="text" id="delivery_location" name="delivery_location" class="w-full border border-dark-blue/20 px-4 py-1 rounded-lg">
                                </label>
                                <label for="branch" class="flex flex-col gap-2">
                                    <span class="text-dark-blue text-sm">Tipe Pembayaran:</span>
                                    @if($paymentsMethod->isNotEmpty())
                                    <select name="branch" id="branch" class="w-full border border-dark-blue/20 px-4 py-1 rounded-lg">
                                        @foreach($paymentsMethod as $payment)
                                        <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="text" class="text-red-500" placeholder="Belum ada metode pembayaran aktif untuk saat ini." disabled />
                                    @endif
                                </label>
                                <label for="desc" class="flex flex-col gap-2">
                                    <span class="text-dark-blue text-sm">Deskripsi:</span>
                                    <textarea id="desc" name="description" class="w-full border border-dark-blue/20 px-4 py-1 rounded-lg"></textarea>
                                </label>
                            </div>

                            <div class="w-full bg-white p-4 rounded-lg shadow-sm shadow-dark-blue/10 space-y-2" id="cart-list">
                                <div class=" border-b border-dark-blue/20">
                                    <h3 class="text-dark-blue font-semibold text-lg">Order</h3>
                                    <ul id="order-list" class="space-y-2 py-4"></ul>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between *:text-dark-blue/80">
                                        <h4>Harga Akhir</h4>
                                        <p class="font-semibold text-lg">Rp <span id="total-price"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-green-500 rounded text-white py-2 cursor-pointer hover:bg-green-300">Pesan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
@parent
<script>
    //  const postButton = document.getElementById('postButton');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const mappingCart = () => {
        const orderList = document.getElementById('order-list')
        orderList.innerHTML = ""

        cart.forEach(item => {
            const row = document.createElement('li')
            const totalPrice = Intl.NumberFormat('id-ID', {
                currency: "idr",
                maximumFractionDigits: 0
            }).format(item.totalPrice)

            row.innerHTML = `
                <div class="flex items-center justify-between *:text-dark-blue/80">
                    <h4>${item.productName}</h4>
                    <p>Rp ${totalPrice}</p>
                </div>
            `

            orderList.appendChild(row)
        });

        showTotalPrice()
    }

    const showTotalPrice = () => {
        let total = 0
        const totalPrice = document.getElementById("total-price")

        cart.map(item => {
            total += Number(item.totalPrice)
        })

        totalPrice.innerHTML = Intl.NumberFormat('id-ID', {
            currency: "idr",
            maximumFractionDigits: 0
        }).format(total)
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        const target = e.target;
        const customer_information = {
            customer_name: target.customer_name.value,
            branch: target.branch.value,
            delivery_location: target.delivery_location.value,
            description: target.description.value
        }

        const orders = {
            cart: cart,
            customer_information
        }

        try {
            const response = await fetch('/order/checkout', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(orders)
            })

            const result = await response.json()

            console.log({
                result
            })

            if (!response.ok) throw result

            Swall.fire({
                title: 'Pesanan berhasil dibuat',
                icon: 'success',
            })

            localStorage.removeItem('cart')
            window.location.replace('/dashboard/orders')
            return result
        } catch (err) {
            Swall.fire({
                title: err.error,
                icon: 'error',
            })
        }
    }

    mappingCart()
</script>
@endsection