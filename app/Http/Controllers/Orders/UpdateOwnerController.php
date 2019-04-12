<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class UpdateOwnerController extends Controller
{
    public function __invoke(Request $request, Order $order)
    {
        $user = User::findOrFail($request->user_id);

        $order->owner()->associate($user);
        $order->save();

        return $order;
    }
}
