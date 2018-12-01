<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompleteOrderController extends Controller
{
    public function __invoke(Request $request, Menu $menu, Order $order)
    {
        abort_if(!$this->currentUser()->is_chef, Response::HTTP_FORBIDDEN);

        $order->update(['completed_at' => now()]);

        return $order;
    }
}
