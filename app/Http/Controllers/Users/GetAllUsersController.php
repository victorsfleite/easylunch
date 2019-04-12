<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Http\Controllers\Controller;

class GetAllUsersController extends Controller
{
    public function __invoke()
    {
        return User::whereRole(User::ROLE_USER)->orWhere('role', '=', User::ROLE_ADMIN)->get();
    }
}
