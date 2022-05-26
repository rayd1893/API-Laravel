<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Configuration;
use Google\Cloud\PubSub\PubSubClient;
use App\Http\Requests\OrderDeliveredRequest;

class StatusOrderController extends Controller
{
    public function handleDelivered(OrderDeliveredRequest $request)
    {
        $statusChangedData = $request->data();

        $order = Order::findByCounter($statusChangedData['counter']);
        $order->update(['status' => $statusChangedData['status']]);

        if ($order) {
            $configurations = Configuration::findBySectionAndCountry(
                'commissions',
                $order->country
            );
            $plainComissionPercentage = $configurations->options['percentage'];
            $commissionPercentage = $plainComissionPercentage / 100;

            $order->update(['status' => Order::DELIVERED_STATUS]);

            $pubSub = new PubSubClient([
                'keyFilePath' => app_path('../pubsub-service-account.json'),
            ]);
            $pubSub->topic('update-cost')->publish([
                'counter' => $order->counter,
                'status' => $order->status,
                'amount' => $order->cost * $commissionPercentage,
            ]);
        }

        return response()->json([]);
    }
}
