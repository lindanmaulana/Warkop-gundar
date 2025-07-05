@php
// Fungsi helper untuk memeriksa apakah rute saat ini cocok dengan nama rute yang diberikan
$isActive = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-royal-blue' : 'bg-royal-blue/70';
@endphp

@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Manajemen Kategori</h2>
    <p class="text-dark-blue mt-1">Kelola kategori produk untuk memudahkan pengelompokan menu makanan dan minuman.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Daftar Kategori</h2>
        <a href="{{ route('dashboard.categories.create') }}" class="flex items-center rounded px-3 py-1 text-white bg-green-500 hover:bg-green-300 cursor-pointer">
            Tambah
        </a>
    </div>

    @if(session('success'))
    <div id="alert" class="bg-green-200 rounded p-4">
        <p class="text-green-700 font-semibold">
            {{session('success')}}
        </p>
    </div>
    @endif

    <div class="bg-white p-2 rounded-lg shadow-md shadow-dark-blue/10 py-4">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-400  *:border-b *:border-dark-blue/10">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal p-2">Nama</th>
                <th class="font-normal p-2">Deskripsi</th>
                <th class="font-normal p-2 text-center">Aksi</th>
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
                        <div class="flex items-center justify-center gap-3 *:text-sm">
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="text-royal-blue font-medium cursor-pointer">Edit</a>
                            <form action="{{ route('category.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 font-medium cursor-pointer">Hapus</button>
                            </form>
                        </div>
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


@section('script')

    <script>
        const alertComponent = document.getElementById('alert')

        const handleHideAlert = (alert) => {
            if(alert) {
                setTimeout(() => {
                    alert.style.display = "none"
                }, 1500);
            }
        }

        handleHideAlert(alertComponent)
    </script>

@endsection