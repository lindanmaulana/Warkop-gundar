@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <h2 class="text-3xl font-semibold text-primary">Manajemen Pengguna</h2>
    <p class="text-secondary mt-1">Kelola data pengguna yang memiliki akses ke sistem, termasuk admin dan staf yang bertugas.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-primary">Pengguna</h2>
        <a href="{{ route('dashboard.users', ['page' => 1, 'limit' => 5]) }}" class="bg-dark-blue hover:bg-dark-blue/70 px-3 rounded py-1 text-white flex items-center gap-1 text-sm"><x-icon name="arrow-left" />Back</a>
    </div>
    <div class="flex flex-col gap-4 bg-white p-2 rounded-lg shadow-sm shadow-dark-blue/10">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            <div class="space-y-3">
                <label for="name" class="flex flex-col gap-3">
                    <span class="text-dark-blue font-semibold opacity-50">Nama:</span>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded-sm opacity-50" readonly>
                </label>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <div class="grid grid-cols-2 gap-2">
                    <label for="role" class="flex flex-col gap-3">
                        <span class="text-dark-blue font-semibold opacity-50">Role:</span>
                        <select name="role" id="role" class="border border-green-500 px-4 py-2 rounded-sm">
                            <option value="superadmin" {{$user->role->value == "superadmin" ? "selected" : ""}}>Super Admin</option>
                            <option value="admin" {{$user->role->value == "admin" ? "selected" : ""}}>Admin</option>
                            <option value="customer" {{$user->role->value == "customer" ? "selected" : ""}}>Customer</option>
                        </select>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->role) }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded-sm opacity-50" readonly>
                    </label>
                    <label for="is_suspended" class="flex flex-col gap-3">
                        @php
                            $statusAccountText = $user->is_suspended ? "Ditangguhkan" : "Aktif";
                            $styleLabel = $user->is_suspended ? "text-red-500" : "text-green-500"
                        @endphp
                        <span class="{{$styleLabel}} font-semibold">Status Akun:</span>
                        <input type="text" id="is_suspended" name="is_suspended" value="{{ $statusAccountText }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded-sm opacity-50" readonly>
                    </label>
                </div>

                <label for="created_at" class="flex flex-col gap-3 rounded-md">
                    <span class="text-dark-blue font-semibold opacity-50">Tgl Daftar:</span>
                    <input type="text" id="created_at" name="created_at" value="{{ old('created_at', $user->created_at) }}" class="w-full border border-dark-blue/20 px-4 py-2 rounded-sm opacity-50" readonly />
                </label>
                @error('description')
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