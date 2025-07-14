@extends('layouts.home')

@section('content')
<section class="pt-28 pb-20 bg-gray-50 min-h-screen">
    <div class="container max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-gray-200">
                <h2 class="text-3xl font-extrabold text-secondary mb-2 sm:mb-0">Keranjang Belanja Anda</h2>
                <p id="totalItems" class="text-lg font-semibold text-gray-600"></p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 overflow-x-auto bg-white rounded-xl shadow-lg p-4">
                    <table class="w-full text-left table-auto">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                            <tr>
                                <th class="py-3 px-4 text-left rounded-tl-lg">Produk</th>
                                <th class="py-3 px-4 text-center">Harga</th>
                                <th class="py-3 px-4 text-center">Jumlah</th>
                                <th class="py-3 px-4 text-center">Total</th>
                                <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="text-gray-600 text-sm font-light">
                        </tbody>
                    </table>

                    <div id="empty-cart-message" class="hidden text-center py-16 text-gray-500">
                        <x-icon name="shopping-bag" class="size-20 mx-auto mb-4 text-gray-300" />
                        <p class="mb-6 text-xl font-medium">Keranjang belanjamu masih kosong.</p>
                        <a href="{{ route('home.menu') }}" class="inline-flex items-center px-8 py-3 bg-secondary text-white font-medium rounded-lg hover:bg-secondary/90 transition-colors duration-200 shadow-md">
                            <x-icon name="shopping-bag" class="size-5 mr-2" />
                            Mulai Belanja Sekarang
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6 h-fit sticky top-28">
                    <h3 class="text-2xl font-bold text-secondary mb-6 border-b pb-4 border-gray-200">Ringkasan Pesanan</h3>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-gray-700">
                            <span>Biaya Pengiriman</span>
                            <span class="font-medium">Gratis</span>
                        </div>
                        <div class="flex justify-between items-center text-2xl font-bold text-primary border-t pt-4 border-gray-200">
                            <span>Total</span>
                            <span id="total-price">Rp 0</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('home.checkout') }}" id="btn-complete-order" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg text-center hover:bg-green-700 transition-colors duration-200 shadow-md transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            Lanjutkan ke Pembayaran
                        </a>
                        <a href="{{ route('home.menu') }}" class="w-full border border-secondary text-secondary font-semibold py-3 rounded-lg text-center hover:bg-secondary hover:text-white transition-colors duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-opacity-50">
                            Kembali Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('script')
@parent
<script>
    const componentTotalItems = document.getElementById('totalItems')

    const showMappingCart = () => {
        let btnCompleteOrder = document.getElementById('btn-complete-order') //button complete disable saat cart kosong
        const tableBody = document.getElementById('table-body')
        tableBody.innerHTML = ""

        if (cart.length === 0) {
            const row = document.createElement('tr')
            row.classList.add('border-b', 'border-dark-blue/20', 'hover:bg-dark-blue/20');

            btnCompleteOrder.style.display = "none"

            row.innerHTML = `
                <td colspan="6" class="py-4">
                    <div class="flex items-center justify-center gap-3 *:text-red-500">
                        <x-icon name="shopping-cart" />
                        <p>Keranjang kosong!</p>
                    </div>
                </td>
            `
            showTotalPrice()

            tableBody.appendChild(row)

            return;
        }


        const storageBaseUrl = "http://localhost:8000/storage/"
        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-dark-blue/20', 'hover:bg-dark-blue/20');

            const imageUrl = item.image_url ? storageBaseUrl + item.image_url : '/images/image-placeholder.png';

            row.innerHTML = `
                        <tr class="border-b border-dark-blue/20 hover:bg-dark-blue/20">
                            <td class="py-2 px-6">${index + 1}</td>
                            <td class="py-2 ">
                                <div class="flex gap-4">
                                    <figure class="w-40 h-28">
                                        <img src="${imageUrl}" class="w-full h-full object-cover" />
                                    </figure>
                                    <div class="flex flex-col items-start justify-center gap-1">
                                        <h4 class="text-lg font-semibold">${item.productName}</h4>
                                        <bold class="block text-green-500 text-sm">${item.category}</bold>
                                        <button onclick="handleDeleteCart(${item.productId})" class="cursor-pointer text-sm text-secondary/60 hover:text-red-500 transition-global">remove</button>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 ">
                                <div class="flex items-center gap-1">
                                    <button onclick="{handleQty(this, 'dec')}" class="${item.qty === 1 && "hidden"} cursor-pointer text-2xl" id="btn-decrement"  data-item-user-id="${item.userId}" data-item-product-id="${item.productId}" data-item-qty="${item.qty}" data-item-product-price="${item.price}" data-item-product-total-price="${item.totalPrice}">-</button>
                                    <div class="border border-black/20 rounded w-8 h-6 flex items-center justify-center">
                                        <span class="text-base text-secondary/70 font-semibold">${item.qty}</span>
                                    </div>
                                    <button onclick="{handleQty(this, 'inc')}" class="cursor-pointer text-2xl" id="btn-increment"  data-item-user-id="${item.userId}" data-item-product-id="${item.productId}" data-item-qty="${item.qty}" data-item-product-price="${item.price}" data-item-product-total-price="${item.totalPrice}">+</button>
                                </div>
                            </td>
                            <td class="py-2 ">${item.price}</td>
                            <td class="py-2 ">${item.totalPrice}</td>
                        </tr>
                    `

            tableBody.appendChild(row)
        })

        showTotalPrice()
    }

    function handleQty(buttonElement, type) {
        const dataQty = buttonElement.dataset.itemQty
        const dataUserId = buttonElement.dataset.itemUserId
        const dataProductId = buttonElement.dataset.itemProductId
        const dataProductPrice = buttonElement.dataset.itemProductPrice
        const dataProductTotalPrice = buttonElement.dataset.itemProducTotalPrice

        const existInCart = cart.findIndex(item => item.userId === dataUserId && item.productId === dataProductId)

        if (existInCart > -1) {
            if (type === "inc") {
                cart[existInCart].qty += 1
                cart[existInCart].totalPrice += Number(dataProductPrice)
            }

            if (type === "dec") {
                if (cart[existInCart].qty == 1) return;
                cart[existInCart].qty -= 1
                cart[existInCart].totalPrice -= Number(dataProductPrice)
            }
        }

        showMappingCart()
        mainLocalStorage()
    }

    const handleDeleteCart = (productId) => {
        cart = cart.filter(item => item.productId !== productId.toString())
        localStorage.setItem('cart', JSON.stringify(cart))

        mainLocalStorage()
        showMappingCart()
        showTotalItems()
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

    const showTotalItems = () => {
        if (cart.length > 0) {
            componentTotalItems.innerHTML = `${cart.length} Items`
        } else {
            componentTotalItems.remove()
        }
    }

    showTotalItems()
    showMappingCart()
</script>
@endsection