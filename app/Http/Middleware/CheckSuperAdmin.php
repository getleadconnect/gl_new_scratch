<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow role_id 0 (superadmin) and role_id 1 (admin)
        if (!auth()->check() || !in_array(auth()->user()->role_id, [0, 1])) {
            abort(403, 'Access denied. Only admin users can access this area.');
        }

        return $next($request);
    }
}
