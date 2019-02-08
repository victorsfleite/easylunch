<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\MarkPaidManyRequest;
use App\Http\Resources\DataResource;
use App\Models\Order;

class MarkPaidManyController extends Controller
{
    public function __invoke(MarkPaidManyRequest $request)
    {
        $orders = Order::whereIn('id', $request->ids);

        if (!$orders->count()) {
            return response()->json(null, 404);
        }

        $orders->update(['paid_at' => $request->paid ? now() : null]);

        return DataResource::collection($orders->get());
    }
}
