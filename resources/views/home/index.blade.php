@extends('layouts.home')

@section('content')
<section class="relative bg-peach py-20 lg:py-28 overflow-hidden">
    <div class="container max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:flex lg:items-center lg:justify-between lg:gap-16 xl:gap-24 min-h-[calc(100vh-100px)]">
            <div class="lg:w-1/2 space-y-6 lg:space-y-8 text-center lg:text-left py-10">
                <h1
                    data-aos="fade-up"
                    data-aos-duration="800"
                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-secondary leading-tight lg:leading-none">
                    Nikmati <span class="text-primary">Kopi</span> Anda<br class="hidden lg:inline"> sebelum beraktivitas
                </h1>
                <p
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="200"
                    class="text-secondary/80 text-lg lg:text-xl max-w-xl mx-auto lg:mx-0">
                    Tingkatkan produktivitas dan bangun suasana hati Anda dengan segelas kopi di pagi hari
                </p>
                <div
                    data-aos="fade-up"
                    data-aos-duration="1200"
                    data-aos-delay="400"
                    class="flex items-center gap-4 justify-center lg:justify-start">
                    <a href="{{ route('home.cart') }}" class="bg-secondary rounded-full px-6 py-3.5 font-semibold text-white text-base flex items-center gap-2 transition-all duration-300 hover:bg-secondary/90 hover:scale-105">
                        Pesan Sekarang <x-icon name="shopping-cart" class="size-5" />
                    </a>
                    <a href="{{ route('home.menu', ['page' => 1, 'limit' => 5]) }}" class="rounded-full px-6 py-3.5 text-primary font-semibold text-base flex items-center gap-2 border border-primary transition-all duration-300 hover:bg-primary/10 hover:scale-105">
                        Menu lainnya
                    </a>
                </div>
            </div>

            <div class="relative lg:w-1/2 flex items-center justify-center pt-12 lg:pt-0">
                <figure
                    data-aos="fade-left"
                    data-aos-duration="1200"
                    data-aos-delay="600"
                    class="relative w-full max-w-[500px] h-auto aspect-square flex items-center justify-center">
                    <img
                        src="{{ asset('images/img-hero.png') }}"
                        alt="Hero banner - Kopi Nikmat"
                        class="w-full h-full object-contain drop-shadow-2xl">
                </figure>

                <img
                    data-aos="fade-down"
                    data-aos-duration="1000"
                    data-aos-delay="800"
                    src="{{ asset('images/bg_img_hero.png') }}"
                    alt="Coffee beans decorative"
                    class="absolute -top-4 -right-8 w-48 h-auto opacity-70 rotate-12 hidden lg:block" {{-- Sembunyikan di layar kecil --}}>
                <img
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="900"
                    src="{{ asset('images/bg_img_hero.png') }}" {{-- Bisa juga gambar dekoratif lain --}}
                    alt="Coffee cup decorative"
                    class="absolute -bottom-8 -left-8 w-40 h-auto opacity-60 -rotate-12 hidden lg:block" {{-- Sembunyikan di layar kecil --}}>
            </div>
        </div>
    </div>
</section>

<section class="mb-20 sm:mb-16 md:mb-10">
    <div class="relative container max-w-6xl mx-auto py-10">
        <img src="/images/bg_img_hero.png" alt="" class="absolute -top-4 w-[460px] -left-12">
        <h2 data-aos="fade-right" data-aos-duration="1000" class="relative text-secondary text-3xl font-bold mb-28 ml-5 after:content[''] after:absolute after:left-30 after:-bottom-2 after:w-16 after:h-1.5 after:rounded after:bg-primary">Menu Terbaru</h2>
        <div class="relative w-full min-h-[200px] lg:min-h-[280px] bg-pale-peach rounded-4xl py-10">
            <article class="absolute w-full -top-20 flex overflow-x-auto md:overflow-hidden md:grid grid-cols-3 gap-8 px-10 py-10">
                @foreach($productsLatest as $product)
                <article
                    data-aos="fade-up"
                    data-aos-duration="{{ 500 + ($loop->index * 100) }}"
                    class="flex flex-col min-w-[300px] md:w-full h-auto sm:h-[380px] md:h-[400px] lg:h-[420px] xl:h-[450px] bg-white border border-primary/20 p-4 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-xl hover:border-primary/50 space-y-3">
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
    </div>
</section>

<section class="py-24 md:py-20">
    <div class="container max-w-6xl mx-auto">
        <h2 data-aos="fade-up" data-aos-duration="1000" class="relative text-secondary text-3xl text-center font-semibold mb-28 ml-5 after:content[''] after:absolute after:right-1/2 after:translate-x-1/2 after:-bottom-2 after:w-16 after:h-1.5 after:rounded after:bg-primary">Pengiriman Cepat dari Kantin ke Meja Anda</h2>

        <article class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
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

<section class="w-full min-h-[400px] bg-center bg-no-repeat bg-cover md:bg-auto mt-20 py-20" style="background-image: url('/images/bg-coffe.jpg')">
    <div class="container max-w-6xl mx-auto px-4 md:px-0">
        <div class="w-full h-full flex flex-col md:flex-row items-center justify-evenly">
            <div class="">
                <figure data-aos="fade-right" data-aos-duration="1000" class="h-[440px] -translate-y-16 rounded-xl overflow-hidden border-4 border-peach">
                    <img src="/images/warkopgundar.jpg" alt="warkopgundar" class="w-full h-full object-cover">
                </figure>
            </div>
            <div class="w-full lg:w-1/2 max-w-md space-y-4 text-center lg:text-left"> {{-- max-w-md untuk kontrol lebar, text-center/left responsif --}}
                <h2
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    class="relative text-secondary text-3xl sm:text-4xl font-extrabold pb-3">
                    Tentang Kita
                    <span class="absolute left-1/2 transform -translate-x-1/2 bottom-0 w-16 h-1.5 rounded bg-primary lg:left-0 lg:translate-x-0"></span> {{-- Garis bawah responsif --}}
                </h2>
                <p
                    data-aos="fade-up"
                    data-aos-duration="1100"
                    class="text-xl text-black font-semibold leading-relaxed">
                    Kami menyediakan kopi berkualitas dan siap diantar.
                </p>
                <p
                    data-aos="fade-up"
                    data-aos-duration="1200"
                    class="text-gray-700 text-base font-normal leading-relaxed">
                    Tempat ngopi sederhana dengan pilihan minuman praktis dan suasana akrab. Cocok buat ngobrol, santai, atau sekadar isi waktu. Kami berdedikasi menyajikan kopi terbaik dengan suasana yang hangat.
                </p>
                <a href="{{ route('home.cart') }}"
                    data-aos="fade-up"
                    data-aos-duration="1300"
                    class="bg-secondary text-primary px-4 py-2 rounded-full text-base font-semibold transition-all duration-300 ease-in-out hover:bg-secondary/90 hover:scale-105">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<section class="bg-center bg-no-repeat h-[440px]" style="background-image: url('/images/bg-coffe-2.png');">
    <div class="container max-w-6xl mx-auto px-4 md:px-0">
        <div class="w-full h-full flex flex-col md:flex-row items-center justify-around md:justify-between">
            <h2 data-aos="fade-right" data-aos-duration="1000" class="text-3xl font-semibold text-white bg-secondary/50 p-4 rounded">Ngopi Bisa Kapan Aja</h2>

            <article class="grid grid-cols-2 gap-12 lg:mr-40">
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
    <div class="container max-w-6xl mx-auto px-4 md:px-0">
        <h2 data-aos="fade-right" data-aos-duration="1000" class="relative text-secondary text-3xl font-bold  ml-5 after:content[''] after:absolute after:left-72 after:-bottom-2 after:w-16 after:h-1.5 after:rounded after:bg-primary">Menu Pilihan untuk Kamu</h2>

        <article class="flex overflow-x-auto md:overflow-hidden md:grid md:grid-cols-2 lg:grid-cols-3 gap-6 py-20">
            @foreach($productsForYou as $product)
            <article
                data-aos="fade-up"
                data-aos-duration="{{ 500 + ($loop->index * 100) }}"
                class="flex flex-col min-w-[300px] md:w-auto h-auto sm:h-[380px] md:h-[400px] lg:h-[420px] xl:h-[450px] bg-white border border-primary/20 p-4 rounded-xl shadow-lg transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-xl hover:border-primary/50 space-y-3">
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

        <div class="flex items-center justify-end">
            <a href="{{ route('home.menu', ['page' => 1, 'limit' => 5]) }}" class="flex items-center justify-end text-primary max-w-40 cursor-pointer group">Menu lainnya <x-icon name="arrow-right" class="mt-1 group-hover:translate-x-2 transition-global" /> </a>
        </div>
    </div>
</section>

<section class="relative w-full min-h-[360px] my-20 py-10">
    <div class="absolute w-4/5 lg:w-2/3 h-full left-0 top-0 bg-center bg-no-repeat -z-10 rounded-r-2xl" style="background-image: url('/images/bg-coffe.jpg')"></div>
    <div class="container max-w-6xl mx-auto overflow-hidden px-4 md:px-0">
        <div class="w-full h-full flex flex-col md:flex-row items-center gap-6 md:gap-0 justify-between">
            <div class="space-y-2">
                <h2 data-aos="fade-right" data-aos-duration="1000" class="relative text-secondary text-3xl font-extrabold">Dari Hati <span class="text-primary">Pemilik</span></h2>
                <p data-aos="fade-right" data-aos-duration="1200" class="max-w-[340px] text-secondary text-base font-thin">Dari obrolan kecil sampai tawa besar, semuanya bisa dimulai dari secangkir kopi. Itu alasan kami buka warkop ini..</p>
            </div>

            <article class="w-full flex overflow-x-auto md:grid grid-cols-3 gap-12 md:px-10">
                <article data-aos="fade-left" data-aos-duration="1000" class="min-w-[200px] md:w-full relative h-[260px] bg-white border-3 border-primary/30 rounded-sm shadow-lg space-y-3">
                    <figure data-aos="fade-left" data-aos-duration="1000" class="rounded-sm overflow-hidden">
                        <img src="/images/pendiri1.jpg" alt="Pendiri Warkop 1" class="w-full h-full object-cover">
                    </figure>
                    <div data-aos="fade-left" data-aos-duration="1000" class="absolute w-[200px] -right-8 bottom-6 bg-pale-peach px-2 py-1 rounded-lg border-2 border-primary/10">
                        <h3 class="text-base font-medium text-secondary">Ero Rohmat</h3>
                        <p class="text-sm text-secondary">Tempat kecil, tapi niat kami besar..</p>
                    </div>
                </article>
                <article data-aos="fade-left" data-aos-duration="1000" class="min-w-[200px] md:w-full relative h-[260px] bg-white border-3 border-primary/30 rounded-sm shadow-lg space-y-3">
                    <figure data-aos="fade-left" data-aos-duration="1000" class="rounded-sm overflow-hidden">
                        <img src="/images/pendiri1.jpg" alt="Pendiri Warkop 1" class="w-full h-full object-cover">
                    </figure>
                    <div data-aos="fade-left" data-aos-duration="1000" class="absolute w-[200px] -right-8 bottom-6 bg-pale-peach px-2 py-1 rounded-lg border-2 border-primary/10">
                        <h3 class="text-base font-medium text-secondary">Tahudin</h3>
                        <p class="text-sm text-secondary">Bukan sekadar kopi, tapi tempat pulang...</p>
                    </div>
                </article>
                <article data-aos="fade-left" data-aos-duration="1000" class="min-w-[200px] md:w-full relative h-[260px] bg-white border-3 border-primary/30 rounded-sm shadow-lg space-y-3">
                    <figure data-aos="fade-left" data-aos-duration="1000" class="rounded-sm overflow-hidden">
                        <img src="/images/pendiri1.jpg" alt="Pendiri Warkop 1" class="w-full h-full object-cover">
                    </figure>
                    <div data-aos="fade-left" data-aos-duration="1000" class="absolute w-[200px] -right-8 bottom-6 bg-pale-peach px-2 py-1 rounded-lg border-2 border-primary/10">
                        <h3 class="text-base font-medium text-secondary">Iyan Rudiansah</h3>
                        <p class="text-sm text-secondary">Ngopi tenang, layani sepenuh hati...</p>
                    </div>
                </article>
            </article>
        </div>
    </div>
</section>
@endsection