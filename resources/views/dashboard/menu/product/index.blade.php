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
                    <option value="" {{ empty(request('category')) ? 'selected' : ''}}>All</option>

                    @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
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
            <tbody>
                @if($products->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($products as $product)
                <tr class=" hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                    <td class="py-4 px-6">{{ $no++ }}</td>
                    <td class="px-2 py-4 text-dark-blue" id="table-image">
                        @if($product->image_url)
                        <img src="{{ asset('storage/'. $product->image_url )}}" alt="{{ $product->name }}" class="w-24">
                        @else
                        <img src="/images/image-placeholder.png" alt="{{ $product->name }}" class="w-24">
                        @endif
                    </td>
                    <td class="px-2 py-4 text-dark-blue">{{ $product->name }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $product->category->name }}</td>
                    <td class="px-2 py-4 text-dark-blue">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $product->stock }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $product->description }}</td>
                    <td class="px-2 py-4 text-dark-blue">
                        <div class="flex items-center justify-center gap-3 *:text-sm">
                            <a href="{{ route('dashboard.menu.products.edit', $product->id) }}" class="text-royal-blue font-medium cursor-pointer">Edit</a>
                            <a href="{{ route('dashboard.menu.products.detail', $product->id) }}" class="text-green-500 font-medium cursor-pointer">Detail</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-red-500 font-medium cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Data Product tidak tersedia.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection


@section('script')
<script>
    let cartLocalStorage = localStorage.getItem('cart')
    let cart = cartLocalStorage ? JSON.parse(cartLocalStorage) : []

    function handleAddToCart(buttonElement) {
        const userId = buttonElement.dataset.userId;
        const productId = buttonElement.dataset.productId;
        const productName = buttonElement.dataset.productName;
        const productPrice = parseFloat(buttonElement.dataset.productPrice);

        const exisItem = cart.findIndex(item => item.userId === userId && item.productId === productId)

        if (exisItem > -1) {
            cart[exisItem].qty += 1
            cart[exisItem].totalPrice += productPrice

        } else {
            cart.push({
                userId,
                productId,
                productName,
                price: productPrice,
                totalPrice: productPrice,
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

    function mainLocalStorage() {
        const cartNew = JSON.stringify(cart)
        localStorage.setItem('cart', cartNew)
    }

    document.addEventListener('DOMContentLoaded', () => {
        const categoryFilterForm = document.getElementById('categoryFilterForm')
        const categoryFilter = document.getElementById('category_filter')

        categoryFilter.addEventListener('change', function() {
            const categorySelected = this.value
            const baseUrl = "{{ route('dashboard.menu.products') }}"

            let newUrl = baseUrl

            if (categorySelected !== "") {
                newUrl += "?category=" + categorySelected
            }

            window.location.href = newUrl
        })
    })
</script>
@endsection