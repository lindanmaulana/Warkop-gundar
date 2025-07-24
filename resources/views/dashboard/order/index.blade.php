@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <h2 class="text-3xl font-semibold text-primary">Daftar Pesanan</h2>
    <p class="text-secondary mt-1">Pantau dan kelola semua pesanan pelanggan yang masuk di Warkop.</p>
</div>
@endsection

@section('content')
@php
$isAdmin = Auth::user()->role->value == "admin"
@endphp
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-primary">Order</h2>
        <div>
            <select name="status" id="filter-status" class="bg-secondary text-white px-2 rounded py-1">

            </select>
            <input id="filter-search" type="text" placeholder="Cari..." class="border border-dark-blue/20 rounded-lg px-4 py-1">
        </div>
    </div>
    <div class="overflow-x-auto bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-500">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal px-2 py-4">Pelanggan</th>
                <th class="font-normal px-2 py-4">Email</th>
                <th class="font-normal px-2 py-4">Tempat</th>
                <th class="font-normal px-2 py-4">Total</th>
                <th class="font-normal px-2 py-4">Status</th>
                <th class="font-normal px-2 py-4">Waktu</th>
                <th class="font-normal px-2 py-4"></th>
            </thead>
            <tbody id="order-content" data-role-access="{{ $isAdmin }}" data-update-url="{{ route('dashboard.orders.update', ':id') }}">

            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between py-6 px-4">
        <div class="flex items-center gap-2">
            <select name="" id="filter-limit" class="border border-dark-blue/20 rounded-md px-3 py-1 text-sm font-semibold"></select>
            <p class="text-sm font-semibold opacity-50">data per halaman</p>
        </div>

        <div id="filter-page" class="flex items-center gap-2">

        </div>
    </div>
</div>
@endsection


@section('script')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const urlParams = new URLSearchParams(window.location.search)
    const filterStatus = document.getElementById("filter-status")
    const filterSearch = document.getElementById("filter-search")

    const dataFilterLimit = [5, 10, 15, 20]
    const dataFilterStatus = ["pending", "processing", "done", "cancelled"]


    function debounce(fn, delay) {
        let timeout;

        return function(...args) {
            clearTimeout(timeout)
            timeout = setTimeout(() => fn.apply(this, args), delay)
        }
    }

    filterSearch.defaultValue = urlParams.get("keyword") ? urlParams.get("keyword").toString() : ""
    filterSearch.addEventListener("input", debounce(function() {
        const value = this.value

        switch (value) {
            case "":
                urlParams.delete("keyword")
                break;
            default:
                urlParams.set("keyword", value)
            break
        }

        updateURL()
        loadDataOrder()
    }, 1000))

    filterStatus.addEventListener("change", function() {
        const value = this.value

        switch (value) {
            case "":
                urlParams.delete("status")
                break;
            default:
                urlParams.set("status", value)
        }

        updateURL()
        loadDataOrder()
    })

    document.getElementById("filter-limit").addEventListener("change", function() {
        const value = this.value
        urlParams.set("limit", value)
        urlParams.set("page", 1)

        updateURL()
        loadDataOrder()
    })

    const showFilterStatus = () => {
        const params = new URLSearchParams(window.location.search)
        const queryStatus = params.get("status") ? params.get("status").toString() : ""
        filterStatus.innerHTML = ""

        const row = dataFilterStatus.map((status) => {

            return (
                `
                <option value="${status}" ${status === queryStatus ? "selected" : ""}>${status}</option>
                `
            )
        }).join(" ")

        const rowStatus = `
        <option value="">All</option>
        ${row}
        `

        filterStatus.innerHTML = rowStatus;
    }

    const showFilterLimit = () => {
        const urlParams = new URLSearchParams(window.location.search)
        const limitParams = urlParams.get("limit") || "5"

        const filterLimit = document.getElementById("filter-limit")
        filterLimit.innerHTML = ""

        const options = dataFilterLimit.map(limit => (
            `
            <option value="${limit}" ${limit == limitParams ? "selected" : ""}>${limit}</option>
            `
        )).join(" ")

        filterLimit.innerHTML = options
    }

    const loadDataOrder = async () => {
        try {
            const response = await fetch(`/api/v1/orders?${urlParams.toString()}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            })

            const result = await response.json()
            const pages = result.data.links

            const data = result.data.data
            showOrderContent(data)
            showFilterPage(pages)

            return result
        } catch (err) {
            console.log({
                err
            })
        }
    }

    const showOrderContent = (dataOrder) => {
        const userContent = document.getElementById("order-content")
        userContent.innerHTML = ""
        const isAdmin = userContent.dataset.roleAccess

        const row = dataOrder.length > 0 ? dataOrder.map((order, index) => {
            let statusOrder = ''
            let actionUpdate = ""

            switch (order.status) {
                case "pending":
                    statusOrder = '<p class="text-sm rounded px-2 py-1 text-center bg-yellow-600 text-white">Pending</p>'
                    actionUpdate = `<a href="/dashboard/orders/${order.id}/update" class="text-royal-blue cursor-pointer"><x-icon name="pencil" /></a>`
                    break;
                case "processing":
                    statusOrder = '<p class="text-sm rounded px-2 py-1 text-center bg-blue-800 text-white">Processing</p>'
                    actionUpdate = `<a href="/dashboard/orders/${order.id}/update" class="text-royal-blue cursor-pointer"><x-icon name="pencil" /></a>`
                    break
                case "done":
                    statusOrder = '<p class="text-sm rounded px-2 py-1 text-center bg-green-800 text-white">Done</p>'
                    break
                default:
                    statusOrder = '<p class="text-sm rounded px-2 py-1 text-center bg-red-800 text-white">Cancelled</p>'
            }

            if (!isAdmin) {
                actionUpdate = ""
            }

            const totalPrice = utilCurrency(order.total_price)
            const createdAt = utilTimemstamp(order.created_at)

            return (
                `
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800">
                    <td class="px-6 py-2">${index + 1}</td>
                    <td class="px-2 py-4">${order.user.name}</td>
                    <td class="px-2 py-4">${order.user.email}</td>
                    <td class="px-2 py-4">${order.branch}</td>
                    <td class="px-2 py-4">${totalPrice}</td>
                    <td class="px-2 py-4">
                        ${statusOrder}
                    </td>
                    <td>${createdAt}</td>
                    <td class="px-2 py-4">
                        <div class="flex items-center gap-2">
                            <a href="/dashboard/orders/${order.id}/detail" class="text-green-500 cursor-pointer"><x-icon name="receipt-text" /></a>
                            ${actionUpdate}
                        </div>
                    </td>
                </tr>
                `
            )
        }).join(" ") : (
            `
                <tr>
                    <td colspan="9" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Pesanan kosong.</p>
                    </td>
                </tr>
            `
        )

        userContent.innerHTML = row
    }

    const showFilterPage = (pages) => {
        const urlParams = new URLSearchParams(window.location.search)
        const pageParams = urlParams.get("page")
        const filterPage = document.getElementById("filter-page")
        filterPage.innerHTML = ""

        const buttonPagination = pages.map(page => {
            const isUrl = page.url
            const isButton = page.label.length
            const isPaginationControl = isButton > 1

            const isActive = isUrl && pageParams == page.label
            const isDisabled = !isUrl

            const styleIsDisabled = isDisabled ? "cursor-not-allowed opacity-50" : "cursor-pointer"
            const styleIsActive = isActive ? "bg-secondary text-white" : ""

            return (
                `
                    <button onclick="handleFilterPage('${isUrl}')" ${isDisabled || isActive ? "disabled" : ""} class="border px-3 py-1 rounded text-sm ${styleIsActive} ${styleIsDisabled}" >${page.label}</button>
                `
            )
        }).join(" ")

        filterPage.innerHTML = buttonPagination
    }

    const handleFilterPage = (url) => {
        const urlObj = new URL(url)
        const params = new URLSearchParams(urlObj.search)
        const page = params.get("page")

        urlParams.set("page", page)

        updateURL()
        loadDataOrder()
    }

    const updateURL = () => {
        const newURL = `${window.location.pathname}?${urlParams.toString()}`

        window.history.replaceState({}, '', newURL)
    }

    const utilTimemstamp = (timestamp) => {
        const date = new Date(timestamp);

        const tanggal = date.getDate();
        const bulan = date.toLocaleString('id-ID', {
            month: 'long'
        });
        const tahun = date.getFullYear();
        const jam = String(date.getHours()).padStart(2, '0');
        const menit = String(date.getMinutes()).padStart(2, '0');

        const formatted = `${tanggal} ${bulan} ${tahun} ${jam}:${menit}`;

        return formatted
    }

    const utilCurrency = (price) => {
        return Intl.NumberFormat("id-ID", {
            currency: "IDR",
            style: "currency",
            maximumFractionDigits: 0
        }).format(Number(price))
    }

    showFilterLimit()
    showFilterStatus()
    loadDataOrder()
</script>
@endsection