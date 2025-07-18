@extends('layouts.home')

@section('content')
<section class="w-full min-h-[400px] pt-28 pb-20 bg-no-repeat bg-cover" style="background-image: url('/images/bg-menu.png');">
    <div class="container max-w-6xl mx-auto py-24 px-4 md:px-0">
        <h2 class="text-secondary text-3xl lg:text-5xl font-semibold text-center tracking-widest">MENU TERBARU</h2>

        <article class="grid grid-cols-1 md:grid-cols-5 gap-10 mt-18">
            <article class="col-span-2 w-full">
                <h3 class="text-3xl font-medium text-center tracking-widest border-t border-t-black border-b border-b-black/30 py-1">MINUMAN</h3>

                <ul class="p-9 space-y-4">
                    @foreach($productsCoffe as $product)
                    <li class="flex items-center justify-between">
                        <div>
                            <h4 class="text-secondary text-base italic">{{ $product->name }}</h4>
                        </div>

                        <span class="font-semibold text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>

            <div class="w-[1px] min-h-96 bg-black mx-auto hidden md:block">

            </div>

            <article class="col-span-2 w-full">
                <h3 class="text-3xl font-medium text-center tracking-widest border-t border-t-black border-b border-b-black/30 py-1">MAKANAN</h3>

                <ul class="p-9 space-y-4">
                    @foreach($productsFood as $product)
                    <li class="flex items-center justify-between">
                        <div>
                            <h4 class="text-secondary text-base italic">{{ $product->name }}</h4>
                        </div>

                        <span class="font-semibold text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
        </article>
    </div>
</section>


<section class="my-20">
    <div class="container max-w-6xl mx-auto space-y-4 px-4 md:px-">
        <h2 class="text-secondary text-3xl lg:text-5xl font-semibold text-center tracking-widest">MENU TERSEDIA</h2>
        <div class="flex items-center justify-end">
            <input id="filter-search" type="text" placeholder="cari..." class="border border-secondary/40 rounded-lg px-4 py-1">
        </div>
        <article id="menu-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-2 gap-y-4">

        </article>

        <div class="flex items-start md:items-center flex-col md:flex-row justify-between gap-4 py-6">
            <div class="flex items-center gap-2">
                <select name="" id="filter-limit" class="border border-dark-blue/20 rounded-md px-3 py-1 text-sm font-semibold"></select>
                <p class="text-sm font-semibold opacity-50">data per halaman</p>
            </div>

            <div id="filter-page" class="flex items-center gap-2">

            </div>
        </div>
    </div>
</section>
@endsection


@section('script')
@parent
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const userId = document.querySelector('meta[name="user-id"]')?.content;

    let urlParams = new URLSearchParams(window.location.search)

    const dataFilterLimit = [5, 10, 15, 20]
    
    
    function debounce(fn, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    }
    
    const filterSearch = document.getElementById("filter-search")
    filterSearch.defaultValue = urlParams.get("keyword") ? urlParams.get("keyword").toString() : ""
    filterSearch.addEventListener("input", debounce(function() {
        const value = this.value

        switch(value) {
            case "":
                urlParams.delete("keyword")
            break
            default:
                urlParams.set("keyword", value)
            break
        }

        loadDataProduct()
        updateURL()
    }, 1000))

    document.getElementById("filter-limit").addEventListener("change", function() {
        const value = this.value
        urlParams.set("limit", value)
        urlParams.set("page", 1)

        updateURL()
        loadDataProduct()
    })

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

    const loadDataProduct = async () => {
        try {
            const response = await fetch(`/api/v1/menus?${urlParams.toString()}`, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })

            const result = await response.json()
            const currentPage = result.data.current_page
            const pages = result.data.links
            const data = result.data.data
            const pagination = result.data.pagination

            showFilterPage(pagination.links)
            showMenuList(pagination.data)

            return result
        } catch (err) {
            console.log({
                err
            })
        }
    }

    const showMenuList = (dataMenu) => {
        const menuList = document.getElementById("menu-list")
        menuList.innerHTML = ""

        const list = dataMenu.map((menu, index) => {
            let imageUrl = menu.image_url ? `/storage/${menu.image_url}` : "/images/image-placeholder.png"
            const price = Intl.NumberFormat("id-ID", {
                currency: "IDR",
                style: "currency",
                maximumFractionDigits: 0
            }).format(menu.price)

            const aosDuration = 500 + (index + 1) * 100
            return (
                `
                <article
                    data-aos="fade-up"
                    data-aos-duration="${aosDuration}"
                    class="col-span-1 flex flex-col h-auto sm:h-[300px] md:h-[400px] lg:h-[420px] xl:h-[400px] bg-white border border-primary/20 p-4 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-xl hover:border-primary/50 space-y-3">
                    <div class="relative w-full h-2/4 overflow-hidden rounded-lg">
                        <figure class="w-full h-full">
                            <img
                                src="${imageUrl}"
                                alt="${menu.name}"
                                class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105">
                        </figure>
                        <span class="absolute top-2 left-2 bg-primary/80 text-white text-xs font-semibold px-2 py-0.5 rounded-full z-10">${menu.category.name}</span>
                    </div>

                    <div class="flex flex-col flex-grow justify-center gap-2 pt-1">
                        <h3 class="text-lg text-secondary font-extrabold line-clamp-2 leading-tight">
                            ${menu.name}
                        </h3>
                        <span class="text-xl font-bold text-primary">
                            ${price}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-auto">
                        <span class="bg-gray-100 text-secondary font-semibold px-3 py-1.5 rounded-full text-sm shadow-inner">
                            Stok ${menu.stock}
                        </span>
                        <button
                            onclick="handleCart(${userId}, '${menu.id}', '${menu.name}', '${menu.price}', '${menu.image_url}', '${menu.category.name}')"
                            class="bg-primary text-white rounded-full p-2.5 cursor-pointer shadow-md
                    transition-all duration-300 ease-in-out hover:bg-royal-blue/90 hover:scale-110">
                            <x-icon name="shopping-cart" class="size-5" />
                        </button>
                    </div>
                </article>
                `
            )
        }).join(" ")

        menuList.innerHTML = list
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
            const styleIsActive = isActive ? "bg-primary text-white" : ""

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
        loadDataProduct()
    }

    function handleCart(userId, productId, productName, productPrice, productImage, productCategoryName) {
        const price = parseFloat(Number(productPrice));
        const idUser = userId.toString()

        const exisItem = cart.findIndex(item => item.userId === userId && item.productId === productId)

        if (exisItem > -1) {
            cart[exisItem].qty += 1
            cart[exisItem].totalPrice += price
        } else {
            cart.push({
                userId: idUser,
                productId,
                productName,
                price,
                totalPrice: price,
                image_url: productImage,
                category: productCategoryName,
                qty: 1
            })
        }

        Swall.fire({
            title: "Berhasil!",
            text: `Menu ${productName} telah ditambahkan ke keranjang.`,
            icon: "success"
        })

        mainLocalStorage()
    }

    const updateURL = () => {
        const newURL = `${window.location.pathname}?${urlParams.toString()}`

        window.history.replaceState({}, '', newURL)
    }

    showFilterLimit()
    loadDataProduct()
</script>
@endsection