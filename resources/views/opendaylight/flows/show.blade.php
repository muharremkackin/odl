@extends('opendaylight.app')

@if($flow['main-received'] <= 101)
   @section('refresh')
       <meta http-equiv="refresh" content="30"/>
   @endsection
@endif

@section('content')
    @if($flow['main-received'] >= 75 && $flow['main-received']<= 100)
        <div class="w-screen h-16 bg-yellow-200">System yogun calismakta</div>
    @elseif($flow['main-received'] > 100)
        <div class="w-screen h-16 bg-red-300">Sistem saldiri altinda</div>
    @endif

    <div class="flex">
        <div class="w-1/4">
            <ul>
                <li>Hosts</li>
                <li>Timeline</li>
                <li>Received</li>
                <li>Connectors</li>
            </ul>
        </div>
        <div class="w-3/4">
            <div class="flex justify-between">
                <div>
                    <div>Name: {{ $flow['id'] }}</div>
                    <div>Connectors: {{ count($flow['connectors']) }}</div>
                    <div>Connector's maximum received packets per 1 minute:</div>
                </div>
                <div>
                    <div>Hosts: {{ $flow['main-connected'] + $flow['another-flow']}}</div>
                    <div>Total Received: {{ $flow['packets']['received'] }}</div>
                    <div>Total Transmitted: {{ $flow['packets']['transmitted'] }}</div>
                </div>
            </div>
            <div class="p-16 w-full">
                <table id="openFlowTable">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Connectors</th>
                        <th>State</th>
                        <th>Hardware Address</th>
                        <th>Current Feature</th>
                        <th>Packets Received</th>
                        <th>Packets Transmitted</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($flow['connectors'] as $connector)

                        <tr>
                            <td><a href="#modal{{ str_replace(':', '-', $connector['id']) }}" rel="modal:open">Open</a>
                            </td>
                            <td>{{ $connector['id'] }}</td>
                            <td>{{ $connector['flow-node-inventory:state']['blocked'] ? 'Offline' : 'Online'}}</td>
                            <td>{{ $connector['flow-node-inventory:hardware-address'] }}</td>
                            <td>{{ $connector['flow-node-inventory:current-feature'] }}</td>
                            <td>{{ $connector['opendaylight-port-statistics:flow-capable-node-connector-statistics']['packets']['received'] }}</td>
                            <td>{{ $connector['opendaylight-port-statistics:flow-capable-node-connector-statistics']['packets']['transmitted'] }}</td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
                @foreach($flow['connectors'] as $connector)
                    <div id="modal{{ str_replace(':', '-', $connector['id']) }}" class="modal">
                        <div>
                            <table>
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>mac</th>
                                    <th>ip</th>
                                    <th>first seen</th>
                                    <th>last seen</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($connector['address-tracker:addresses']))
                                    @foreach($connector['address-tracker:addresses'] as $addresses)
                                        <tr>
                                            <td>{{$addresses['id']}}</td>
                                            <td>{{$addresses['mac']}}</td>
                                            <td>{{$addresses['ip']}}</td>
                                            <td>{{$addresses['first-seen']}}</td>
                                            <td>{{$addresses['last-seen']}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <a href="#" rel="modal:close">Close</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


