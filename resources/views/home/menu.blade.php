@extends('layouts.home')

@section('content')
<section class="min-h-[400px] pt-28 pb-20 bg-no-repeat" style="background-image: url('/images/bg-menu.png');">
    <div class="container max-w-6xl mx-auto py-24">
        <h2 class="text-secondary text-5xl font-semibold text-center tracking-widest">MENU</h2>

        <article class="flex justify-center gap-10 mt-18">
            <article class="w-full">
                <h3 class="text-3xl font-medium tracking-widest border-t border-t-black border-b border-b-black/30 py-1">MINUMAN</h3>

                <ul class="p-9 space-y-4">
                    @foreach($productsCoffe as $product)
                    <li class="flex items-center justify-between">
                        <div>
                            <h4 class="text-secondary text-base italic">{{ $product->name }}</h4>
                            <p>{{ $product->description }}</p>
                        </div>

                        <span class="font-semibold text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>

            <div class="block w-[1px] min-h-96 bg-black">

            </div>

            <article class="w-full">
                <h3 class="text-3xl font-medium tracking-widest border-t border-t-black border-b border-b-black/30 py-1">MAKANAN</h3>

                <ul class="p-9 space-y-4">
                    @foreach($productsFood as $product)
                    <li class="flex items-center justify-between">
                        <div>
                            <h4 class="text-secondary text-base italic">{{ $product->name }}</h4>
                            <p>{{ $product->description }}</p>
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
    <div class="container max-w-6xl mx-auto">
        <h2 class="text-secondary text-5xl font-semibold text-center tracking-widest">MENU TERSEDIA</h2>

        <article class="grid grid-cols-4 gap-4 py-10">
            @foreach($products as $product)
            <article data-aos="fade-up" data-aos-duration="{{ 500 + ($loop->index * 100) }}" class="h-[300px] bg-white hover:border-2 border-primary/30 hover:bg-primary p-4 rounded-xl shadow-lg space-y-3">
                <div class="h-[70%]">
                    <span></span>
                    <figure class="rounded-lg overflow-hidden">
                        @if($product->image_url)
                        <img src="{{ asset('storage/'. $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <img src="/images/image-placeholder.png" alt="{{ $product->name }}" class="w-full h-full object-cover object-center">
                        @endif
                    </figure>
                </div>

                <div class="flex items-center justify-between">
                    <h3 class="text-lg text-secondary font-semibold truncate max-w-36">{{ $product->name }}</h3>
                    <span class="text-xl font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="bg-primary px-2 py-px text-sm text-soft-blue-gray">Stok {{ $product->stock }}</span>
                    <button onclick="handleAddToCart(this)" data-user-id="{{ auth()->user()->id }}" data-product-id="{{ $product->id }}" data-product-category="{{ $product->category }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->price }}" data-product-image="{{ $product->image_url }}" class="bg-primary rounded-full p-2 cursor-pointer"><x-icon name="shopping-cart" class="size-4 text-soft-blue-gray" /> </button>
                </div>
            </article>
            @endforeach
        </article>
    </div>
</section>
@endsection


@section('script')
@parent
<script>
    const alertComponent = document.getElementById('alert')

    function handleAddToCart(buttonElement) {
        const userId = buttonElement.dataset.userId;
        const productId = buttonElement.dataset.productId;
        const productName = buttonElement.dataset.productName;
        const productPrice = parseFloat(buttonElement.dataset.productPrice);
        const productImage = buttonElement.dataset.productImage;
        const productCategory = JSON.parse(buttonElement.dataset.productCategory)

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
                image_url: productImage,
                category: productCategory.name,
                qty: 1
            })
        }

        Swall.fire({
            title: "Berhasil!",
            text: `Menu ${productName} telah ditambahkan ke keranjang.`,
            icon: "success"
        })

        mainLocalStorage()
        showTotalCart()
    }

    const handleHideAlert = (alert) => {
        if (alert) {
            setTimeout(() => {
                alert.style.display = "none"
            }, 1500);
        }
    }

    handleHideAlert(alertComponent)
</script>
@endsection