<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserVerifiedByAdmin
{
    /**
     * Handle an incoming request.
     * 
     * Checks if the authenticated user has been verified by an admin.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()?->is_verified) {
            return $next($request);
        }

        return response()->view('auth.unverified', [], 403);
    }
}
