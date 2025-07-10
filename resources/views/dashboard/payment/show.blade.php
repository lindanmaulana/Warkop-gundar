@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Detail Pembayaran</h2>
    <p class="text-dark-blue mt-1">Lihat detail informasi metode pembayaran yang dipilih.</p>
</div>
@endsection

@section('content')
<div class="bg-white p-6 rounded-md shadow-md shadow-dark-blue/10 space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-semibold text-royal-blue">Informasi Pembayaran</h3>
        <a href="{{ route('dashboard.payments') }}" class="text-sm text-gray-600 hover:underline">← Kembali ke daftar</a>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Nama -->
        <div>
            <p class="text-sm text-gray-500">Nama Metode</p>
            <h4 class="text-lg font-semibold text-dark-blue">{{ $payment->name }}</h4>
        </div>

        <!-- Status -->
        <div>
            <p class="text-sm text-gray-500">Status</p>
            <span class="inline-block px-3 py-1 rounded text-white text-sm font-medium 
                {{ $payment->is_active ? 'bg-royal-blue' : 'bg-red-500' }}">
                {{ $payment->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
        </div>

        <!-- QR Code -->
        <div class="col-span-2">
            <p class="text-sm text-gray-500">QR Code</p>
            <div class="mt-2">
                @if($payment->qr_code_url)
                    <img src="{{ asset('storage/' . $payment->qr_code_url) }}" alt="QR {{ $payment->name }}" class="h-48 rounded shadow-sm border">
                @else
                    <img src="/images/image-placeholder.png" alt="Tidak ada gambar" class="h-48 rounded border">
                @endif
            </div>
        </div>

        <!-- Tanggal Dibuat -->
        <div>
            <p class="text-sm text-gray-500">Dibuat pada</p>
            <p class="text-dark-blue">{{ $payment->created_at->format('d M Y H:i') }}</p>
        </div>

        <!-- Terakhir Diperbarui -->
        <div>
            <p class="text-sm text-gray-500">Diperbarui pada</p>
            <p class="text-dark-blue">{{ $payment->updated_at->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
