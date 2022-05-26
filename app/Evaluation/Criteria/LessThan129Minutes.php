<?php

namespace App\Evaluation\Criteria;

use App\Models\Order;
use App\CouriersFleet;

class LessThan129Minutes implements Criterion
{
    public function perform(Order $order, CouriersFleet $couriersFleet) : CouriersFleet
    {
        return $couriersFleet->filter(
            fn ($courier) => ceil($courier['duration'] / 60) <= 129
        );
    }
}
