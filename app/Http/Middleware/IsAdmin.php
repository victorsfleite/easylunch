<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_admin) {
            return $request->expectsJson()
                ? response()->json([], Response::HTTP_FORBIDDEN)
                : redirect(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
