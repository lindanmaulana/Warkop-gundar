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
            <article
                data-aos="fade-up"
                data-aos-duration="{{ 500 + ($loop->index * 100) }}"
                class="flex flex-col h-auto sm:h-[380px] md:h-[400px] lg:h-[420px] xl:h-[450px] bg-white border border-primary/20 p-4 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-xl hover:border-primary/50 space-y-3">
                <div class="relative w-full h-2/3 overflow-hidden rounded-lg">
                    <figure class="w-full h-full">
                        @if($product->image_url)
                        <img
                            src="{{ asset('storage/'. $product->image_url) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105">
                        @else
                        <img
                            src="/images/image-placeholder.png"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover object-center transition-transform duration-300 ease-in-out hover:scale-105">
                        @endif
                    </figure>
                    <span class="absolute top-2 left-2 bg-primary/80 text-white text-xs font-semibold px-2 py-0.5 rounded-full z-10">{{ $product->category->name }}</span>
                </div>

                <div class="flex flex-col flex-grow justify-between gap-2 pt-1">
                    <h3 class="text-xl text-secondary font-extrabold line-clamp-2 leading-tight">
                        {{ $product->name }}
                    </h3>
                    <span class="text-2xl font-bold text-primary">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex items-center justify-between mt-auto">
                    <span class="bg-gray-100 text-secondary font-semibold px-3 py-1.5 rounded-full text-sm shadow-inner">
                        Stok {{ $product->stock }}
                    </span>
                    <button
                        onclick="handleAddToCart(this)"
                        data-user-id="{{ auth()->user()->id }}"
                        data-product-id="{{ $product->id }}"
                        data-product-category="{{ $product->category }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $product->price }}"
                        data-product-image="{{ $product->image_url }}"
                        class="bg-primary text-white rounded-full p-2.5 cursor-pointer shadow-md
                   transition-all duration-300 ease-in-out hover:bg-royal-blue/90 hover:scale-110" {{-- Warna disesuaikan --}}>
                        <x-icon name="shopping-cart" class="size-5" /> {{-- Ukuran ikon sedikit lebih besar --}}
                    </button>
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