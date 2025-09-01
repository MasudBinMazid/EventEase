<?php

namespace App\Http\Middleware;

use Closure;

class OrganizerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || strtolower((string)auth()->user()->role) !== 'organizer') {
            abort(403, 'Organizers only');
        }
        return $next($request);
    }
}
