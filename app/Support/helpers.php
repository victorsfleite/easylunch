<?php

use App\Models\User;

function user(): ? User
{
    return auth()->user();
}

function impersonator(): ? User
{
    $userId = session()->get('admin:impersonator');

    if (!$userId) {
        return null;
    }

    return User::findOrFail($userId);
}
