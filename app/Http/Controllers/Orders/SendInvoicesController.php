<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\SendInvoicesRequest;
use App\Models\User;
use App\Notifications\InvoicePending;

class SendInvoicesController extends Controller
{
    public function sendAll(SendInvoicesRequest $request)
    {
        User::withPendingOrdersBetween($request->validated())->each(function (User $user) use ($request) {
            $user->notify(new InvoicePending($user, $request->validated()));
        });
    }

    public function sendToUser(SendInvoicesRequest $request, User $user)
    {
        $user->notify(new InvoicePending($user, $request->validated()));
    }
}
