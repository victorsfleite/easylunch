<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserReportsController extends Controller
{
    public function __invoke(Request $request)
    {
        $range = $request->only('start', 'end');

        $report = User::with('orders')->get()->map(function (User $user) use ($range) {
            $countOrders = $user->orders()->completed()->betweenDates($range)->count();

            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'count_orders' => $countOrders,
                'total_amount' => $countOrders * 10,
            ];
        })->filter(function ($user) {
            return $user['count_orders'] > 0;
        });

        return DataResource::collection($report);
    }
}
