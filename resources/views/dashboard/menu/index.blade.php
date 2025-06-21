@php
    // Fungsi helper untuk memeriksa apakah rute saat ini cocok dengan nama rute yang diberikan
    $isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <h2 class="text-lg font-semibold text-dark-blue">Menu</h2>
        <div class="grid grid-cols-2 gap-6 py-4">
            <ul class="flex items-center gap-4">
                <li class="rounded-md px-4 py-1 {{ $isActive('dashboard.menu') }}">
                    <a href="" class="flex items-center gap-4 text-white "><x-icon name="coffee" /> Coffe</a>
                </li>
                <li class="rounded-md px-4 py-1 {{ $isActive('dashboard.menu.mie') }}">
                    <a href="" class="flex items-center gap-4 text-white "><x-icon name="noodles" /> Mie</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        @yield('menu')
    </div>
</div>
@endsection