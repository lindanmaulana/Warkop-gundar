@extends('app')

<header class="w-full fixed top-0 right-0 z-50">
    <div class="container max-w-6xl mx-auto flex items-center justify-between py-8">
        <h2 class="text-primary flex items-center gap-1 font-medium text-xl"><x-icon name="warkopgundar" class="size-6" /> Warkop <span class="text-secondary">Gundar</span></h2>
        <ul class="flex items-center gap-4 ">
            <li class="text-secondary">
                <a href="">About Us</a>
            </li>
            <li class="text-secondary">
                <a href="">Our Product</a>
            </li>
            <li class="text-secondary">
                <a href="">Delivery</a>
            </li>
        </ul>

        <div class="flex items-center gap-3">
            <label for="" class="relative bg-white rounded-full max-w-[140px]">
                <x-icon name="search" class="absolute size-5 top-1/2 -translate-y-1/2 left-2" />
                <input type="text" class="w-full h-full pl-8 outline-none rounded-full py-1">
            </label>
            <a href=""><x-icon name="shopping-cart" /></a>
        </div>
    </div>
</header>

<main class="">
    @yield('content')
</main>