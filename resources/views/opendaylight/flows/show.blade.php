@extends('opendaylight.app')

@include('opendaylight.flows.includes.refresh')

@section('content')
    @include('opendaylight.flows.includes.check_received')

    <div class="flex">
        <div class="w-screen">
            @foreach($flows as $flow)
                @if($name == $flow['id'])
                    <div class="flex justify-between">
                        <div>
                            <div>Name: {{ $flow['id'] }}</div>
                            <div>Connectors: {{ count($flow['connectors']) }}</div>
                            <div>Connector's maximum received packets per 30 seconds:</div>
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
                                            <th class="px-4">id</th>
                                            <th class="px-4">mac</th>
                                            <th class="px-4">ip</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($connector['address-tracker:addresses']))
                                            @foreach($connector['address-tracker:addresses'] as $addresses)
                                                <tr>
                                                    <td class="px-4">{{$addresses['id']}}</td>
                                                    <td class="px-4">{{$addresses['mac']}}</td>
                                                    <td class="px-4">{{$addresses['ip']}}</td>
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
                @endif
            @endforeach
        </div>
    </div>
@endsection


