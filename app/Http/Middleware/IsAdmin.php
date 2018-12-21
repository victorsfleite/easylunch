<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->is_admin) {
            return redirect(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
