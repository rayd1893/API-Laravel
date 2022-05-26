<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreOrderRequest extends FormRequest
{
    use InteractsWithPubSub;

    public function rules()
    {
        return [];
    }

    public function order()
    {
        $data = $this->data();

        return [
            'counter' => $data['counter'],
            'cost' => $data['cost'],
            'country' => $data['country'],
            'origin' => $data['origin'],
            'destination' => $data['destination'],
            'fleet' => $this->decideFleetFromApiKey($data['user']),
        ];
    }

    private function decideFleetFromApiKey($key)
    {
        $fleet = 'express';
        // todo fix this abomination and change it to the fleet_users relationship
        if ($key == 'f700e10b7caab7688a6117af0ebd1e10f1bf4a45') {
            $fleet = 'ikea-xps';
        }

        return $fleet;
    }
}
