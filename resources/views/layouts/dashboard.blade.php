    @php
    $isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'text-white bg-royal-blue' : 'text-royal-blue/50';
    $isActiveMobile = fn (string $routeName) => request()->routeIs($routeName) ? 'text-white' : 'text-white/50';
    @endphp

    @extends('app')
    <main class="bg-soft-blue-gray">
        <section class="w-full flex gap-4">
            <!-- side bar -->
            <div class="w-full hidden md:max-w-[250px] md:flex h-screen lg:relative translate-x-0 bg-white">
                <div class="relative w-full px-4 py-8">
                    <h2 class="flex items-center gap-2 text-xl font-semibold text-dark-blue"><x-icon name="warkopgundar" class="w-10 h-10" /> Warkop Gundar</h2>

                    <ul class="w-full py-14 flex flex-col items-center">
                        <li class="w-full group">
                            <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard') }}"">
                                <x-icon name='home' />
                            Dashboard
                            </a>
                        </li>
                        <li class="w-full group">
                            <a href="{{ route('dashboard.categories') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard.categories') }}">
                                <x-icon name="category" />
                                Category
                            </a>
                        </li>
                        <li class="w-full group">
                            <a href="{{ route('dashboard.menu.products', ['page' => 1, 'limit' => 5]) }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard.menu.products') }}">
                                <x-icon name="toolskitchen" />
                                Menu
                            </a>
                        </li>
                        <li class="w-full group">
                            <a href="{{ route('dashboard.orders') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard.orders') }}">
                                <x-icon name="shopping-cart" />
                                Order
                            </a>
                        </li>
                        <li class="w-full group">
                            <a href="{{ route('dashboard.payments') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard.payments') }}">
                                <x-icon name="credit-card" />
                                Payment
                            </a>
                        </li>
                        <li class="w-full group">
                            <a href="{{ route('dashboard.setting') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard.setting') }}">
                                <x-icon name="settings" />
                                Setting
                            </a>
                        </li>
                    </ul>

                    <div class="w-full absolute bottom-4 left-0 px-4">
                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class=" w-full flex items-center gap-4 text-base text-red-400 hover:text-red-500 font-semibold pl-6 py-2 rounded-md hover:bg-red-100 transition-all duration-300 ease-in-out">
                            <x-icon name="logout" />
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navbar -->
            <div class="fixed md:hidden bottom-2 translate-x-1/2 right-1/2 w-full max-w-[96%] z-50 mx-auto px-4 bg-royal-blue rounded">
                <ul class="w-full flex items-center justify-between gap-2">
                    <li class="w-full group ">
                        <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-4 text-lg font-semibold py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActiveMobile('dashboard') }}">
                            <x-icon name="home" />
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->role->value === 'admin')
                    <li class="w-full group ">
                        <a href="{{ route('dashboard.categories') }}" class="w-full flex items-center justify-center gap-4 text-lg font-semibold py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActiveMobile('dashboard.admin.category') }}">
                            <x-icon name="category" />
                        </a>
                    </li>
                    @endif
                    <li class="w-full group ">
                        <a href="{{ route('dashboard.menu.products') }}" class="w-full flex items-center justify-center gap-4 text-lg font-semibold py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActiveMobile('dashboard.menu.products') }}">
                            <x-icon name="toolskitchen" />
                        </a>
                    </li>
                    <li class="w-full group ">
                        <a href="{{ route('dashboard.orders') }}" class="w-full flex items-center justify-center gap-4 text-lg font-semibold py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActiveMobile('dashboard.orders') }}">
                            <x-icon name="shopping-cart" />
                        </a>
                    </li>
                    <li class="w-full group ">
                        <a href="{{ route('dashboard.setting') }}" class="w-full flex items-center justify-center gap-4 text-lg font-semibold py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActiveMobile('dashboard.setting') }}">
                            <x-icon name="settings" />
                        </a>
                    </li>
                </ul>

                <div class="hidden w-full absolute bottom-4 left-0 px-4">
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class=" w-full flex items-center gap-4 text-base text-red-400 hover:text-red-500 font-semibold pl-6 py-2 rounded-md hover:bg-red-100 transition-all duration-300 ease-in-out">
                        <x-icon name="logout" />
                        Logout
                    </a>
                </div>
            </div>

            <div class="flex flex-col w-full h-screen max-h-screen px-4">
                <div class="w-full h-auto sticky top-0 inset-0 flex items-center justify-between">
                    @yield('header')
                </div>
                <div class="w-full flex-1 overflow-y-auto py-4">
                    @yield('content')
                </div>
            </div>
        </section>
    </main>


    <!-- <script>

    </script> -->