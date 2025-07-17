@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Manajemen Menu</h2>
    <p class="text-dark-blue mt-1">Atur dan kelola daftar makanan serta minuman yang tersedia di Warkop.</p>
</div>
@endsection


@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Menu</h2>
        <div class="flex items-center gap-2">
            <form id="categoryFilterForm" action="{{ route('dashboard.menu.products') }}" method="GET">
                <select name="category" id="category_filter" class="bg-dark-blue text-white px-2 rounded py-1">

                </select>
            </form>
            <a href="{{ route('dashboard.menu.products.create') }}" class="flex items-center rounded px-3 py-1 text-white bg-green-500 hover:bg-green-300 cursor-pointer">
                Tambah
            </a>
        </div>
    </div>

    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <p class="text-green-700 ">
            <strong class="bold">Success!</strong> {{session('success')}}
        </p>
    </div>
    @endif

    <div class="overflow-x-auto w-full bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class=" *:text-gray-400 *:border-b *:border-dark-blue/10">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal p-2">Gambar</th>
                <th class="font-normal p-2">Nama</th>
                <th class="font-normal p-2">Kategori</th>
                <th class="font-normal p-2">Harga</th>
                <th class="font-normal p-2">Stok</th>
                <th class="font-normal p-2">Deskripsi</th>
                <th class="font-normal p-2 text-center">Aksi</th>
            </thead>
            <tbody id="body-product" data-edit-url="{{ route('dashboard.menu.products.edit', ':id') }}"
                data-detail-url="{{ route('dashboard.menu.products.detail', ':id') }}"
                data-delete-url="{{ route('products.destroy', ':id') }}">
            </tbody>
        </table>
        <div class="flex items-center justify-between py-6 px-4">
            <div class="flex items-center gap-2">
                <select name="" id="filter-limit" class="border border-dark-blue/20 rounded-md px-3 py-1 text-sm font-semibold">
                </select>
                <p class="text-sm font-semibold text-dark-blue/80">data per halaman.</p>
            </div>

            <div>
                <div id="page" class="flex items-center gap-2">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const urlParams = new URLSearchParams(window.location.search)

    let data = []
    let setParams = ''
    let categoryParams = urlParams.get("category") || "default"
    let limitParams = urlParams.get("limit") || "5"

    const dataLimitPage = [5, 10, 15, 20]

    document.getElementById("filter-limit").addEventListener("change", function() {
        const value = this.value
        urlParams.set("limit", value)
        urlParams.set("page", 1)

        updateURL()
        loadDataProduct()
    })

    document.getElementById("category_filter").addEventListener("change", function() {
        const value = this.value

        if (value === "default" || value === "") {
            urlParams.delete("category")
            updateURL();
        } else {
            urlParams.set("category", value)
        }

        updateURL()
        loadDataProduct()
    })

    const loadDataProduct = async () => {
        try {
            const response = await fetch(`/api/v1/products?${urlParams.toString()}`, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })

            const result = await response.json()
            const currentPage = result.data.current_page
            const pages = result.data.links

            data = result.data.data

            showProductList(data)
            showFilterPage(pages)

            return result
        } catch (err) {
            console.log({
                err
            })
        }
    }

    const showProductList = (dataProduct) => {
        const bodyProduct = document.getElementById("body-product")
        bodyProduct.innerHTML = ""
        const editUrlTemplate = bodyProduct.dataset.editUrl;
        const detailUrlTemplate = bodyProduct.dataset.detailUrl;
        const deleteUrlTemplate = bodyProduct.dataset.deleteUrl;


        const row = dataProduct.length > 0 ? dataProduct.map((product, index) => {
            let imageUrl = product.image_url ? `/storage/${product.image_url}` : "/images/image-placeholder.png"
            const editUrl = editUrlTemplate.replace(':id', product.id);
            const detailUrl = detailUrlTemplate.replace(':id', product.id);
            const deleteUrl = deleteUrlTemplate.replace(':id', product.id);

            const price = Intl.NumberFormat("id-ID", {
                currency: "IDR",
                style: "currency",
                maximumFractionDigits: 0
            }).format(product.price)

            return (
                `
                <tr class=" hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                    <td class="py-4 px-6">${index + 1}</td>
                    <td class="px-2 py-4 text-dark-blue" id="table-image">
                        <img src="${imageUrl}" alt="{${product.name}}" class="w-24">
                    </td>
                    <td class="px-2 py-4 text-dark-blue">${product.name}</td>
                    <td class="px-2 py-4 text-dark-blue">${product.category.name}</td>
                    <td class="px-2 py-4 text-dark-blue">${price}</td>
                    <td class="px-2 py-4 text-dark-blue">${product.stock}</td>
                    <td class="px-2 py-4 text-dark-blue">${product.description}</td>
                    <td class="px-2 py-4 text-dark-blue">
                        <div class="flex items-center justify-center gap-3 *:text-sm">
                            <a href="${editUrl}" class="text-royal-blue font-medium cursor-pointer">Edit</a>
                            <a href="${detailUrl}" class="text-green-500 font-medium cursor-pointer">Detail</a>
                            <form action="${deleteUrl}" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-500 font-medium cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            `
            )
        }) : `<tr>
                <td colspan="8" class="text-center py-4 text-red-500">
                    <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Menu tidak tersedia.</p>
                </td>
            </tr> `

        bodyProduct.innerHTML = row

    }

    const loadDataCategory = async () => {
        try {
            const response = await fetch('/api/v1/categories', {
                method: "GET",
                headers: {
                    'Content-Type': "application/json",
                    'X-CSRF-TOKEN': csrfToken
                }
            })

            const result = await response.json()
            const data = result.data

            showCategoryList(data)
            return result
        } catch (err) {
            console.log({
                err
            })
        }
    }

    const showCategoryList = (dataCategory) => {
        const categoryFilter = document.getElementById("category_filter")
        categoryFilter.innerHTML = ""

        const list = dataCategory.map(category => (
            `
                <option value="${category.id}" ${category.id == categoryParams ? "selected" : ""}  >${category.name}</option>
            `
        )).join("")

        const options = `
            <option value="default" ${categoryParams === "default" ? "selected" : ""}>All</option>
        ${list}
        `

        categoryFilter.innerHTML = options
    }

    const showFilterLimit = () => {
        const paginationFilter = document.getElementById("filter-limit")
        paginationFilter.innerHTML = ""

        const option = dataLimitPage.map(limit => (
            `
                <option value="${limit}" ${limit == limitParams ? "selected" : ""}>${limit}</option>
            `
        ))

        paginationFilter.innerHTML = option
    }

    const showFilterPage = (pages) => {
        const urlParams = new URLSearchParams(window.location.search)
        const pageParams = urlParams.get("page")
        const page = document.getElementById("page")
        page.innerHTML = ""

        const link = pages.map(page => {
            const isUrl = page.url
            const isButton = page.label.length
            const isPaginationControl = isButton > 1

            const isActive = isUrl && pageParams == page.label 
            const isDisabled = !isUrl

            const styleIsDisabled = isDisabled ? "cursor-not-allowed opacity-50" : "cursor-pointer"
            const styleIsActive = isActive ?  "bg-royal-blue text-white" : ""

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
        loadDataProduct()
    }

    function updateURL() {
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.history.replaceState({}, '', newUrl);
    }

    showFilterLimit()
    loadDataProduct()
    loadDataCategory()
</script>
@endsection