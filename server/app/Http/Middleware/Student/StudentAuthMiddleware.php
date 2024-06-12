<?php

namespace App\Http\Middleware\teacher;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth as LaravelAuth;
use Illuminate\Support\Facades\Hash;

use App\Modules\SLAuthorization as SLAuthorization;

class StudentAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $request = SLAuthorization::guard('student')->auth($request);

        if ($request instanceof Request) {
            return $next($request);
        }
        return response()->json($request, $request['status']);
    }
}
