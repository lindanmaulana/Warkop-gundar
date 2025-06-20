@extends('app')

<main class="h-screen w-full bg-soft-blue-gray">
    <section class="w-full">
        <div class="flex h-full">
            <div class="relative w-full h-full bg-gray-800 flex items-center justify-center">
                <div class="px-40 h-[300px] space-y-4">
                    <x-icon name="coffee" class="w-16 h-16 text-white" />
                    <h2 class="text-white font-semibold text-4xl">Hello <br> Warkop Gundar! 👋</h2>
                    <p class="text-white/80">Nikmati kemudahan dalam memesan makanan & minuman favoritmu.
                        Proses cepat, tanpa antri, langsung dari genggamanmu!
                    </p>
                    <p class="absolute bottom-4 text-sm text-white/50">© 2025 Warkop Gundar. Semua hak dilindungi.</p>
                </div>
            </div>
            <div class="w-full h-full max-w-1/3 flex items-center justify-center">
                @yield('content')
            </div>
        </div>
    </section>
</main>