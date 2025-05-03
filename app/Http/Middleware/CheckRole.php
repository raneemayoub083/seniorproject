<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            abort(403); // Not logged in
        }

        if (!in_array(Auth::user()->role_id, $roles)) {
            abort(403); // Not authorized
        }

        return $next($request);
    }
}
