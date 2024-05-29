<?php

namespace App\Http\Middleware\AdminPanel\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Models\UserAdmin as UserAdmin;
use Illuminate\Support\Facades\Auth as LaravelAuth;

use App\Modules\SLAuthorization as SLAuthorization;

class AdminController
{
    public function handle(Request $request, Closure $next): Response
    {
        if (SLAuthorization::guard('admin')->auth($request)) {
            return $next($request);
        }
        return response()->json(['error' => 'Не удалось аутентифицировать пользователя.'], 401);
    }
}