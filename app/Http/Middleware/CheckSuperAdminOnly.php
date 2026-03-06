<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdminOnly
{
    /**
     * Allow only superadmin (role_id 0) to pass.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role_id !== 0) {
            abort(403, 'Access denied. Only superadmin can access this area.');
        }

        return $next($request);
    }
}
