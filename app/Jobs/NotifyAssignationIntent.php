<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\CoreService;
use App\Models\AssignationIntent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAssignationIntent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private AssignationIntent $intent;
    private string $deadline;

    public function __construct($intent, $deadline)
    {
        $this->intent = $intent;
        $this->deadline = $deadline;
}

    public function handle()
    {
        $order = $this->intent->order;
        info('intent: ' . $this->intent->uuid, $this->intent->toArray());

        if (!$order->isAssignable()) {
            return;
        }

        $this->intent->update(['take_until' => $this->deadline]);

        if (!$this->intent->hasGhostCourier()) {
            CoreService::notificateIntent($order, $this->intent, $this->intent->courier);
        }
    }
}
