<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menus\ReportRequest;
use App\Models\Menu;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __invoke(ReportRequest $request)
    {
        $start = new Carbon($request->start);
        $end   = new Carbon($request->end);

        return Menu::with('orders')
            ->completed()
            ->where('date', '>=', $start->toDateString())
            ->where('date', '<=', $end->toDateString())
            ->orderBy('date')
            ->get()
            ->groupBy(function ($menu) {
                return $menu->date->toDateString();
            })
            ->map(function ($report, $date) {
                $orders = $report->reduce(function ($carry, $menu) {
                    return $carry->merge($menu->orders()->completed()->get());
                }, collect([]));

                return [
                    'orders'       => $orders->toArray(),
                    'count_orders' => $orders->count(),
                    'total'        => $report->reduce(function ($total, $menu) {
                        return $total + $menu->income;
                    }),
                ];
            });
    }
}
