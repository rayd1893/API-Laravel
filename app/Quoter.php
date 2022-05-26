<?php

namespace App;

use App\Models\Fare;
use App\Models\Quote;
use App\Services\DirectionsService;
use App\Exceptions\TravelNotFoundException;
use Illuminate\Http\Client\RequestException;

class Quoter
{
    private array $route;
    private array $configuration;

    public function __construct($route, $configuration)
    {
        $this->route = $route;
        $this->configuration = $configuration;
    }

    public function terminate($userid)
    {
        try {
            $travel = DirectionsService::calculateTravel(
                $this->route['origin'],
                $this->route['destination']
            );

            $distances = $this->getDistances($travel);
            $price = $this->calculatePrice($distances, $userid);

            return Fare::create([
                'price' => $price,
                'distance' => $distances['distance'],
                'extra_distance' => $distances['extra_distance'],
            ]);
        } catch (RequestException) {
            throw new TravelNotFoundException();
        }
    }

    public function getDistances($travel)
    {
        return [
            'distance' => ceil($travel['travel']['distance'] / 1000),
            'extra_distance' => 0,
        ];
    }

    private function calculatePrice($distances, $userid)
    {
        $quotes = Quote::where('user_id', $userid)->get();

        if (!$quotes->isEmpty()) {
            $ruler = $quotes->where('type', 'fixed')->first()
                ?? $quotes->where('type', 'dynamic')->first();

            if ($ruler->type == 'fixed') {
                $finalCost = $ruler->amount;
            } else {
                $fares = $ruler->fares;
                $dynamicFare = $fares->last();
                $indexFare = $fares->count() - 1;

                foreach ($fares as $key => $fare) {
                    if ($distances['distance'] <= $fare['distance']) {
                        $dynamicFare = $fare;
                        $indexFare = $key;
                        break;
                    }
                }

                if ($dynamicFare['type'] == 'fixed') {
                    $finalCost = $dynamicFare['amount'];
                } else {
                    for ($i = $indexFare; $i <= 0; $i--) {
                        if ($fares[$i]['type'] == 'fixed') {
                            $fixedCost = $fares[$i];
                            break;
                        }
                    }

                    $finalCost = $fixedCost['amount']
                        + (($distances['distance'] - $fixedCost['distance']) * $dynamicFare['amount']);
                }
            }
        } else {
            $costs = collect($this->configuration['costs']);
            $lastCost = $costs->last();
            $finalCost = $lastCost['price'];

            foreach ($costs as $cost) {
                if ($distances['distance'] <= $cost['distance']) {
                    $finalCost = $cost['price'];
                    break;
                }
            }
        }

        $price['base'] = $finalCost;
        $price['extra'] = 0;
        $price['cost_per_extra'] = 0;
        $price['total'] = $finalCost;

        return $price;
    }
}
