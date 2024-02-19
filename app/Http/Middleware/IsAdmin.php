<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin) {
            return $next($request);
        }

        abort(403, 'Accès interdit. Vous devez être administrateur.');
    }
}
