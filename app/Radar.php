<?php

namespace App;

use App\Models\Order;
use App\Services\DirectionsService;
use App\Services\CouriersTrackingService;

class Radar
{
    private Order $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function scan(): CouriersFleet
    {
        $couriers = CouriersTrackingService::fetchCouriers(
            $this->order->origin,
            8000,
            $this->order->country,
            $this->order->fleet
        );
        info('couriers: ' . $this->order->counter, $couriers);

        $directions = $this->getDirectionsFromCouriers($couriers);
        $travels = DirectionsService::calculateTravel($directions);

        $fleet = $this->ensureOnlyCouriersWithEstimation($couriers, $travels);

        return new CouriersFleet($fleet);
    }

    private function getDirectionsFromCouriers($couriers): array
    {
        return collect($couriers)->map(function ($courier) {
            $orders = $courier['orders'];

            return [
                'origin' => $courier['location'],
                'destination' => $this->order->destination,
                'waypoints' => empty($orders) ? [$this->order->origin]
                    : [$orders[0]['location'], $this->order->origin],
            ];
        })->toArray();
    }

    private function ensureOnlyCouriersWithEstimation($couriers, $travels): array
    {
        return collect($couriers)->zip($travels)
            ->filter(function ($matrix) {
                return !array_key_exists('error', $matrix[1]);
            })->toArray();
    }
}
