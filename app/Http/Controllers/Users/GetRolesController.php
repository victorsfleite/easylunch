<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetRolesController extends Controller
{
    public function __invoke()
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_NOT_FOUND);

        return collect(User::ROLES)->map(function ($role) {
            return [
                'name' => $role,
                'label' => ucfirst($role),
            ];
        })->toArray();
    }
}
