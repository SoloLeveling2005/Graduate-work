<?php

namespace App\Http\Middleware\AdminPanel;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (in_array('SuperAdmin', $request->user['privileges'])) {
            return $next($request);
        }

        return response()->json(['error' => 'HTTP 403 Forbidden', 'status'=>403], 403);

    }
}
