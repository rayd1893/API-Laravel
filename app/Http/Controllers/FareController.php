<?php

namespace App\Http\Controllers;

use App\Quoter;
use App\Models\Fare;
use App\Models\Configuration;
use App\Http\Requests\StoreFareRequest;
use App\Exceptions\TravelNotFoundException;

class FareController extends Controller
{
    public function store(StoreFareRequest $request)
    {
        try {
            $route = $request->only('origin', 'destination');
            $configuration = Configuration::findBySectionAndCountry(
                'fares',
                $request->input('country')
            );

            $quoter = new Quoter($route, $configuration->options);
            $fare = $quoter->terminate($request->input('userid', '*'));

            return response()->json([
                'token' => $fare->uuid,
                'price' => $fare->price,
                'distances' => [
                    'base' => $fare->distance,
                    'extra' => $fare->extra_distance,
                ],
            ], 201);
        } catch (TravelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function show($uuid)
    {
        return response()->json([
            'price' => Fare::findOrFailByUuid($uuid)->price['total'],
        ]);
    }

    public function fares($country)
    {
        $fares = Configuration::findBySectionAndCountry('fares', $country);

        return response()->json($fares['options']);
    }
}
