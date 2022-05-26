<?php

namespace App\Evaluation\Criteria;

use App\Models\Order;
use App\CouriersFleet;

class OrderByTime implements Criterion
{
    public function perform(Order $order, CouriersFleet $couriersFleet) : CouriersFleet
    {
        return $couriersFleet->sortBy('duration');
    }
}
