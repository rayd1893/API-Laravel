<?php

namespace App\Services;

use Exception;
use App\Models\Order;
use App\Models\Courier;
use App\Models\AssignationIntent;
use Illuminate\Support\Facades\Http;

class CoreService
{
    public static function assignOrder(Order $order, Courier $courier): bool
    {
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('apis.core.key'),
        ])->post(config('apis.core.assignations'), [
            'counter' => $order->counter,
            'driverid' => $courier->driver_id,
            'status' => strval($order->status),
        ])->throw();

        return true;
    }

    public static function notificateIntent(Order $order, AssignationIntent $intent, Courier $courier): bool
    {
        try {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('apis.core.key'),
            ])->post(config('apis.core.assignment_intent_notification'), [
                'driverid' => $courier->driver_id,
                'bodyExpress' => [
                    'counter' => $order->counter,
                    'assignation_intent_id' => $intent->uuid,
                    'take_until' => $intent->take_until,
                ],
            ])->throw();

        } catch (Exception $e) {
            info('error', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
        }

        return true;
    }
}
