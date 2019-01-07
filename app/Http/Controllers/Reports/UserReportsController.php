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

        $report = User::with('orders')->orderBy('name')->get()->map(function (User $user) use ($range) {
            $orders = $user->orders()->with('owner')->completed()->betweenDates($range)->get();

            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'orders'       => $orders,
                'total_amount' => $orders->sum('price'),
            ];
        })->filter(function ($user) {
            return $user['orders']->count() > 0;
        });

        return DataResource::collection($report);
    }
}
