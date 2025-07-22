@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md shadow-secondary/20 bg-white">
    <h2 class="text-3xl font-semibold text-primary">Manajemen Kategori</h2>
    <p class="text-secondary mt-1">Kelola kategori produk untuk memudahkan pengelompokan menu makanan dan minuman.</p>
</div>
@endsection

@section('content')
@php
$isAdmin = Auth::user()->role->value == "admin"
@endphp

<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-primary">Daftar Kategori</h2>
        @if($isAdmin)
        <a href="{{ route('dashboard.categories.create') }}" class="flex items-center rounded px-3 py-1 text-white bg-green-500 hover:bg-green-300 cursor-pointer">
            Tambah
        </a>
        @endif
    </div>

    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <p class="text-green-700 ">
            <strong class="bold">Success!</strong> {{session('success')}}
        </p>
    </div>
    @endif


    <div class="bg-white p-2 rounded-lg shadow-md shadow-dark-blue/10 py-4">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-400  *:border-b *:border-dark-blue/10">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal p-2">Nama</th>
                <th class="font-normal p-2">Deskripsi</th>
                @if($isAdmin)
                <th class="font-normal p-2 text-center">Aksi</th>
                @endif
            </thead>
            <tbody>
                @if($categories->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($categories as $category)
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                    <td class="py-4 px-6">{{ $no++ }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $category->name }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $category->description }}</td>
                    <td class=" py-4 px-6">
                        @if($isAdmin)
                        <div class="flex items-center justify-center gap-3 *:text-sm">
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="text-royal-blue font-medium cursor-pointer">Edit</a>
                            <form action="{{ route('category.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 font-medium cursor-pointer">Hapus</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="text-center py-4 text-red-500">
                        <p class="flex items-center justify-center gap-2"><x-icon name="package-open" /> Data Category tidak tersedia.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection