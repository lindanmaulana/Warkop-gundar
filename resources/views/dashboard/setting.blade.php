@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Pengaturan Profile</h2>
    <p class="text-dark-blue mt-1">Perbarui informasi akun seperti nama atau data profil lainnya.</p>
</div>
@endsection

@section('content')
<div class="">
    <h2 class="text-lg font-semibold text-dark-blue">Profile Setting</h2>
    <div class="w-full flex items-center gap-6 py-4">
        <form action="{{ route('setting.profile.update', $user->id) }}" class="w-full space-y-6" method="POST">
            @csrf
            @method('PATCH')

            <div class="space-y-3">
                <label for="name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Nama:</span>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name)}}" class="w-full border-2 border-dark-blue/20 px-4 py-1 rounded-sm">
                </label>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold">Email:</span>
                    <input type="text" id="name" value="{{ old('email', $user->email ) }}" readonly class="w-full border-2 border-dark-blue/20 px-4 text-dark-blue/70 py-1 rounded-sm">
                </label>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-1 rounded cursor-pointer bg-royal-blue text-white font-semibold text-sm">Update</button>
                <button type="reset" class="px-4 py-1 rounded cursor-pointer bg-red-500 text-white font-semibold text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection