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

    /**
     * Конструктор класса.
     * Загружает конфигурацию охранников и устанавливает текущий охранник.
     *
     * @param string $guard Название охранника.
     */
    public function __construct($guard)
    {
        $this->SLGuardArray = include(__DIR__ . '/../Modules/SLGuard.php');
        $this->guard = $guard;
    }

    /**
     * Попытка аутентификации пользователя.
     *
     * @param array $params Ассоциативный массив параметров аутентификации (например, логин и пароль).
     * @param string|null $expires_at Время жизни токена в формате даты и времени. Если null, токен бессрочный.
     * @return \Illuminate\Http\JsonResponse Возвращает токен при успешной аутентификации или сообщение об ошибке.
     */
    public function attempt(array $params, ?string $expires_at = null)
    {
        if (empty($params)) {
            return ['error' => 'No parameters provided', 'status' => 400];
        }

        $ip = request()->ip();
        $key = 'login-attempts:' . $ip;

        // Ограничение количества попыток входа для предотвращения атак типа "brute force"
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return ['error' => 'Too many attempts. Please try again later.', 'status' => 429];
        }

        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $userModel = new $guardConfig['model'];
        $tokenModel = new $guardConfig['tokenModel'];

        $query = $userModel->query();
        $check_password = false;
        $password_value = null;

        // Построение запроса для поиска пользователя на основе переданных параметров
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

        // Выполнение запроса и получение пользователя
        $user = $query->first();

        // Проверка пароля
        if ($check_password && $user != null) {
            if (!Hash::check($password_value, $user->password)) {
                RateLimiter::hit($key, 60);
                return ['error' => 'Invalid credentials', 'status' => 401];
            }
        }

        // Создание токена при успешной аутентификации
        if ($user) {
            $token = Str::random(32);
            $expiration = $expires_at ? \Carbon\Carbon::parse($expires_at) : null; // Токен может быть бессрочным
            $tokenModel->create([
                $this->SLGuardArray['default_token_field'] => $token,
                $this->SLGuardArray['default_parentId_field'] => $user->id,
                'expires_at' => $expiration,
            ]);

            RateLimiter::clear($key);

            return ['token' => $token, 'expires_at' => $expiration, 'status' => 200];
        } else {
            RateLimiter::hit($key, 60);
            return ['error' => 'User not found', 'status' => 404];
        }
    }

    /**
     * Аутентификация пользователя на основе токена.
     *
     * @param Request $request HTTP-запрос, содержащий токен.
     * @return \Illuminate\Http\JsonResponse|Request Возвращает данные пользователя или сообщение об ошибке.
     */
    public function auth(Request $request)
    {
        $token = $this->getToken($request);

        if (!$token) {
            return ['error' => 'Token not provided', 'status'=>401];
        }

        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first(); 

        // Проверка токена и его срока действия
        if ($tokenRecord && (!$tokenRecord->expires_at || $tokenRecord->expires_at->isFuture())) {
            $userId = $tokenRecord->{$this->SLGuardArray['default_parentId_field']};
            $userModel = new $guardConfig['model'];
            $user = ($userModel::with('privileges')->find($userId))->toArray();

            $instance = $userModel;
            $reflection = new \ReflectionClass($instance);
            $property = $reflection->getProperty('hidden');
            $property->setAccessible(true);
            $hidden_fields = $property->getValue($instance);

            if ($user) {
                $privileges = array_map(function($item) {
                    return $item['privilege']; // Умножаем каждый элемент на 2
                }, $user['privileges']);
                $user['privileges'] = $privileges;

                $request->user = $user;
                return $request;
            }
        }

        return ['error' => 'Invalid or expired token', 'status'=>401];
    }

    /**
     * Обновление токена пользователя.
     *
     * @param Request $request HTTP-запрос, содержащий токен.
     * @param string|null $expires_at Время жизни нового токена в формате даты и времени. Если null, токен бессрочный.
     * @return \Illuminate\Http\JsonResponse Возвращает новый токен или сообщение об ошибке.
     */
    public function refresh(Request $request, ?string $expires_at = null)
    {
        $token = $this->getToken($request);

        if (!$token) {
            return ['error' => 'Token not provided', 'status'=>401];
        }

        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first();

        // Обновление токена, если он действителен
        if ($tokenRecord && (!$tokenRecord->expires_at || $tokenRecord->expires_at->isFuture())) {
            $newToken = Str::random(32);
            $expiration = $expires_at ? \Carbon\Carbon::parse($expires_at) : null;

            $tokenRecord->token = $newToken;
            $tokenRecord->expires_at = $expiration;
            $tokenRecord->save();

            return ['token' => $newToken, 'expires_at' => $expiration, 'status'=>200];
        }

        return ['error' => 'Invalid or expired token', 'status'=>401];
    }

    /**
     * Продление срока действия токена.
     *
     * @param Request $request HTTP-запрос, содержащий токен.
     * @param string $new_expires_at Новое время истечения срока действия токена.
     * @return \Illuminate\Http\JsonResponse Возвращает обновленный токен или сообщение об ошибке.
     */
    public function extend(Request $request, string $new_expires_at)
    {
        $token = $this->getToken($request);

        if (!$token) {
            return ['error' => 'Token not provided', 'status'=>401];
        }

        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first();

        // Продление срока действия токена, если он действителен
        if ($tokenRecord && (!$tokenRecord->expires_at || $tokenRecord->expires_at->isFuture())) {
            $newExpiration = \Carbon\Carbon::parse($new_expires_at);
            $tokenRecord->expires_at = $newExpiration;
            $tokenRecord->save();

            return ['token' => $token, 'expires_at' => $newExpiration, 'status'=>200];
        }

        return ['error' => 'Invalid or expired token', 'status'=>401];
    }

    /**
     * Получение всех активных сессий пользователя.
     *
     * @param Request $request HTTP-запрос, содержащий данные пользователя.
     * @return \Illuminate\Http\JsonResponse Возвращает список активных сессий пользователя.
     */
    public function sessions(Request $request)
    {
        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $userId = $request->user->id;

        // Получение всех активных сессий (токенов)
        $sessions = $tokenModel->where($this->SLGuardArray['default_parentId_field'], $userId)
                            ->where(function ($query) {
                                $query->where('expires_at', '>', now())
                                      ->orWhereNull('expires_at');
                            })
                            ->get(['id', 'token', 'expires_at']);

        return ['data'=>$sessions, 'status'=>200];
    }

    /**
     * Отключение (удаление) сессии пользователя.
     *
     * @param Request $request HTTP-запрос, содержащий токен.
     * @return \Illuminate\Http\JsonResponse Возвращает успех операции.
     */
    public function revoke(Request $request)
    {
        $token = $this->getToken($request);

        if ($token == null) {
            return ['message' => 'Token not provided', 'status' => 203];
        }

        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];

        // Проверка наличия токена в базе данных
        $tokenRecord = $tokenModel->where('token', $token)->first();

        if ($tokenRecord == null) {
            return ['message' => 'Token not found', 'status' => 404];
        }

        // Удаление токена
        $tokenRecord->delete();

        return ['message' => 'Token revoked successfully', 'status' => 200];
    }

    /**
     * Перевыпуск существующего токена с обновлением срока действия.
     *
     * @param Request $request HTTP-запрос, содержащий токен.
     * @param string|null $expires_at Новое время истечения срока действия токена в формате даты и времени. Если null, токен бессрочный.
     * @return \Illuminate\Http\JsonResponse Возвращает обновленный токен или сообщение об ошибке.
     */
    public function renew(Request $request, ?string $expires_at = null)
    {
        $token = $this->getToken($request);

        if (!$token) {
            return ['error' => 'Token not provided', 'status'=>401];
        }

        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first();

        // Обновление срока действия токена, если он действителен
        if ($tokenRecord && (!$tokenRecord->expires_at || $tokenRecord->expires_at->isFuture())) {
            $expiration = $expires_at ? \Carbon\Carbon::parse($expires_at) : null;
            $tokenRecord->expires_at = $expiration;
            $tokenRecord->save();

            return ['token' => $token, 'expires_at' => $expiration, 'sattus'=>200];
        }

        return ['error' => 'Invalid or expired token', 'status'=>401];
    }

    /**
     * Получение токена из запроса.
     *
     * @param Request $request HTTP-запрос, содержащий токен.
     * @return string|null Возвращает токен из запроса или null.
     */
    public function getToken(Request $request)
    {
        return $request->bearerToken() ?? $request->header('Authorization');
    }
    
    /**
     * Удаление всех токенов пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return void
     */
    public function revokeAllTokens($userId)
    {
        // Получение конфигурации охранника
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];

        // Удаление всех токенов пользователя
        $tokenModel->where($this->SLGuardArray['default_parentId_field'], $userId)->delete();
    }
}