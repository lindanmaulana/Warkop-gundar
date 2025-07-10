@extends('app')
@php
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'text-primary font-semibold' : 'text-secondary';
$bgHeader = fn () => request()->routeIs('home.menu', 'home.order.detail', 'home.order.payment') ? 'bg-peach/10 backdrop-blur-xs text-lg' : 'bg-peach text-lg';
@endphp
<div class="flex flex-col">
    <header class="w-full fixed top-0 right-0 z-50 transition-all duration-300 {{ $bgHeader() }} shadow-sm">
        <div class="container max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <h2 class="text-primary flex items-center gap-1.5 font-bold text-2xl lg:text-3xl">
                    <x-icon name="warkopgundar2" class="size-7 lg:size-8" /> Warkop <span class="text-secondary">Gundar</span>
                </h2>
            </a>

            <div class="hidden lg:flex items-center gap-8">
                <ul class="flex items-center gap-6 xl:gap-8 font-medium">
                    <li>
                        <a href="{{ route('home') }}"
                            class="relative text-secondary text-lg hover:text-primary transition-colors duration-200 after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300 hover:after:w-full {{ $isActive('home') ? 'font-semibold text-primary' : '' }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('home.menu') }}"
                            class="relative text-secondary text-lg hover:text-primary transition-colors duration-200
                                after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300 hover:after:w-full
                                {{ $isActive('home.menu') ? 'font-semibold text-primary' : '' }}">Our Menu</a>
                    </li>
                </ul>

                @if(Auth::user())
                <div class="relative flex items-center gap-3">
                    <a href="{{ route('home.cart') }}" class="relative text-secondary hover:text-primary transition-colors duration-200">
                        <x-icon name="shopping-cart" class="size-6 lg:size-7" />
                        <span id="totalCart" class="absolute -top-1 -right-2 size-5 bg-green-500 rounded-full text-white text-xs font-bold flex items-center justify-center p-0.5 leading-none transform scale-90">
                        </span>
                    </a>
                    <button onclick="handleMenu()" class="focus:outline-none rounded-full transition-all duration-200 hover:bg-gray-100 p-1">
                        <x-icon name="profile" class="size-8 lg:size-9 text-secondary cursor-pointer hover:text-secondary/70 transition-colors duration-200" />
                    </button>

                    <ul id="menu" class="absolute top-12 right-0 min-w-44 bg-peach rounded-lg shadow-xl py-2 opacity-0 pointer-events-none transition-all duration-300 transform scale-95 origin-top-right">
                        <li>
                            <a href="{{ route('home.profile') }}" class="block px-4 py-2 text-green-600 hover:bg-peach/80 hover:text-green-800 transition-colors duration-200 text-sm font-medium">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('home.order') }}" class="block px-4 py-2 text-green-600 hover:bg-peach/80 hover:text-green-800 transition-colors duration-200 text-sm font-medium">Pesanan</a>
                        </li>
                        <li>
                            <form action="{{ route('auth.logout') }}" method="post">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-peach/80 hover:text-red-800 transition-colors duration-200 text-sm font-medium">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            <div class="lg:hidden flex items-center gap-3">
                @if(Auth::user())
                <a href="{{ route('home.cart') }}" class="relative text-secondary hover:text-primary transition-colors duration-200">
                    <x-icon name="shopping-cart" class="size-6" />
                    <span id="totalCartMobile" class="absolute -top-1 -right-2 size-5 bg-green-500 rounded-full text-white text-xs font-bold flex items-center justify-center p-0.5 leading-none transform scale-90">
                    </span>
                </a>
                @endif
                <button id="mobileMenuButton" class="text-secondary focus:outline-none p-1 rounded-md hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-75 z-40 hidden transition-opacity duration-300 opacity-0"></div>
        <nav id="mobileMenu" class="fixed top-0 right-0 w-64 h-full bg-peach shadow-lg transform translate-x-full transition-transform duration-300 z-50">
            <div class="p-6">
                <button id="closeMobileMenuButton" class="absolute top-4 right-4 text-secondary hover:text-primary transition-colors duration-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <ul class="flex flex-col gap-6 text-xl font-semibold mt-16">
                    <li>
                        <a href="{{ route('home') }}" class="text-secondary hover:text-primary transition-colors duration-200 {{ $isActive('home') ? 'text-primary font-bold' : '' }}">Home</a>
                    </li>
                    <li>
                        <a href="#" class="text-secondary hover:text-primary transition-colors duration-200">About Us</a>
                    </li>
                    <li>
                        <a href="{{ route('home.menu') }}" class="text-secondary hover:text-primary transition-colors duration-200 {{ $isActive('home.menu') ? 'text-primary font-bold' : '' }}">Our Menu</a>
                    </li>
                    @if(Auth::user())
                    <hr class="border-t border-gray-300 my-4">
                    <li>
                        <a href="{{ route('home.profile') }}" class="text-green-600 hover:text-green-800 transition-colors duration-200">Profile</a>
                    </li>
                    <li>
                        <a href="{{ route('home.order') }}" class="text-green-600 hover:text-green-800 transition-colors duration-200">Pesanan</a>
                    </li>
                    <li>
                        <form action="{{ route('auth.logout') }}" method="post">
                            @csrf
                            <button type="submit" class="block w-full text-left text-red-600 hover:text-red-800 transition-colors duration-200">Logout</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>

    <main class="flex-1 font-poppins-regular">
        @yield('content')
    </main>

    <footer>
        <div class="relative w-full py-16 md:py-20 overflow-hidden"
            style="background-image: url('/images/bg-coffe-3.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">

            <span class="absolute inset-0 bg-black/70"></span>

            <div class="container max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 md:gap-8 lg:gap-16 text-white text-center md:text-left">

                    <div class="flex flex-col items-center md:items-start space-y-4">
                        <h2 data-aos="fade-up" data-aos-duration="1000" class="text-primary flex items-center gap-2 font-bold text-3xl lg:text-4xl">
                            <x-icon name="warkopgundar2" class="size-8 lg:size-10" /> Warkop <span class="text-secondary">Gundar</span>
                        </h2>
                        <p data-aos="fade-up" data-aos-duration="1100" class="text-white/90 text-sm md:text-base max-w-xs leading-relaxed">
                            Tempat ngopi sederhana di jantung kota. Warkop Gundar hadir untuk jadi tempat istirahat, ngobrol, dan nikmati kopi dengan harga bersahabat.
                        </p>
                    </div>

                    <div class="flex flex-col items-center md:items-start space-y-5">
                        <h3 data-aos="fade-up" data-aos-duration="1000" class="relative text-2xl font-bold pb-3">
                            HUBUNGI KAMI
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1.5 bg-primary rounded lg:left-0 lg:transform-none"></span>
                        </h3>
                        <div data-aos="fade-up" data-aos-duration="1100" class="text-white/90 text-sm md:text-base font-light space-y-2">
                            <p>Gedung Pajak Sudirman<br>Jl. Jend. Sudirman Kav. 56, Senayan, Jakarta Selatan</p>
                            <div class="flex items-center gap-3">
                                <x-icon name="phone" class="size-5 text-primary" />
                                <span>+62 878-7865-9892</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-icon name="mail" class="size-5 text-primary" />
                                <span>linmidofficial@gmail.com</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center md:items-start space-y-5">
                        <h3 data-aos="fade-up" data-aos-duration="1000" class="relative text-2xl font-bold pb-3">
                            IKUTI KAMI
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1.5 bg-primary rounded lg:left-0 lg:transform-none"></span>
                        </h3>
                        <div data-aos="fade-up" data-aos-duration="1100" class="flex gap-4">
                            <a href="https://facebook.com" target="_blank" class="text-white/80 hover:text-primary transition-colors duration-200">
                                <x-icon name="facebook" class="size-8" />
                            </a>
                            <a href="https://twitter.com" target="_blank" class="text-white/80 hover:text-primary transition-colors duration-200">
                                <x-icon name="twitter" class="size-8" />
                            </a>
                            <a href="https://instagram.com" target="_blank" class="text-white/80 hover:text-primary transition-colors duration-200">
                                <x-icon name="instagram" class="size-8" />
                            </a>
                        </div>
                    </div>

                </div>

                <div class="border-t border-white/20 mt-12 pt-8 text-center">
                    <p class="text-white/70 text-sm">© 2025 WarkopGundar. All Rights Reserved.</p>
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

    const showTotalCart = () => {
        if (cart.length === 0) {
            componentTotalCart.style.display = "none"
        } else {
            componentTotalCart.innerHTML = cart.length
        }
    }

    function mainLocalStorage() {
        const cartNew = JSON.stringify(cart)
        localStorage.setItem('cart', cartNew)
    }

    const componentMenu = document.getElementById('menu');
    componentMenu.classList.add('opacity-0', 'pointer-events-none', 'scale-95');

    const handleMenu = () => {
        if (componentMenu.classList.contains('opacity-0')) {
            componentMenu.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            componentMenu.classList.add('opacity-100', 'scale-100');
        } else {
            componentMenu.classList.remove('opacity-100', 'scale-100');
            componentMenu.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        }
    };

    document.addEventListener('click', (event) => {
        const profileButton = document.querySelector('button[onclick="handleMenu()"]');

        if (
            componentMenu &&
            !componentMenu.contains(event.target) &&
            !profileButton.contains(event.target)
        ) {
            componentMenu.classList.remove('opacity-100', 'scale-100');
            componentMenu.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        }
    });

    showTotalCart()
</script>
@endsection