<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\MarkPaidRequest;
use App\Http\Resources\DataResource;
use App\Models\Order;

class MarkPaidController extends Controller
{
    public function __invoke(MarkPaidRequest $request, Order $order)
    {
        $order->update([
            'paid_at' => $request->paid ? now()->toDateTimeString() : null,
        ]);

        return DataResource::make($order);
    }
}
