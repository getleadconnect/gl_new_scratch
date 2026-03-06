<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow role_id 2 (user) and role_id 3 (child/shop)
        if (!auth()->check() || !in_array(auth()->user()->role_id, [2, 3])) {
            abort(403, 'Access denied. Only users with user or child role can access this area.');
        }

        return $next($request);
    }
}
