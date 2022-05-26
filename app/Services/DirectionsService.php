<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DirectionsService
{
    public static function calculateTravel(): array
    {
        $args = func_get_args();

        if (isset($args[1])) {
            $payload = [
                'origin' => $args[0],
                'destination' => $args[1],
            ];
        } else {
            $payload = ['directions' => $args[0]];
        }

        return Http::post(config('apis.directions') . '/travels', $payload)
            ->throw()
            ->json();
    }
}
