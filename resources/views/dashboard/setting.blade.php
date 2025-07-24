@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <h2 class="text-3xl font-semibold text-primary">Pengaturan Profile</h2>
    <p class="text-secondary mt-1">Perbarui informasi akun seperti nama atau data profil lainnya.</p>
</div>
@endsection

@section('content')
<div class="pl-4">
    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <p class="text-green-700 ">
            <strong class="bold">Success!</strong> {{session('success')}}
        </p>
    </div>
    @endif
    <h2 class="text-lg font-semibold text-secondary">Profile Setting</h2>
    <div class="w-full grid grid-cols-2 gap-6 py-4">
        <div class=" space-y-6">
            <label for="" class="block space-y-2">
                <span class="block">Nama</span>
                <input type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-lg px-4 py-3 border border-secondary/20" readonly>
            </label>
            <label for="" class="block">
                <span class="block">Email</span>
                <input type="text" value="{{ old('name', $user->email) }}" class="w-full rounded-lg px-4 py-3 border border-secondary/20" readonly>
            </label>
        </div>
        <form action="{{ route('setting.profile.update', $user->id) }}" class="w-full space-y-6 bg-white rounded-lg shadow-lg p-4" method="POST">
            @csrf
            @method('PATCH')
            <div class="space-y-3">
                <label for="name" class="flex flex-col gap-3">
                    <span class="text-secondary font-semibold">Nama:</span>
                    <input type="text" id="name" name="name" placeholder="Nama baru" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Update</button>
                <button type="reset" class="px-4 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
            </div>
        </form>
    </div>
    <div class="flex flex-col gap-4 items-center justify-center py-8">
        <h3 class="text-lg font-semibold text-secondary">Ubah password</h3>
        <form action="{{ route('setting.profile.update.password') }}" class="w-full max-w-1/2 space-y-6 bg-white rounded-lg shadow-lg p-4" method="POST">
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
@endsection