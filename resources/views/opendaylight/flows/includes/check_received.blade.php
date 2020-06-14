@php
  $safety = true;
@endphp
@foreach($flows as $flow)
    @if($flow['main-received'] >= 75 && $flow['main-received']<= 100)
        <div class="w-screen h-16 bg-yellow-200">
            <p>System yogun calismakta</p>
            <p>{{ $flow['main-flow'] }} portunda yogunluk var</p>
        </div>
        @php
        $safety = false
        @endphp
    @elseif($flow['main-received'] > 100)
        <div class="w-screen h-16 bg-red-300">
            <p>Sistem saldiri altinda</p>
            <p>{{ $flow['main-flow'] }} portunda saldiri var</p>
        </div>
        @php
            $safety = false;
        @endphp
    @endif
@endforeach

@if($safety)
    <div class="w-screen h-16 bg-green-300">
        <p>Sistem guvende</p>
    </div>
@endif
