@extends('layouts.auth')

@section('content')
<div class="shadow-md shadow-gray-300 px-4 py-6 w-[400px] min-h-[400px] flex flex-col items-center justify-center rounded-md">
    <h2 class="text-lg font-semibold flex items-center gap-2"><x-icon name="warkopgundar" /> Warkop Gundar</h2>
    <form method="POST" action="{{ route('auth.login')}}" class="w-full rounded-md py-10 px-8">
        @csrf
        <h3 class="text-xl font-semibold">Wellcome Back!</h3>
        <p class="text-sm text-black/60">Warkop favoritmu, selalu ada untukmu.</p>
        <div class="space-y-6 w-full py-8">
            <div class="space-y-4">
                <label for="email" class="block w-full text-sm">
                    <input type="email" placeholder="example@gmail.com" name="email" class="w-full outline-none bg-soft-blue-gray border-b active:border-black focus:border-black border-black/50 px-2 pb-2">
                </label>
                <label for="password" class="block w-full text-sm">
                    <input type="password" placeholder="******" name="password" class="w-full outline-none bg-soft-blue-gray border-b active:border-black focus:border-black border-black/50 px-2 pb-2">
                </label>
            </div>
            <div class="space-y-1">
                <button class="w-full bg-black rounded-md text-white py-2 text-xs font-semibold cursor-pointer">Login Now</button>
                <p class="text-sm text-black/60">Baru di sini? Yuk, <a href="{{ route('auth.register') }}" class="text-royal-blue">daftar gratis!</a></p>
            </div>
        </div>
    </form>
</div>
@endsection