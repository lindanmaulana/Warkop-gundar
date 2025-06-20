@php
    // Fungsi helper untuk memeriksa apakah rute saat ini cocok dengan nama rute yang diberikan
    $isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'text-royal-blue' : 'text-royal-blue/50';
@endphp

@extends('app')
<main class="bg-soft-blue-gray">
    <section class="w-full flex gap-4">
        <div class="w-full lg:max-w-[250px] flex h-screen lg:relative translate-x-0 bg-white">
            <div class="relative w-full px-4 py-8">
                <h2 class="flex items-center gap-2 text-xl font-semibold text-dark-blue"><x-icon name="warkopgundar" class="w-10 h-10" /> Warkop Gundar</h2>

                <ul class="w-full py-14 flex flex-col items-center">
                    <li class="w-full group">
                        <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard') }}">
                            <x-icon name="home" />
                            Dashboard
                        </a>
                    </li>
                    <li class="w-full group">
                        <a href="{{ route('dashboard.menu') }}" class="w-full flex items-center gap-4 text-lg font-semibold pl-6 py-2 rounded-md group-hover:bg-royal-blue/20 transition-all duration-300 ease-in-out {{ $isActive('dashboard.menu') }}">
                            <x-icon name="toolskitchen" />
                            Menu
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
        <div class="flex flex-col w-full h-screen max-h-screen px-4">
            <div class="w-full h-auto sticky top-0 inset-0">
                @yield('header')
            </div>
            <div class="flex-1 overflow-y-scroll py-4">
                @yield('content')
            </div>
        </div>
    </section>
</main>