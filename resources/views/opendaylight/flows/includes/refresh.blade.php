@foreach($flows as $flow)
    @if($flow['main-received'] <= 101)
@section('refresh')
    <meta http-equiv="refresh" content="30"/>
@endsection
@endif
@endforeach
