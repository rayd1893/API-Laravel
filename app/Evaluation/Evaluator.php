<?php

namespace App\Evaluation;

use App\Models\Order;
use App\CouriersFleet;
use App\Evaluation\Criteria\Criterion;

class Evaluator
{
    private $criteria;

    public function __construct(Criterion ...$criteria)
    {
        $this->criteria = collect($criteria);
    }

    public function evaluate(Order $order, CouriersFleet $couriersFleet)
    {
        $evaluatedCouriers = $couriersFleet;

        foreach ($this->criteria as $criterion) {
            $evaluatedCouriers = $criterion->perform($order, $evaluatedCouriers);
        }

        return $evaluatedCouriers;
    }
}
