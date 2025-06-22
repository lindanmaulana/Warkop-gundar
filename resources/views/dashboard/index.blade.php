@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
</div>
@endsection

@section('content')
<div class="">
    <h2 class="text-lg font-semibold text-dark-blue">Overview</h2>
    <div class="grid grid-cols-4 gap-6 py-4">
        <div class="bg-white border border-dark-blue/20 p-5 rounded-3xl space-y-5 *:text-royal-blue">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-base">Category</h3>
            </div>
            <div>
                <h3>Total</h3>
                <p class="text-2xl ">{{ $totalCategory }}</p>
            </div>
        </div>
        @foreach($categories as $category)
            <div class="bg-royal-blue border border-dark-blue/20 p-5 rounded-3xl space-y-5 *:text-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold  text-base">{{ $category->name }}</h3>
                </div>
                <div>
                    <h3 class="">Total Produk</h3>
                    <p class="text-2xl ">{{ $category->product_count }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection