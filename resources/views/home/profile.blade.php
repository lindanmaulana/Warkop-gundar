@extends('layouts.home')

@section('content')
<section class="mt-20">
    <div class="container max-w-6xl mx-auto py-14 px-4 lg:px-0">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-secondary">
                    Edit Profile
                    @if($user->is_email_verified)
                    <span class="text-sm text-green-500">Terverfikasi</span>
                    @else
                    <span class="text-sm text-red-500">Belum Verifikasi</span>
                    @endif
                </h2>
                <p class="text-secondary/80 max-w-80">Masukkan informasi yang valid agar proses pesanan lebih mudah</p>
            </div>

            @if(session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <p class="text-green-700 ">
                    <strong class="bold">Success!</strong> {{session('success')}}
                </p>
            </div>
            @endif

            <div class="flex items-center gap-2">
                @if(!$user->is_email_verified)
                <a href="{{ route('auth.otp') }}" class="text-sm text-red-500 border border-red-500 rounded px-2 py-1 hover:bg-red-500 hover:text-white hover:border-none transition-global">Verifikasi</a>
                @endif
                <a href="{{ route('home') }}" class="hidden md:block bg-secondary px-4 py-1 text-white rounded">Kembali</a>
            </div>
        </div>
        <div class="w-full grid grid-cols-2 gap-4">
            <form method="POST" action="{{ route('profile.update', $user->id) }}" class="w-full gap-6 py-8">
                @csrf
                @method('PATCH')

                <div class="bg-white shadow-md p-6 rounded-lg space-y-4">
                    <label for="" class="block space-y-2">
                        <span class="block text-lg font-semibold text-secondary">Nama</span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-200 rounded-lg px-4 py-2 text-secondary">
                    </label>
                    <label for="" class="block space-y-2">
                        <span class="block text-lg font-semibold text-secondary">Email</span>
                        <input type="email" name="email" value="{{ old('name', $user->email) }}" readonly class="w-full bg-gray-200 rounded-lg px-4 py-2 text-secondary/70 cursor-not-allowed">
                    </label>
                    <button type="submit" class="w-full px-4 py-2 bg-secondary rounded-md font-semibold cursor-pointer hover:scale-105 transition-global text-white">Save</button>
                </div>
            </form>

            <form action="{{ route('setting.profile.update.password') }}" class="w-full space-y-6 bg-white rounded-lg shadow-lg p-4" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-3">
                    <label for="current_password" class="flex flex-col gap-3">
                        <span class="text-secondary font-semibold">Password Lama:</span>
                        <input type="password" id="current_password" name="current_password" placeholder="Password lama" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                    </label>
                    @error('current_password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    <label for="new_password" class="flex flex-col gap-3">
                        <span class="text-secondary font-semibold">Password baru:</span>
                        <input type="password" id="new_password" name="new_password" placeholder="Password baru" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                    </label>
                    @error('new_password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    <label for="new_password_confirmation" class="flex flex-col gap-3">
                        <span class="text-secondary font-semibold">Konfirmasi Password:</span>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm Password" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                    </label>
                    @error('new_password_confirmation')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="px-4 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Update</button>
                    <button type="reset" class="px-4 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection