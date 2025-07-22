@extends('layouts.home')

@section('content')
<section class="w-full min-h-[400px] py-20">
    <div class="relative w-full h-[260px] py-24 bg-no-repeat bg-cover bg-bottom -z-10" style="background-image: url('/images/location.jpg');">
        <div class="absolute inset-0 flex items-center justify-center bg-black/50 -z-10"></div>
        <h2 class="text-white text-3xl lg:text-5xl font-semibold text-center tracking-widest">LOKASI KAMI</h2>
    </div>
    <div class="container max-w-6xl mx-auto py-24 px-4 md:px-0 space-y-8">
        <article class="space-y-4">
            <h3 data-aos="fade-right" data-aos-duration="900" class="text-2xl lg:text-3xl text-secondary font-bold">Warkop Gundar - Sudirman <span class="text-lg font-semibold text-green-500">buka senin - jumat</span></h3>
            <div data-aos="fade-up" data-aos-duration="900" class="rounded-xl shadow-xl shadow-secondary/30 overflow-hidden">
                <iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.29852934195!2d106.80404827355432!3d-6.224312310956695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f151dd69c2a9%3A0xbc103ac6336b4e4e!2sGedung%20Direktorat%20Jendral%20Pajak%20Jalan%20Sudirman!5e0!3m2!1sen!2sid!4v1752761403076!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </article>

        <article class="space-y-4">
            <h3 data-aos="fade-right" data-aos-duration="900" class="text-2xl lg:text-3xl text-secondary font-bold">Warkop Gundar - Tebet <span class="text-lg font-semibold text-green-500">buka senin - jumat</span></h3>
            <p class="text-secondary/80">Layanan <span class="bg-red-200 text-red-500 px-2 rounded">online</span> Warkop Gundar <bold class="font-bold">Tebet</bold> sedang diperbaiki. Kunjungi langsung warkopnya di lokasi, kami tetap buka seperti biasa! <span class="font-semibold italic flex items-center gap-2"><x-icon name="coffee" /> Stay happy and enjoy your life with a cup of coffee.</span></p>
            <div data-aos="fade-up" data-aos-duration="900" class="rounded-xl shadow-xl shadow-secondary/30 overflow-hidden">
            <div data-aos="fade-up" data-aos-duration="900" class="rounded-xl shadow-xl shadow-secondary/30 overflow-hidden">
                <iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.035557910991!2d106.82973296713908!3d-6.229560447830404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f38725ee3bc7%3A0xb10413926a31365e!2sKantor%20Pelayanan%20Pajak%20Pratama%20Jakarta%20Tebet!5e0!3m2!1sen!2sid!4v1752761653873!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </article>

        <article class="space-y-4">
            <h3 data-aos="fade-right" data-aos-duration="900" class="text-2xl lg:text-3xl text-secondary font-bold">Warkop Gundar - Depok <span class="text-lg font-semibold text-green-500">buka setiap hari</span></h3>
            <p class="text-secondary/80">Layanan <span class="bg-red-200 text-red-500 px-2 rounded">online</span> Warkop Gundar <bold class="font-bold">Depok</bold> sedang diperbaiki. Kunjungi langsung warkopnya di lokasi, kami tetap buka seperti biasa! <span class="font-semibold italic flex items-center gap-2"><x-icon name="coffee" /> Stay happy and enjoy your life with a cup of coffee.</span></p>
            <div data-aos="fade-up" data-aos-duration="900" class="rounded-xl shadow-xl shadow-secondary/30 overflow-hidden">
                <iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3064075123193!2d106.84158712971012!3d-6.354366612584711!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ec475427cefd%3A0xc4e7eee0f871687!2sUniversity%20Gunadarma%20Campus%20E!5e0!3m2!1sen!2sid!4v1752761842375!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </article>
    </div>
</section>
@endsection