<?php

namespace App;

use App\Models\User;

class Globals
{
    public static function variables(): array
    {
        return array_sort([
            'user'  => auth()->user(),
            'roles' => array_sort(User::ROLES),
        ]);
    }
}
