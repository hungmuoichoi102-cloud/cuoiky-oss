<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateSession
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->secure() && env('REDIRECT_HTTPS')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
