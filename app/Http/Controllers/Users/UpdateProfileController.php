<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserProfileUpdateRequest;
use App\Models\User;

class UpdateProfileController extends Controller
{
    public function __invoke(UserProfileUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return $user;
    }
}
