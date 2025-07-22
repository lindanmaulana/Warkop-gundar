@extends('layouts.auth')

@section('content')
<div class="shadow-md shadow-gray-300 bg-secondary px-4 py-6 w-[400px] min-h-[400px] flex flex-col items-center justify-center rounded-md">
    <h2 class="text-2xl font-bold text-primary flex items-center gap-2"><x-icon name="coffee" class="size-7" /> Warkop <span class="text-white">Gundar</span></h2>
    <form method="POST" action="{{ route('auth.register')}}" class="w-full rounded-md py-10 px-8">
        @csrf
        <h3 class="text-xl font-semibold text-white">Gabung Sekarang!</h3>
        <p class="text-sm text-white/80">Warkop favoritmu, selalu ada untukmu.</p>
        <div class="space-y-6 w-full py-8">
            <div class="space-y-4">
                <label for="name" class="block w-full text-sm">
                    <input type="text" placeholder="name..." name="name" class="w-full outline-none bg-soft-blue-gray/50 border-b active:border-black focus:border-black border-black/50 p-2 rounded-md active:bg-soft-blue-gray focus:bg-soft-blue-gray">
                    @error('name')
                    <span class="text-xs text-red-500 px-2">{{ $message }}</span>
                    @enderror
                </label>
                <label for="email" class="block w-full text-sm">
                    <input type="email" placeholder="example@gmail.com" name="email" class="w-full outline-none bg-soft-blue-gray/50 border-b active:border-black focus:border-black border-black/50 p-2 rounded-md active:bg-soft-blue-gray focus:bg-soft-blue-gray">
                    @error('email')
                    <span class="text-xs text-red-500 px-2">{{ $message }}</span>
                    @enderror
                </label>
                <label for="password" class="block w-full text-sm">
                    <input type="password" placeholder="password***" name="password" class="w-full outline-none bg-soft-blue-gray/50 border-b active:border-black focus:border-black border-black/50 p-2 rounded-md active:bg-soft-blue-gray focus:bg-soft-blue-gray">
                    @error('password')
                    <span class="text-xs text-red-500 px-2">{{ $message }}</span>
                    @enderror
                </label>
                <label for="password-confirmation" class="block w-full text-sm">
                    <input type="password" placeholder="confirm password***" name="password_confirmation" class="w-full outline-none bg-soft-blue-gray/50 border-b active:border-black focus:border-black border-black/50 p-2 rounded-md active:bg-soft-blue-gray focus:bg-soft-blue-gray">
                    @error('password_confirmation')
                    <span class="text-xs text-red-500 px-2">{{ $message }}</span>
                    @enderror
                </label>
            </div>
            <div class="space-y-1">
                <button class="w-full bg-soft-blue-gray hover:bg-soft-blue-gray/80 rounded-md text-secondary p-3 text-base font-semibold cursor-pointer hover:scale-x-105">Register</button>
                <p class="text-sm text-white/60">Sudah punya akun?, <a href="{{ route('auth.login') }}" class="text-primary">Masuk disini</a></p>
            </div>
        </div>
    </form>
</div>
@endsection