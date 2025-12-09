<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (\Illuminate\Support\Facades\Auth::guard($guard)->check()) {
                return Redirect::to('/home');
            }
        }

        return $next($request);
    }
}
