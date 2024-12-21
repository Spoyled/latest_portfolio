<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserGuard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the current guard is 'employer' and redirect to employer dashboard
        if (auth('employer')->check()) {
            if ($request->route()->getName() === 'HomePage') { 
                return redirect()->route('employer.dashboard')->with('error', 'You are redirected to your dashboard.');
            }

            return redirect()->route('employer.dashboard')->with('error', 'You do not have access to this page.');
        }

        return $next($request);
    }
}
