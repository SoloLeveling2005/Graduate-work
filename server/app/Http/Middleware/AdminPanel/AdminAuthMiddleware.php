<?php

namespace App\Http\Middleware\AdminPanel;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Modules\SLAuthorization as SLAuthorization;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $request = SLAuthorization::guard('admin')->auth($request);

        if ($request instanceof Request) {

            return $next($request);
        }
        return response()->json($request, $request['status']);
    }
}
