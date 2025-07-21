@extends('layouts.home')


@section('content')
<section>
    <div class="container max-w-6xl mx-auto">
        <h2>Transaction History</h2>

        @if(session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <p class="text-green-700 ">
                <strong class="bold">Success!</strong> {{session('success')}}
            </p>
        </div>
        @endif
        
    </div>
</section>
@endsection