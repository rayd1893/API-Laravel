<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Laravel\Telescope\Telescope;

class ReassignOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $orders = Order::unassigneds()->get();
        $orders->each(function ($order) {
            if ($order->inTime()) {
                if ($order->snapshotsCout() < config('assignations.max_tries_before_cancellation')) {
                    AssignOrder::dispatch($order);
                } else {
                    $order->cancel();
                }
            }
        });
    }
}
