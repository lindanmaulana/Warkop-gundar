@extends('layouts.home')

@section('content')
<section class="bg-peach py-10 -mb-68">
    <div class="container max-w-6xl mx-auto h-[800px]">
        <div class="h-full flex items-center justify-center pb-44">
            <div class="space-y-8">
                <h1 data-aos="fade-up" class="text-5xl max-w-[70%] font-semibold text-secondary leading-14">Nikmati <span class="text-primary">Kopi</span> Anda sebelum beraktivitas</h1>
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
</section>

<section>
    <div class="relative container max-w-6xl mx-auto py-10">
        <img src="/images/bg_img_hero.png" alt="" class="absolute -top-4 w-[460px] -left-12">
        <h2 data-aos="fade-right" data-aos-duration="1000" class="relative text-secondary text-3xl font-semibold mb-28 ml-5 after:content[''] after:absolute after:left-30 after:-bottom-2 after:w-16 after:h-1.5 after:rounded after:bg-primary">Menu Terbaru</h2>
        <div class="relative w-full h-[280px] bg-pale-peach rounded-4xl py-10">
            <article class="absolute w-full -top-20 grid grid-cols-3 gap-8 px-10">
                @foreach($productsLatest as $product)
                <article data-aos="fade-up" data-aos-duration="{{ 500 + ($loop->index * 100) }}" class="h-[300px] bg-white border-2 border-primary/30 p-4 rounded-xl shadow-lg space-y-3">
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
                        <button class="bg-primary rounded-full p-2 cursor-pointer"><x-icon name="shopping-cart" class="size-4 text-soft-blue-gray" /> </button>
                    </div>
                </article>
                @endforeach
            </article>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="container max-w-6xl mx-auto">
        <h2 data-aos="fade-up" data-aos-duration="1000" class="relative text-secondary text-3xl text-center font-semibold mb-28 ml-5 after:content[''] after:absolute after:right-1/2 after:translate-x-1/2 after:-bottom-2 after:w-16 after:h-1.5 after:rounded after:bg-primary">Pengiriman Cepat dari Kantin ke Meja Anda</h2>

        <article class="grid grid-cols-3 gap-4">
            <article class="flex flex-col items-center justify-center gap-1">
                <figure data-aos="fade-up" data-aos-duration="1000">
                    <img src="/images/chose-coffe.png" alt="pilih coffe mu" class="w-full h-full">
                </figure>
                <h3 data-aos="fade-up" data-aos-duration="1000" class="text-2xl font-semibold text-secondary">Pilih Kopimu</h3>
                <p data-aos="fade-up" data-aos-duration="1000" class="text-base text-black">Temukan beragam pilihan kopi favoritmu.</p>
            </article>
            <article class="flex flex-col items-center justify-center gap-1">
                <figure data-aos="fade-up" data-aos-duration="1100">
                    <img src="/images/delivery.png" alt="pilih coffe mu" class="w-full h-full">
                </figure>
                <h3 data-aos="fade-up" data-aos-duration="1100" class="text-2xl font-semibold text-secondary">Kami Antarkan ke Mejamu</h3>
                <p data-aos="fade-up" data-aos-duration="1100" class="text-base text-black">Cukup tunggu pesananmu di mejamu.</p>
            </article>
            <article class="flex flex-col items-center justify-center gap-1">
                <figure data-aos="fade-up" data-aos-duration="1200">
                    <img src="/images/coffe-time.png" alt="pilih coffe mu" class="w-full h-full">
                </figure>
                <h3 data-aos="fade-up" data-aos-duration="1200" class="text-2xl font-semibold text-secondary">Nikmati Kopimu</h3>
                <p data-aos="fade-up" data-aos-duration="1200" class="text-base text-black">Kopimu siap dinikmati di mejamu</p>
            </article>
        </article>
    </div>
</section>

<section class="w-full h-[400px] bg-center bg-no-repeat mt-20" style="background-image: url('/images/bg-coffe.jpg')">
    <div class="container max-w-6xl mx-auto">
        <div class="w-full h-full flex items-center justify-evenly">
            <div class="">
                <figure data-aos="fade-right" data-aos-duration="1000" class="h-[440px] -translate-y-16 rounded-xl overflow-hidden border-4 border-peach">
                    <img src="/images/warkopgundar.jpg" alt="warkopgundar" class="w-full h-full object-cover">
                </figure>
            </div>
            <div class="max-w-[360px] space-y-4">
                <h2 data-aos="fade-up" data-aos-duration="1000" class="relative text-secondary text-3xl font-semibold after:content[''] after:absolute after:left-30 after:-bottom-2 after:w-12 after:h-1.5 after:rounded after:bg-primary">Tentang Kita</h2>
                <p data-aos="fade-up" data-aos-duration="1000" class="text-xl text-black font-semibold">Kami menyediakan kopi berkualitas dan siap diantar.</p>
                <p data-aos="fade-up" data-aos-duration="1000" class="text-gray-600 text-base font-thin">Tempat ngopi sederhana dengan pilihan minuman praktis dan suasana akrab. Cocok buat ngobrol, santai, atau sekadar isi waktu.</p>
                <button data-aos="fade-up" data-aos-duration="1000" class="bg-secondary text-primary px-4 rounded-full py-2 text-xs font-semibold">Pesan Sekarang</button>
            </div>
        </div>
    </div>
</section>

<section class="bg-center bg-no-repeat h-[440px]" style="background-image: url('/images/bg-coffe-2.png');">
    <div class="container max-w-6xl mx-auto">
        <div class="w-full h-full flex items-center justify-between">
            <h2 data-aos="fade-right" data-aos-duration="1000" class="text-3xl font-semibold text-white bg-secondary/50 p-4 rounded">Ngopi Bisa Kapan Aja</h2>

            <article class="grid grid-cols-2 gap-12 mr-40">
                <article class="">
                    <h3 data-aos="fade-up" data-aos-duration="1000" class="text-2xl text-primary">WG-SUDIRMAN</h3>
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-semibold text-lg">Senin - Jum'at</p>
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-semibold text-base">06:00 - 17:30</p>
                </article>
                <article class="">
                    <h3 data-aos="fade-up" data-aos-duration="1000" class="text-2xl text-primary">WG-TEBET</h3>
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-semibold text-lg">Senin - Jum'at</p>
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-semibold text-base">06:00 - 18:00</p>
                </article>
                <article class="">
                    <h3 data-aos="fade-up" data-aos-duration="1000" class=" text-2xl text-primary">WG-DEPOK</h3>
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-semibold text-lg">Setiap Hari</p>
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-white font-semibold text-base">06:00 - 21:00</p>
                </article>

                <article data-aos="fade-up" data-aos-duration="1000" class="relative flex items-center justify-center">
                    <span class="bg-primary p-2 rounded text-secondary text-sm">Tanggal Merah Tutup</span>
                    <span class="block absolute top-4 right-1 size-4 bg-green-500 rounded-full animate-pulse"></span>
                </article>
            </article>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="container max-w-6xl mx-auto">
        <h2 data-aos="fade-right" data-aos-duration="1000" class="relative text-secondary text-3xl font-semibold  ml-5 after:content[''] after:absolute after:left-72 after:-bottom-2 after:w-16 after:h-1.5 after:rounded after:bg-primary">Menu Pilihan untuk Kamu</h2>

        <article class="grid grid-cols-3 gap-4 py-20">
            @foreach($productsForYou as $product)
            <article data-aos="fade-up" data-aos-duration="{{ 1000 + ($loop->index * 400) }}" class="h-[300px] bg-white border-2 border-primary/30 p-4 rounded-xl shadow-lg space-y-3">
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
                    <button class="bg-primary rounded-full p-2 cursor-pointer"><x-icon name="shopping-cart" class="size-4 text-soft-blue-gray" /> </button>
                </div>
            </article>
            @endforeach
        </article>

        <div class="flex items-center justify-end">
            <a href="{{ route('home.menu') }}" class="flex items-center justify-end text-primary max-w-40 cursor-pointer group">Menu lainnya <x-icon name="arrow-right" class="mt-1 group-hover:translate-x-2 transition-global" /> </a>
        </div>
    </div>
</section>

<section class="relative w-full h-[360px] my-20">
    <div class="absolute w-2/3 h-full left-0 top-0 bg-center bg-no-repeat -z-10 rounded-r-2xl" style="background-image: url('/images/bg-coffe.jpg')"></div>
    <div class="container max-w-6xl mx-auto overflow-hidden">
        <div class="w-full h-full flex items-center justify-between">
            <div class="space-y-2">
                <h2 data-aos="fade-right" data-aos-duration="1000" class="relative text-secondary text-3xl font-semibold">Dari Hati Pemilik</h2>
                <p data-aos="fade-right" data-aos-duration="1200" class="max-w-[340px] text-gray-600 text-base font-thin">Dari obrolan kecil sampai tawa besar, semuanya bisa dimulai dari secangkir kopi. Itu alasan kami buka warkop ini..</p>
            </div>

            <article class="grid grid-cols-3 gap-12">
                <article data-aos="fade-left" data-aos-duration="1000" class="relative h-[260px] bg-white border-3 border-primary/30 rounded-sm shadow-lg space-y-3">
                    <figure data-aos="fade-left" data-aos-duration="1000" class="rounded-sm overflow-hidden">
                        <img src="/images/pendiri1.jpg" alt="Pendiri Warkop 1" class="w-full h-full object-cover">
                    </figure>
                    <div data-aos="fade-left" data-aos-duration="1000" class="absolute w-[200px] -right-8 bottom-6 bg-pale-peach px-2 py-1 rounded-lg border-2 border-primary/10">
                        <h3 class="text-base font-medium text-secondary">Ero Rohmat</h3>
                        <p class="text-sm text-secondary">Tempat kecil, tapi niat kami besar..</p>
                    </div>
                </article>
                <article data-aos="fade-left" data-aos-duration="1000" class="relative h-[260px] bg-white border-3 border-primary/30 rounded-sm shadow-lg space-y-3">
                    <figure data-aos="fade-left" data-aos-duration="1000" class="rounded-sm overflow-hidden">
                        <img src="/images/pendiri1.jpg" alt="Pendiri Warkop 1" class="w-full h-full object-cover">
                    </figure>
                    <div data-aos="fade-left" data-aos-duration="1000" class="absolute w-[200px] -right-8 bottom-6 bg-pale-peach px-2 py-1 rounded-lg border-2 border-primary/10">
                        <h3 class="text-base font-medium text-secondary">Ero Rohmat</h3>
                        <p class="text-sm text-secondary">Bukan sekadar kopi, tapi tempat pulang...</p>
                    </div>
                </article>
                <article data-aos="fade-left" data-aos-duration="1000" class="relative h-[260px] bg-white border-3 border-primary/30 rounded-sm shadow-lg space-y-3">
                    <figure data-aos="fade-left" data-aos-duration="1000" class="rounded-sm overflow-hidden">
                        <img src="/images/pendiri1.jpg" alt="Pendiri Warkop 1" class="w-full h-full object-cover">
                    </figure>
                    <div data-aos="fade-left" data-aos-duration="1000" class="absolute w-[200px] -right-8 bottom-6 bg-pale-peach px-2 py-1 rounded-lg border-2 border-primary/10">
                        <h3 class="text-base font-medium text-secondary">Ero Rohmat</h3>
                        <p class="text-sm text-secondary">Ngopi tenang, layani sepenuh hati...</p>
                    </div>
                </article>
            </article>
        </div>
    </div>
</section>
@endsection