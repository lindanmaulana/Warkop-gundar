@extends('layouts.home')

@section('content')
<section class="mt-20">
    <div class="container max-w-6xl mx-auto py-14 px-4 lg:px-0">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-secondary">Edit Profile</h2>
                <p class="text-secondary/80 max-w-80">Masukkan informasi yang valid agar proses pesanan lebih mudah</p>
            </div>

            <a href="{{ route('home') }}" class="hidden md:block bg-secondary px-4 py-1 text-white rounded">Kembali</a>
        </div>
        <form method="POST" action="{{ route('profile.update', $user->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6 py-8">
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
    </div>
</section>
@endsection