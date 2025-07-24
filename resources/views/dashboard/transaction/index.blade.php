@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <h2 class="text-3xl font-semibold text-primary">Manajemen Transaksi</h2>
    <p class="text-secondary mt-1">Kelola transaksi untuk melihat seluruh proses transaksi pesanan</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-primary">Daftar Transaksi</h2>

        <div>
            <select name="status" id="filter-status" class="bg-secondary text-white px-2 rounded py-1">

            </select>
            <select name="status" id="filter-payment-type" class="bg-secondary text-white px-2 rounded py-1">

            </select>
            <input id="filter-date" type="date" placeholder="Tanggal Transaksi" class="border border-secondary text-secondary px-2 rounded py-1">
            <button id="btn-reset" class="hidden bg-red-500 text-sm px-2 py-1 rounded text-white">Reset</button>
        </div>
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
                <th class="font-normal p-2">Midtrans OrderId</th>
                <th class="font-normal p-2">jenis Pembayaran</th>
                <th class="font-normal p-2">Total Price</th>
                <th class="font-normal p-2">Penyedia</th>
                <th class="font-normal p-2">Status</th>
                <th class="font-normal p-2">Tanggal Transaksi</th>
                <th class="font-normal p-2 text-center">Aksi</th>
            </thead>
            <tbody id="body-transaction">
            </tbody>
        </table>
        <div class="flex items-center justify-between py-6 px-4">
            <div class="flex items-center gap-2">
                <select name="" id="filter-limit" class="border border-dark-blue/20 rounded-md px-3 py-1 text-sm font-semibold">
                </select>
                <p class="text-sm font-semibold text-dark-blue/80">data per halaman.</p>
            </div>

            <div id="filter-page" class="flex items-center gap-2">

            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const urlParams = new URLSearchParams(window.location.search)
    const filterLimit = document.getElementById("filter-limit")
    const filterStatus = document.getElementById("filter-status")
    const filterPaymentType = document.getElementById("filter-payment-type")
    const filterDate = document.getElementById("filter-date")
    const btnReset = document.getElementById("btn-reset")

    const dataFilterLimit = [5, 10, 15, 20]
    const dataFilterStatus = [{
            status: "pending",
            value: "pending"
        },
        {
            status: "lunas",
            value: "settlement"
        },
        {
            status: "ditolak",
            value: "deny"
        },
        {
            status: "kedaluarsa",
            value: "expire"
        },
        {
            status: "refund",
            value: "refund"
        },
        {
            status: "chargeback",
            value: "chargeback"
        }
    ]
    const dataFilterPaymentType = ['bank_transfer', 'qris']

    btnReset.addEventListener("click", function() {
        urlParams.delete("date")

        showFilterDate()
        showBtnReset()
        updateURL()
        loadDataTransaction()
    })

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
        loadDataTransaction()
    })

    filterPaymentType.addEventListener("change", function() {
        const value = this.value

        switch (value) {
            case "":
                urlParams.delete("payment-type")
                break;
            default:
                urlParams.set("payment-type", value)
        }

        updateURL()
        loadDataTransaction()
    })

    filterDate.addEventListener("change", function() {
        const value = this.value

        switch (value) {
            case "":
                urlParams.delete("date")
                break;
            default:
                urlParams.set("date", value)
        }

        showBtnReset()
        updateURL()
        loadDataTransaction()
    })

    filterLimit.addEventListener("change", function() {
        const value = this.value
        urlParams.set("limit", value)
        urlParams.set("page", 1)

        updateURL()
        loadDataTransaction()
    })

    const loadDataTransaction = async () => {
        try {
            const response = await fetch(`/api/v1/transactions?${urlParams.toString()}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            })

            const result = await response.json()

            const pages = result.data.links
            const data = result.data.data

            showFilterPage(pages)
            showTransactionList(data)

            return result
        } catch (err) {
            console.log({
                err
            })
        }
    }

    const showFilterStatus = () => {
        filterStatus.innerHTML = ""

        const row = dataFilterStatus.map(filter => {

            return (
                `
                <option value="${filter.value}">${filter.status}</option>
                `
            )
        }).join(" ")

        const rows = `
            <option value="">Status</option>
            ${row}
        `

        filterStatus.innerHTML = rows;
    }

    const showFilterPaymentType = () => {
        filterPaymentType.innerHTML = ""

        const row = dataFilterPaymentType.map(type => {
            return (
                `
                <option value="${type}">${type}</option>
                `
            )
        }).join(" ")

        const rows = `
            <option value="">Payment Type</option>
            ${row}
        `

        filterPaymentType.innerHTML = rows
    }

    const showFilterDate = () => {
        const isShow = urlParams.get("date") ? true : false

        isShow ? filterDate.value = urlParams.get("date").toString() : filterDate.value = ""
    }

    const showBtnReset = () => {
        const isShow = urlParams.get("date") ? true : false

        switch (isShow) {
            case true:
                btnReset.classList.remove("hidden")
                break;
            case false:
                btnReset.classList.add("hidden")
                break;
            default:
                btnReset.classList.add("hidden")
        }
    }

    const showTransactionList = (dataTransaction) => {
        const bodyTransaction = document.getElementById("body-transaction")
        bodyTransaction.innerHTML = ""

        const row = dataTransaction.length > 0 ? dataTransaction.map((transaction, index) => {
            let provider = ''
            let totalPrice;
            let transactionStatus = ""

            const rawResponse = JSON.parse(transaction.raw_response);

            totalPrice = Intl.NumberFormat("id-ID", {
                currency: "IDR",
                style: "currency",
                maximumFractionDigits: 0
            }).format(Number(transaction.gross_amount))


            switch (transaction.payment_type) {
                case "bank_transfer":
                    if (rawResponse.va_numbers) {
                        provider = rawResponse.va_numbers[0].bank;

                    } else if (rawResponse.permata_va_number) {
                        provider = "Permata";

                    } else {
                        provider = rawResponse.va_numbers
                    }
                    break
                case "qris":
                    rawResponse.transaction_status == "pending" ? provider = "-" : provider = rawResponse.issuer;
                    break;
                default:
                    provider = rawResponse.transaction_status
            }


            switch (transaction.transaction_status) {
                case "pending":
                    transactionStatus = "pending"
                    break;
                case "settlement":
                    transactionStatus = "Lunas"
                    break;
                case "deny":
                    transactionStatus = "Ditolak"
                    break;
                case "expire":
                    transactionStatus = "Kedaluarsa"
                    break;
                case "cancel":
                    transactionStatus = "Dibatalkan"
                    break;
                default:
                    transactionStatus = "Dikembalikan"
            }


            return (
                `
                 <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                    <td class="py-4 px-6">${index += 1}</td>
                    <td class="px-2 py-4 text-dark-blue">${transaction.midtrans_order_id}</td>
                    <td class="px-2 py-4 text-dark-blue">${ transaction.payment_type }</td>
                    <td class="px-2 py-4 text-dark-blue">${totalPrice}</td>
                    <td class="px-2 py-4 text-dark-blue uppercase">${provider}</td>
                    <td class="px-2 py-4 text-dark-blue uppercase">${transactionStatus}</td>
                    <td class="px-2 py-4 text-dark-blue">${ transaction.transaction_time }</td>
                    <td class=" py-4 px-6">
                        <a href="/dashboard/transactions/${transaction.id}/detail" class="text-green-500 font-medium cursor-pointer">Detail</a>
                    </td>
                </tr>
                `
            )

        }).join(" ") : (
            `
            <tr>
                <td colspan="8" class="text-center py-4 text-red-500">
                <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Data Transaksi tidak tersedia.</p>
                </td>
            </tr>
            `
        )

        bodyTransaction.innerHTML = row
    }

    const showFilterLimit = () => {
        const urlParams = new URLSearchParams(window.location.search)
        const limitParams = urlParams.get("limit") ? urlParams.get("limit") : "5"
        const paginationFilter = document.getElementById("filter-limit")
        paginationFilter.innerHTML = ""

        const option = dataFilterLimit.map(limit => (
            `
                <option value="${limit}" ${limit == limitParams ? "selected" : ""}>${limit}</option>
            `
        ))

        paginationFilter.innerHTML = option
    }

    const showFilterPage = (pages) => {
        const urlParams = new URLSearchParams(window.location.search)
        const pageParams = urlParams.get("page")

        const page = document.getElementById("filter-page")
        page.innerHTML = ""

        const link = pages.map(page => {
            const isUrl = page.url
            const isButton = page.label.length
            const isPaginationControl = isButton > 1

            const isActive = isUrl && pageParams == page.label
            const isDisabled = !isUrl

            const styleIsDisabled = isDisabled ? "cursor-not-allowed opacity-50" : "cursor-pointer"
            const styleIsActive = isActive ? "bg-primary text-white" : ""

            return (
                `
                    <button onclick="handleFilterPage('${isUrl}')" ${isDisabled || isActive ? "disabled" : ""} class="border px-3 py-1 rounded text-sm ${styleIsActive} ${styleIsDisabled}">${page.label}</button>
                `
            )
        }).join(" ")

        page.innerHTML = link
    }

    const handleFilterPage = (url) => {
        const urlObj = new URL(url)
        const params = new URLSearchParams(urlObj.search)
        const page = params.get("page")

        urlParams.set("page", page)

        updateURL()
        loadDataTransaction()
    }

    function updateURL() {
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.history.replaceState({}, '', newUrl);
    }

    loadDataTransaction()
    showFilterStatus()
    showFilterPaymentType()
    showBtnReset()
    showFilterDate()
    showFilterLimit()
</script>
@endsection