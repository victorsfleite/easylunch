<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menus\ReportRequest;
use App\Http\Resources\DataResource;
use App\Models\Menu;
use App\Models\Order;
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
            ->where('date', '<=', $end->addDay()->toDateString())
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($report, $date) {
                return [
                    'count_orders' => $report->reduce(function ($carry, $menu) {
                        return $carry + $menu->orders()->completed()->count();
                    }),
                    'total' => $report->reduce(function ($total, $menu) {
                        return $total + $menu->income;
                    }),
                ];
            });
    }
}
