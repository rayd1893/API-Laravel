<?php

namespace App;

use App\Models\Courier;
use Illuminate\Support\Collection;

class CouriersFleet extends Collection
{
    public function registerNewCouriers()
    {
        return $this->map(function ($bundle) {
            $courierData = $bundle[0];
            $courierEstimation = $bundle[1];

            $courier = Courier::firstOrCreate(
                ['driver_id' => $courierData['driver_id']],
                [
                    'user_id' => $courierData['user_id'],
                    'token' => $courierData['token'],
                ]
            );

            return [
                'courier' => $courier,
                'distance' => $courierEstimation['travel']['distance'],
                'duration' => $courierEstimation['travel']['duration'],
            ];
        });
    }
}
