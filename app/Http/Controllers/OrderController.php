<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderBulkDestroyRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\DataResource;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        return view('orders.index', compact('request'));
    }

    public function index(Request $request, Menu $menu)
    {
        return DataResource::collection(
            QueryBuilder::for($menu->orders()->getQuery())->search($request->search)->paginate()
        );
    }

    public function create(Menu $menu)
    {
        $order = $menu->orders()->make();

        return view('orders.create', compact('order', 'menu'));
    }

    public function edit(Menu $menu, Order $order)
    {
        abort_if(!$this->currentUser()->createdOrder($order), Response::HTTP_NOT_FOUND);

        return view('orders.edit', compact('order'));
    }

    public function store(OrderRequest $request, Menu $menu)
    {
        return DataResource::make(
            $menu->orders()->create(array_merge($request->validated(), [
                'owner_id' => $this->currentUser()->id,
            ]))
        );
    }

    public function show(Order $order)
    {
        return DataResource::make($order);
    }

    public function update(OrderRequest $request, Menu $menu, Order $order)
    {
        abort_if(!$this->currentUser()->createdOrder($order), Response::HTTP_FORBIDDEN);

        $order->update($request->validated());

        return DataResource::make($order);
    }

    public function destroy(Menu $menu, Order $order)
    {
        abort_if(!$this->currentUser()->createdOrder($order), Response::HTTP_FORBIDDEN);
        abort_if($order->completed_at, Response::HTTP_BAD_REQUEST);

        $order->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(OrderBulkDestroyRequest $request)
    {
        abort_if(!$this->currentUser()->createdOrder($order), Response::HTTP_NOT_FOUND);

        Order::destroy($request->ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
