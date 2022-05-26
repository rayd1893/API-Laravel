<?php

namespace App\Evaluation\Criteria;

use App\Models\Order;
use App\CouriersFleet;

class DidNotIgnoreOrReject implements Criterion
{
    public function perform(Order $order, CouriersFleet $couriersFleet) : CouriersFleet
    {
        $intents = $order->assignationIntents;

        return $couriersFleet->filter(function ($courier) use ($intents) {
            return !$intents->contains('courier_id', '=', $courier['courier']->id);
        });
    }
}
