<?php

namespace App\Evaluation\Criteria;

use App\Models\Order;
use App\CouriersFleet;

interface Criterion
{
    public function perform(Order $order, CouriersFleet $couriersFleet) : CouriersFleet;
}
