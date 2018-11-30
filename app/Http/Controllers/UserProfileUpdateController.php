<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\User;

class UserProfileUpdateController extends Controller
{
    public function __invoke(UserProfileUpdateRequest $request, User $user)
    {
        $user->update($request->all());

        return $user;
    }
}
