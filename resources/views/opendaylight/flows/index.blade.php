@extends('opendaylight.app')

@foreach($flows as $flow)
    @if($flow['main-received'] <= 101)
@section('refresh')
    <meta http-equiv="refresh" content="30"/>
@endsection
@else
@section('refresh')
@endsection
@endif
@endforeach

@section('content')
    @foreach($flows as $flow)
        @if($flow['main-received'] >= 75 && $flow['main-received']<= 100)
            <div class="w-screen h-16 bg-yellow-200">System yogun calismakta</div>
        @elseif($flow['main-received'] > 100)
            <div class="w-screen h-16 bg-red-300">Sistem saldiri altinda</div>
        @endif
    @endforeach

    <div class="flex justify-center items-start lg:w-3/4 bg-gray-200 h-screen py-4 flex-wrap">
        @foreach($flows as $flow)

            <div class="h-64 w-56 bg-white rounded shadow-lg mx-2 flex justify-between flex-col">
                <h2 class="text-lg font-semibold leading-tight text-center uppercase py-3">{{ $flow['id'] }}</h2>

                <div>
                    <p class="py-1 px-3 text-sm uppercase"><span
                            class="font-semibold">Connectors:</span> {{ count($flow['connectors']) }}</p>
                    <p class="py-1 px-3 text-sm uppercase"><span
                            class="font-semibold">Total Received:</span> {{ $flow['packets']['received'] }}</p>
                    <p class="py-1 px-3 text-sm uppercase"><span
                            class="font-semibold">Total Transmitted:</span> {{ $flow['packets']['transmitted'] }}</p>
                    <p class="py-1 px-3 text-sm uppercase"><span
                            class="font-semibold">Main Connected:</span> {{ $flow['main-connected'] }}</p>
                    <p class="py-1 px-3 text-sm uppercase"><span
                            class="font-semibold">From Another Flow:</span> {{ $flow['another-flow'] }}</p>
                </div>
                <div>
                    <a href="/flows/{{ $flow['id'] }}"
                       class="w-full py-3 font-semibold text-sm uppercase bg-gray-200 hover:bg-gray-300 text-gray-900 ">Watch</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
