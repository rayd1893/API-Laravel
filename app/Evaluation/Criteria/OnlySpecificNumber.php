<?php

namespace App\Evaluation\Criteria;

use App\Models\Order;
use App\CouriersFleet;

class OnlySpecificNumber implements Criterion
{
    private $quantity;

    public function __construct($quantity)
    {
        $this->quantity = $quantity;
    }

    public function perform(Order $order, CouriersFleet $couriersFleet) : CouriersFleet
    {
        return $couriersFleet->take($this->quantity);
    }
}
