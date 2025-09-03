<?php

namespace App\Http\Middleware;

use Closure;

class ManagerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'Authentication required');
        }

        $userRole = strtolower((string)auth()->user()->role);
        
        // Allow both admin and manager roles
        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403, 'Manager or Admin access required');
        }
        
        return $next($request);
    }
}
