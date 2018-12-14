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

        $report = User::whereHasOrdersBetweenDates($range)
            ->get()
            ->map(function (User $user) use ($range) {
                return [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'count_orders' => $user->ordersCompleted()->betweenDates($range)->count(),
                    'total_amount' => $user->totalAmountInRange($range),
                ];
            });

        return DataResource::collection($report);
    }
}
