<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserPasswordUpdateRequest;
use App\Models\User;

class UpdatePasswordController extends Controller
{
    public function __invoke(UserPasswordUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return $user;
    }
}
