@extends('layouts.home')

@section('content')
<section class="bg-peach min-h-screen pt-20">
    <div class="container max-w-6xl mx-auto">
        <div class="flex items-center justify-center h-full">
            <div class="space-y-8">
                <h1 class="text-5xl max-w-[70%] font-semibold text-secondary leading-14">Nikmati <span class="text-primary">Kopi</span> Anda sebelum beraktivitas</h1>
                <p class="text-secondary/70 max-w-[60%]">Tingkatkan produktivitas dan bangun suasana hati Anda dengan segelas kopi di pagi hari</p>
                <div class="flex items-center gap-2">
                    <button class="bg-secondary rounded-full px-4 py-3 font-semibold text-white text-xs flex items-center gap-1">Pesan Sekarang <x-icon name="shopping-cart" class="size-4" /></button>
                    <button class="rounded-full px-4 py-3 text-primary font-semibold text-xs flex items-center gap-1">Menu lainnya</button>
                </div>
            </div>

            <div class="">
                <figure class="w-full h-[416px]">
                    <img src="{{ asset('images/img-hero.png') }}" alt="Hero banner" class="w-full h-full object-contain">
                </figure>

                <img src="{{ asset('images/bg_img_hero.png') }}" alt="Coffe" class="absolute top-0 right-0 h-56">
            </div>
        </div>
    </div>

    <div class="container max-w-6xl mx-auto">
        <h2 class="relative text-secondary text-3xl font-semibold after:content[''] after:absolute after:right-0 after:w-10">Terpopuler & Terbaru</h2>
    </div>
</section>
@endsection