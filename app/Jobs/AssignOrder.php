<?php

namespace App\Jobs;

use App\Radar;
use App\Snapshot;
use App\Models\Order;
use App\Evaluation\Evaluator;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Evaluation\Criteria\OrderByTime;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Evaluation\Criteria\LessThan129Minutes;
use App\Evaluation\Criteria\OnlySpecificNumber;
use App\Evaluation\Criteria\DidNotIgnoreOrReject;

class AssignOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $order = $this->order;

        $radar = new Radar($order);
        $couriersFleet = $radar->scan();

        $couriersFleet = $couriersFleet->registerNewCouriers();

        $evaluator = new Evaluator(
            new DidNotIgnoreOrReject(),
            new LessThan129Minutes(),
            new OrderByTime(),
            new OnlySpecificNumber(config('assignations.snapshot_size'))
        );

        $evaluatedCouriersFleet = $evaluator->evaluate($order, $couriersFleet);

        $snapshot = new Snapshot($evaluatedCouriersFleet);
        $snapshot->fulfillWithGhostCouriers(config('assignations.snapshot_size'));

        $intents = $order->addSnapshot($snapshot);

        $intents->reduce(function ($delay, $intent) {
            $dispatchAt = now()->addSeconds(($delay - 1) * config('assignations.delay_per_courier'));
            $nextAssignationAt = now()->addSeconds($delay * config('assignations.delay_per_courier'));

            NotifyAssignationIntent::dispatch(
                $intent,
                $nextAssignationAt->toString()
            )->delay($dispatchAt);

            return $delay + 1;
        }, 1);
    }
}
