@extends('layouts.home')

@section('content')
<section class="pt-28 pb-20">
    <div class="container max-w-6xl mx-auto ">
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-black/10 pb-6">
                <h2 class="text-2xl font-semibold text-secondary">Keranjang</h2>
                <p id="totalItems" class="text-xl font-semibold text-secondary"></p>
            </div>
            <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
                <table class="w-full text-left rounded-md overflow-hidden">
                    <thead class=" ">
                        <th class="py-2 px-6">No</th>
                        <th class="p-2">Menu Detail</th>
                        <th class="p-2">Qty</th>
                        <th class="p-2">Harga</th>
                        <th class="p-2">Total Harga</th>
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>
            </div>

            <div class="p-2 flex flex-col items-end justify-center gap-2 ">
                <p class="text-secondary text-xl font-semibold">Total: Rp <span id="total-price"></span></p>
                <div class="space-x-2 flex flex-col md:flex-row gap-1 *:text-center">
                    <a href="{{ route('home.menu') }}" class="bg-secondary px-2 py-1 rounded text-white cursor-pointer">Kembali Belanja</a>
                    <a href="{{ route('home.checkout') }}" id="btn-complete-order" class="bg-green-500 px-2 py-1 rounded text-white cursor-pointer">Selesaikan Pesanan</a>
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

        mainLocalStorage()
        showMappingCart()
    }

    const handleDeleteCart = (productId) => {
        cart = cart.filter(item => item.productId !== productId.toString())
        localStorage.setItem('cart', JSON.stringify(cart))

        showMappingCart()
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
        }
    }

    showTotalItems()
    showMappingCart()
</script>
@endsection