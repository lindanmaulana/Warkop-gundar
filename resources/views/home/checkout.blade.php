@extends('layouts.home')

@section('content')
<section class="pt-28 pb-20 bg-gray-50 min-h-screen">
    <div class="container max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <div class="flex items-center justify-between pb-6 border-b border-gray-200">
                <h2 class="text-3xl font-extrabold text-secondary">Checkout</h2>
                <a href="{{ route('home.menu', ['page' => 1, 'limit' => 5]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-lg shadow hover:bg-secondary/90 transition-colors duration-200">
                    <x-icon name="arrow-left" class="size-4" />
                    <span>Kembali Belanja</span>
                </a>
            </div>

            <div class="w-full flex flex-col gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg space-y-3">
                    <h3 class="text-xl font-bold text-secondary mb-2">Informasi Akun</h3>
                    <label for="account_email" class="block text-secondary text-sm font-medium mb-1">Email:</label>
                    <input type="text" id="account_email" value="{{ old('email', Auth::user()->email ?? 'user@example.com') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 text-sm cursor-not-allowed" readonly>
                    <p class="mt-2 text-xs text-gray-500">Pastikan email yang Anda masukkan benar, karena kami akan menyimpan informasi pemesanan ini.</p>
                </div>

                <div id="checkout-form-container">
                    <form onsubmit="handleSubmit(event)" class="space-y-6">
                        <div class="w-full bg-white space-y-5 p-6 shadow-lg rounded-xl">
                            <h3 class="text-xl font-bold text-secondary mb-2">Informasi Pembeli & Pengiriman</h3>

                            <label for="customer_name" class="flex flex-col gap-2">
                                <span class="text-secondary text-sm font-medium">Nama:</span>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('name', Auth::user()->name ?? 'Nama Pelanggan') }}" class="w-full border border-gray-300 text-gray-800 px-4 py-2 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200" readonly>
                            </label>

                            <label for="branch" class="flex flex-col gap-2">
                                <span class="text-secondary text-sm font-medium">Lokasi Warkop:</span>
                                <select name="branch" id="branch" class="w-full border border-gray-300 text-gray-800 px-4 py-2 rounded-lg bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                                    <option value="wg-sudirman">WG-Sudirman</option>
                                    <option value="wg-tebet">WG-Tebet</option>
                                    <option value="wg-depok">WG-Depok</option>
                                </select>
                            </label>

                            <label for="delivery_location" class="flex flex-col gap-2">
                                <span class="text-secondary text-sm font-medium">Lokasi Pengantaran:</span>
                                <input type="text" id="delivery_location" name="delivery_location" placeholder="Cth: Lantai 5, Depan Lift" class="w-full border border-gray-300 text-gray-800 px-4 py-2 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200" required>
                            </label>

                            <label for="description" class="flex flex-col gap-2">
                                <span class="text-secondary text-sm font-medium">Catatan Tambahan (opsional):</span>
                                <textarea id="description" name="description" rows="3" placeholder="Contoh: Kopi tanpa gula, Roti bakar extra keju" class="w-full border border-gray-300 text-gray-800 px-4 py-2 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"></textarea>
                            </label>
                        </div>

                        <div class="w-full bg-white p-6 rounded-xl shadow-lg space-y-4">
                            <div class="border-b border-gray-200 pb-4 mb-4">
                                <h3 class="text-xl font-bold text-secondary">Rincian Pesanan</h3>
                                <ul id="order-list" class="space-y-3 pt-4"></ul>
                            </div>
                            <div>
                                <div class="flex items-center justify-between text-secondary">
                                    <h4 class="text-lg font-semibold">Total Pembayaran</h4>
                                    <p class="font-bold text-3xl">Rp <span id="total-price"></span></p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg text-center hover:bg-green-700 transition-colors duration-200 shadow-md transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            Pesan Sekarang
                        </button>
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
            delivery_location: target.delivery_location.value,
            branch: target.branch.value,
            description: target.description.value
        }

        const orders = {
            cart: cart,
            customer_information
        }

        try {
            const response = await fetch('/checkout', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(orders)
            })

            const result = await response.json()

            if (!response.ok) throw result

            Swall.fire({
                title: 'Pesanan berhasil dibuat',
                icon: 'success',
            })

            localStorage.removeItem('cart')
            window.location.replace(`/transaction/${result.order_id}`)
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