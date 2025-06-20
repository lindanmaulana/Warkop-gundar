@extends('layouts.auth')

@section('content')
<div class="w-[70%]">
    <h2 class="text-lg font-semibold">Warkop Gundar</h2>
    <form method="POST" action="{{ route('auth.login')}}" class="w-full rounded-md py-10">
        @csrf
        <h3 class="text-xl font-semibold">Wellcome Back!</h3>
        <p class="text-sm text-black/60">Warkop favoritmu, selalu ada untukmu.</p>
        <div class="space-y-6 w-full py-8">
            <div class="space-y-4">
                <label for="username" class="block w-full text-sm">
                    <input type="text" placeholder="username..." name="email" class="w-full outline-none bg-soft-blue-gray border-b active:border-black focus:border-black border-black/50 px-2 pb-2">
                </label>
                <label for="password" class="block w-full text-sm">
                    <input type="password" placeholder="******" name="password" class="w-full outline-none bg-soft-blue-gray border-b active:border-black focus:border-black border-black/50 px-2 pb-2">
                </label>
            </div>
            <div class="space-y-1">
                <button class="w-full bg-black rounded-md text-white py-2 text-xs font-semibold">Login Now</button>
                <p class="text-sm text-black/60">Baru di sini? Yuk, <a href="{{ route('auth.register') }}" class="text-royal-blue">daftar gratis!</a></p>
            </div>
        </div>
    </form>
</div>
@endsection