@extends('app')
@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'text-primary font-semibold' : 'text-secondary';
$bgHeader = fn () => request()->routeIs('home.menu') ? 'bg-peach/10 backdrop-blur-xs text-lg' : 'bg-peach text-lg';
@endphp
<div class="flex flex-col">
    <header class="w-full fixed top-0 right-0 z-50 {{ $bgHeader() }}">
        <div class="container max-w-6xl mx-auto flex items-center justify-between py-8">
            <a href="{{ route('home') }}">
                <h2 class="text-primary flex items-center gap-1 font-medium text-xl"><x-icon name="warkopgundar2" class="size-6" /> Warkop <span class="text-secondary">Gundar</span></h2>
            </a>
            <ul class="flex items-center gap-4 ">
                <li class="text-secondary">
                    <a href="{{ route('home') }}" class="{{ $isActive('home') }}">Home</a>
                </li>
                <li class="text-secondary">
                    <a href="">About Us</a>
                </li>
                <li class="text-secondary">
                    <a href="{{ route('home.menu') }}" class="{{ $isActive('home.menu') }}">Our Menu</a>
                </li>
                <li class="text-secondary">
                    <a href="">Delivery</a>
                </li>
            </ul>
            @if(Auth::user())
            <a href="{{ route('home.cart') }}" class="relative"><x-icon name="shopping-cart" />
                <span id="totalCart" class="absolute -top-4 left-0 size-5 bg-green-500 rounded-full p-2 text-white flex items-center justify-center text-sm"></span>
            </a>
            @endif
        </div>
    </header>

    <main class="flex-1 font-poppins-regular">
        @yield('content')
    </main>

    <footer>
        <div class="relative bg-center bg-no-repeat h-[340px] -z-10" style="background-image: url('/images/bg-coffe-3.png');">
            <span class="absolute inset-0 flex items-center justify-center bg-black/60 -z-10"></span>
            <div class="container max-w-6xl mx-auto z-10">
                <div class="w-full h-full flex flex-col justify-between py-8 gap-10">
                    <article class="grid grid-cols-2">
                        <article class="flex items-center flex-col">
                            <h2 data-aos="fade-up" data-aos-duration="1000" class="text-3xl font-semibold text-white">Warkop Gundar</h2>
                            <div class="h-full flex items-center justify-center">
                                <p data-aos="fade-up" data-aos-duration="1000" class="text-white text-center text-sm max-w-sm">
                                    Tempat ngopi sederhana di jantung kota. Warkop Gundar hadir untuk jadi tempat istirahat, ngobrol, dan nikmati kopi dengan harga bersahabat. <span class="block text-white text-2xl">""</span>
                                </p>
                            </div>
                        </article>

                        <article class="space-y-6">
                            <h2 data-aos="fade-up" data-aos-duration="1000" class="text-2xl text-white after:content[''] after:block after:w-12 after:h-1 after:bg-white after:mt-5 after:rounded">HUBUNGI KAMI</h2>
                            <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-thin">Gedung Pajak Sudirman<br>Jl. Jend. Sudirman Kav. 56, Senayan, Jakarta Selatan</p>

                            <ul class="*:text-white max-w-1/2">
                                <li class="grid grid-cols-3">
                                    <h3 data-aos="fade-up" data-aos-duration="1000">Telepon: </h3>
                                    <span data-aos="fade-up" data-aos-duration="1000" class="col-span-2 block">+62 878-7865-9892</span>
                                </li>
                                <li class="grid grid-cols-3">
                                    <h3 data-aos="fade-up" data-aos-duration="1000">Email: </h3>
                                    <span data-aos="fade-up" data-aos-duration="1000" class="col-span-2 block">linmidofficial@gmail.com</span>
                                </li>
                            </ul>
                        </article>
                    </article>
                    <p class="text-white/80 text-center text-sm">© 2025 WarkoGundar. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</div>

@section('script')
<script>
    let cartLocalStorage = localStorage.getItem('cart')
    let cart = cartLocalStorage ? JSON.parse(cartLocalStorage) : []

    const componentTotalCart = document.getElementById('totalCart')

    const showTotalCart = () => {
        if (cart.length === 0) {
            componentTotalCart.innerHTML = ""
        } else {
            componentTotalCart.innerHTML = cart.length
        }
    }

    function mainLocalStorage() {
        const cartNew = JSON.stringify(cart)
        localStorage.setItem('cart', cartNew)
    }

    showTotalCart()
</script>
@endsection