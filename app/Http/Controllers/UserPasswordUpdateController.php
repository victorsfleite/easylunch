<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPasswordUpdateRequest;
use App\Models\User;

class UserPasswordUpdateController extends Controller
{
    public function __invoke(UserPasswordUpdateRequest $request, User $user)
    {
        $user->update($request->all('password'));

        return $user;
    }
}
