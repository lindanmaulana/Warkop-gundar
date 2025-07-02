@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue hover:bg-royal-blue/80' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-dark-blue">Cart</h2>
        <a href="{{ route('dashboard.orders') }}" class="flex items-center justify-start max-w-20 gap-1 bg-dark-blue text-sm px-4 py-1 text-white rounded"><x-icon name="arrow-left" /> Back</a>
    </div>
    <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="bg-royal-blue hover:bg-royal-blue/70 text-white">
                <th class="py-2 px-6">No</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Qty</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Total Harga</th>
                @if(auth()->check() && auth()->user()->role->value == 'customer')
                <th class="p-2"></th>
                @endif
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>

    <div class="p-2 flex flex-col items-end justify-center gap-2 ">
        <p class="text-royal-blue text-xl font-semibold">Total: Rp <span id="total-price"></span></p>
        <div class="space-x-2 flex flex-col md:flex-row gap-1 *:text-center">
            <a href="{{ route('dashboard.menu.products') }}" class="bg-dark-blue px-2 py-1 rounded text-white cursor-pointer">Kembali Belanja</a>
            <a href="{{ route('dashboard.orders.checkout') }}" id="btn-complete-order" class="bg-green-500 px-2 py-1 rounded text-white cursor-pointer">Selesaikan Pesanan</a>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
    let localstorageCart = localStorage.getItem('cart')
    
    let cart = localstorageCart ? JSON.parse(localstorageCart) : []
    
    const showMappingCart = () => {
        let btnCompleteOrder = document.getElementById('btn-complete-order') //button complete disable saat cart kosong
        const tableBody = document.getElementById('table-body')
        tableBody.innerHTML = ""
        
        if(cart.length === 0) {
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


        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-dark-blue/20', 'hover:bg-dark-blue/20');
            row.innerHTML = `
                        <tr class="border-b border-dark-blue/20 hover:bg-dark-blue/20">
                            <td class="py-2 px-6">${index + 1}</td>
                            <td class="py-2 ">${item.productName}</td>
                            <td class="py-2 ">
                                <div class="flex items-center gap-1">
                                    <button onclick="{handleQty(this, 'dec')}" class="${item.qty === 1 && "hidden"} cursor-pointer text-lg" id="btn-decrement"  data-item-user-id="${item.userId}" data-item-product-id="${item.productId}" data-item-qty="${item.qty}" data-item-product-price="${item.price}" data-item-product-total-price="${item.totalPrice}">-</button>
                                    <span class="text-lg text-royal-blue font-semibold">${item.qty}</span>
                                    <button onclick="{handleQty(this, 'inc')}" class="cursor-pointer text-lg" id="btn-increment"  data-item-user-id="${item.userId}" data-item-product-id="${item.productId}" data-item-qty="${item.qty}" data-item-product-price="${item.price}" data-item-product-total-price="${item.totalPrice}">+</button>
                                </div>
                            </td>
                            <td class="py-2 ">${item.price}</td>
                            <td class="py-2 ">${item.totalPrice}</td>
                             @if(auth()->check() && auth()->user()->role->value == 'customer')
                                <td class="py-2 ">
                                    <button onclick="handleDeleteCart(${item.productId})" class="cursor-pointer"><x-icon name="trash" /></button>
                                </td>
                            @endif
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

        actionLocalStorage()
        showMappingCart()
    }

    const handleDeleteCart = (productId) => {
        cart = cart.filter(item => item.productId !== productId.toString())
        localStorage.setItem('cart', JSON.stringify(cart))

        showMappingCart()
    }

    function actionLocalStorage() {
        const cartNew = JSON.stringify(cart)
        localStorage.setItem('cart', cartNew)
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



    showMappingCart()
</script>
@endsection