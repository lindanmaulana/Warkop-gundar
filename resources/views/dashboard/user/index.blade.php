@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Manajemen Pengguna</h2>
    <p class="text-dark-blue mt-1">Kelola data pengguna yang memiliki akses ke sistem, termasuk admin dan staf yang bertugas.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Daftar Pengguna</h2>
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
                <th class="font-normal p-2">Role</th>
                <th class="font-normal p-2">Status Aktif</th>
                <th class="font-normal p-2">Tgl Daftar</th>
                <th class="font-normal p-2 text-center">Aksi</th>
            </thead>
            <tbody>
                @if($users->isNotEmpty())
                <?php $no = 1; ?>
                @foreach($users as $user)
                <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                    <td class="py-4 px-6">{{ $no++ }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $user->name }}</td>
                    <td class="px-2 py-4 text-dark-blue">{{ $user->role }}</td>
                    <td class="px-2 py-4 text-dark-blue">
                        @if($user->is_email_verified == 1)
                        <bold class="text-royal-blue bg-royal-blue/20 px-2 py-1 rounded">Aktif</bold>
                        @else
                        <bold class="text-red-500 bg-red-200 px-2 py-1 rounded">Tidak Aktif</bold>
                        @endif
                    </td>
                    <td class="px-2 py-4 text-dark-blue">{{ $user->created_at->format('d M Y H:i') }}</td>
                    <td class=" py-4 px-6">
                        <div class="flex items-center justify-center gap-3 *:text-sm">
                            <a href="{{ route('dashboard.categories.edit', $user->id) }}" class="text-royal-blue font-medium cursor-pointer">Edit</a>
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


@section("script")
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const urlParams = new URLSearchParams(window.location.search)
</script>
@endsection