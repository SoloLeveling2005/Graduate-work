<?php

namespace App\Modules;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class SLAuthorization
{
    public function __construct()
    {
    }

    public static function guard($guard)
    {
        // Обращение к охраннику.
        return new SLAuthorizationGuard($guard);
    }
}

class SLAuthorizationGuard
{
    protected $SLGuardArray;
    protected $guard;

    public function __construct($guard)
    {
        $this->SLGuardArray = include(__DIR__ . '/../Modules/SLGuard.php');
        $this->guard = $guard;
    }

    public function attempt(array $params)
    {
        if (empty($params)) {
            return response()->json(['error' => 'No parameters provided'], 400);
        }

        $ip = request()->ip();
        $key = 'login-attempts:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['error' => 'Too many attempts. Please try again later.'], 429);
        }

        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $userModel = new $guardConfig['model'];
        $tokenModel = new $guardConfig['tokenModel'];

        $query = $userModel->query();
        $check_password = false;
        $password_value = null;

        foreach ($params as $field => $value) {
            $fillables = Schema::getColumnListing($userModel->getTable());
            if (in_array($field, $fillables)) {
                if ($field == 'password') {
                    $check_password = true;
                    $password_value = $value;
                } else {
                    $query->where($field, $value);
                }
            }
        }

        $user = $query->first();

        if ($check_password && $user != null) {
            if (!Hash::check($password_value, $user->password)) {
                RateLimiter::hit($key, 60);
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        }

        if ($user) {
            $token = Str::random(32);
            $expiration = now()->addHours(1);
            $tokenModel->create([
                $this->SLGuardArray['default_token_field'] => $token,
                $this->SLGuardArray['default_parentId_field'] => $user->id,
                'expires_at' => $expiration,
            ]);

            RateLimiter::clear($key);

            return response()->json(['token' => $token, 'expires_at' => $expiration], 200);
        } else {
            RateLimiter::hit($key, 60);
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function auth(Request $request)
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first();

        if ($tokenRecord && (!$tokenRecord->expires_at || $tokenRecord->expires_at->isFuture())) {
            $userId = $tokenRecord->{$this->SLGuardArray['default_parentId_field']};
            $userModel = new $guardConfig['model'];
            $user = $userModel->find($userId);

            if ($user) {
                $request->user = $user;
                return $request;
            }
        }

        return response()->json(['error' => 'Invalid or expired token'], 401);
    }

    public function refresh(Request $request)
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first();

        if ($tokenRecord && (!$tokenRecord->expires_at || $tokenRecord->expires_at->isFuture())) {
            $newToken = Str::random(32);
            $expiration = now()->addHours(1);

            $tokenRecord->token = $newToken;
            $tokenRecord->expires_at = $expiration;
            $tokenRecord->save();

            return response()->json(['token' => $newToken, 'expires_at' => $expiration], 200);
        }

        return response()->json(['error' => 'Invalid or expired token'], 401);
    }

    public function sessions(Request $request)
    {
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $userId = $request->user->id;

        $sessions = $tokenModel->where($this->SLGuardArray['default_parentId_field'], $userId)
                            ->where('expires_at', '>', now())
                            ->get(['id', 'token', 'expires_at']);

        return response()->json($sessions);
    }

    public function revoke($tokenId)
    {
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];

        $tokenModel->where('id', $tokenId)->delete();

        return response()->json(['success' => true]);
    }
}
