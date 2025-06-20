@extends('layouts.dashboard')

@section('header')
<div class="py-10">
    <h2 class="text-xl font-semibold text-dark-blue">Dashboard</h2>
</div>
@endsection

@section('content')
<div>
    <h2 class="text-lg font-semibold text-dark-blue">Overview</h2>
    <div class="grid grid-cols-2 gap-6 py-4">
        <div class="bg-royal-blue p-5 border-dark-blue/20 rounded-3xl space-y-5">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-white text-base">Coffe</h3>
                <x-icon name="coffee" class="text-white" />
            </div>
            <div>
                <h3 class="text-white">Total</h3>
                <p class="text-2xl text-white">10</p>
            </div>
        </div>
        <div class="bg-white border border-dark-blue/20 p-5 rounded-3xl space-y-5">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-royal-blue text-base">Mie</h3>
                <x-icon name="coffee" class="text-royal-blue" />
            </div>
            <div>
                <h3 class="text-royal-blue">Total</h3>
                <p class="text-2xl text-royal-blue">30</p>
            </div>
        </div>
    </div>
</div>
@endsection