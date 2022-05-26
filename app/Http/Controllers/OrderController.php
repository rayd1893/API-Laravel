<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Jobs\AssignOrder;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = Order::create(
                array_merge($request->order(), ['status' => Order::ASSIGNING_STATUS])
            );
            AssignOrder::dispatch($order);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        } finally {
            return response()->json([], 201);
        }
    }
}
