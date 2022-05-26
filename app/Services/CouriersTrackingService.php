<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CouriersTrackingService
{
    public static function fetchCouriers($scanPoint, $radius, $country, $fleet = 'express'): array
    {
        $payload = [
            'latitude' => (float)$scanPoint[0],
            'longitude' => (float)$scanPoint[1],
            'radius' => (int)$radius,
            'country' => $country,
            'fleet' => $fleet,
        ];

        $couriers = Http::post(config('apis.couriers_tracking'), $payload)
            ->throw()
            ->json();

        return collect($couriers)->map(function ($courier) {
            $orders = collect($courier['orders'])->map(function ($order) {
                return [
                    'counter' => $order['counter'],
                    'location' => [
                        $order['Location']['coordinates'][1],
                        $order['Location']['coordinates'][0],
                    ],
                ];
            })->toArray();

            return [
                'driver_id' => $courier['driver_id'],
                'user_id' => $courier['user_id'],
                'token' => $courier['driver_token'],
                'location' => [
                    $courier['geometry']['coordinates'][1],
                    $courier['geometry']['coordinates'][0],
                ],
                'orders' => $orders,
            ];
        })->toArray();
    }
}
