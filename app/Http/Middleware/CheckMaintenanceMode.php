<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MaintenanceSettings;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't apply maintenance mode to admin routes, API routes, or authentication routes
        if ($request->is('admin/*') || 
            $request->is('api/*') || 
            $request->is('maintenance') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('forgot-password') ||
            $request->is('reset-password/*') ||
            $request->is('verify-email/*') ||
            $request->is('verify-email') ||
            $request->is('confirm-password') ||
            $request->is('email/verification-notification')) {
            return $next($request);
        }

        // Check if user is authenticated and is admin/manager
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isManager())) {
            return $next($request);
        }

        $settings = MaintenanceSettings::getSettings();
        
        if ($settings->is_enabled) {
            // Check if current IP is in allowed IPs
            $userIp = $request->ip();
            if ($settings->isIpAllowed($userIp)) {
                return $next($request);
            }
            
            // Show maintenance page
            return response()->view('maintenance', compact('settings'), 503);
        }

        return $next($request);
    }
}
