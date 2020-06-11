<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OpenflowController extends Controller
{
    public function save()
    {
        $client = new Client();
        $response = $client->get('http://178.128.147.189:8181/restconf/operational/opendaylight-inventory:nodes', [
            'auth' => [
                'admin',
                'admin'
            ]
        ]);

        if (Storage::disk('public')->exists('openflows-new.json')) {
            if (Storage::disk('public')->exists('openflows-old.json')) {
                Storage::disk('public')->delete('openflows-old.json');
            }
            Storage::disk('public')->copy('openflows-new.json', 'openflows-old.json');
        }

        Storage::disk('public')->put('openflows-new.json', $response->getBody());
    }

    public function nodes()
    {
        return response()->json(json_decode(Storage::get('public/openflows-new.json')));
    }

    public function flows()
    {
        return response()->json($this->parseFlows());
    }

    public function flow($flow)
    {
        foreach ($this->parseFlows() as $openflow) {
            if ($openflow['id'] == $flow) {
                return response()->json($openflow);
            }
        }
    }

    public function received()
    {
        return response()->json($this->object_to_array(json_decode(Storage::disk('public')->get('received.json'))));
    }

    public function indexFlows()
    {
        return view('opendaylight.flows.index')->with('flows', $this->parseFlows());
    }

    public function showFlow($flow)
    {

        foreach ($this->parseFlows() as $openflow) {
            if ($openflow['id'] == $flow) {

                return view('opendaylight.flows.show')->with('flow', $openflow);
            }
        }

    }

    public function parseFlows()
    {
        $this->save();

        $api = $this->object_to_array(json_decode(Storage::get('public/openflows-new.json')));

        if (Storage::disk('public')->exists('openflows-old.json')) {
            $old = $this->object_to_array(json_decode(Storage::get('public/openflows-old.json')));

            $received = [];
            foreach ($old['nodes']['node'] as $node) {
                foreach ($node['node-connector'] as $connector) {
                    if ($connector['id'] == $node['id'] . ':1') {
                        $received[$connector['id']] = $connector['opendaylight-port-statistics:flow-capable-node-connector-statistics']['packets']['received'];
                    }
                }
            }

            Storage::disk('public')->put('received.json', json_encode($received));


        }


        $flows = [];
        $counter = 0;
        foreach ($api['nodes']['node'] as $node) {
            $node = (array)$node;
            $flows[$counter]['id'] = $node['id'];
            $flows[$counter]['connectors'] = $node['node-connector'];
            $flows[$counter]['packets'] = array(
                'received' => 0,
                'transmitted' => 0
            );


            foreach ($node['node-connector'] as $connector) {
                $flows[$counter]['packets']['received'] += $connector['opendaylight-port-statistics:flow-capable-node-connector-statistics']['packets']['received'];

                $flows[$counter]['packets']['transmitted'] += $connector['opendaylight-port-statistics:flow-capable-node-connector-statistics']['packets']['transmitted'];
                if ($connector['id'] == $node['id'] . ':1') {
                    $flows[$counter]['main-connected'] = count($connector['address-tracker:addresses']);
                    if (Storage::exists('public/received.json')) {
                        $received = $this->object_to_array(json_decode(Storage::get('public/received.json')));
                        $flows[$counter]['main-received'] = $connector['opendaylight-port-statistics:flow-capable-node-connector-statistics']['packets']['received'] - $received[$connector['id']];
                    }
                } else {
                    if (key_exists('address-tracker:addresses', $connector)) {
                        ($flows[$counter]['another-flow'] = count($connector['address-tracker:addresses']));
                    }
                }
            }


            $counter++;
        }

        return $flows;
    }

    private function object_to_array($obj)
    {
        if (is_object($obj)) {
            $obj = (array)$obj;
        }
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = $this->object_to_array($val);
            }
        } else {
            $new = $obj;
        }
        return $new;
    }
}
