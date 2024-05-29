<?php

namespace App\Modules;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Генерируем случайную строку
function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Функция, обращение к охраннику guard(). Мы получаемна вход параметр: строка название охранника. 
// Функция, авторизовывающая пользователя attempt(). Мы получаем на вход параметры в виде ассоциативного массива по которым необходимо провести авторизацию. Например: логин и пароль или номер телефона и почта. Если пользователь найден мы создаем для него токен и записываем во вторую таблицу.
// Функция, аутентификация пользователя auth(). Мы получаем на вход парметр: Request $request. Из него мы вытаскиваем bearer токен и проходимся по второй таблице. Если находим то возвращаем данные пользователя (я имею ввиду данные первой таблицы).

class SLAuthorization {
    public function __construct() {
        
    }

    static public function guard($guard) {
        // Обращение к охраннику.
        return new SLAuthorizationGuard($guard);
    }
} 

class SLAuthorizationGuard {
    protected $SLGuardArray;
    protected $guard;

    public function __construct($guard) {
        $this->SLGuardArray = include(__DIR__ . '/../Modules/SLGuard.php');
        // Дополнительный код инициализации, если необходимо.
        $this->guard = $guard;
    }

    public function attempt($params) {
        // Проверяем, что массив параметров не пустой.
        if (empty($params)) {
            return null;
        }

        // Получаем модель и модель токена для указанного охранника.
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $userModel = new $guardConfig['model'];
        $tokenModel = new $guardConfig['tokenModel'];

        // Строим запрос к базе данных динамически на основе переданных параметров.
        $query = $userModel->query();
        $check_password = false;
        $password_value = null;
        foreach ($params as $field => $value) {
            $fillables = Schema::getColumnListing($userModel->getTable());
            // Проверяем, существует ли поле в модели, чтобы избежать SQL инъекций.
            if (in_array($field, $fillables)) {
                if ($field == 'password') {
                    $check_password = true;
                    $password_value = $value;
                } else {
                    $query->where($field, $value);
                }
            }
        }

        // Выполняем запрос и получаем пользователя.
        $user = $query->first();

        if ($check_password && $user != null) {
            if (!Hash::check($password_value, $user->password)) {
                $user = null;
            }
        }

        // Проверяем, найден ли пользователь.
        if ($user) {
            // Создаем токен и записываем во вторую таблицу.
            $token = generateRandomString();
            $tokenModel->create([
                ($this->SLGuardArray['default_token_field']) => $token,
                ($this->SLGuardArray['default_parentId_field']) => $user->id,
            ]);

            // Возвращаем токен.
            return $token;
        } else {
            // Возвращаем сообщение об ошибке, если пользователь не найден.
            return null;
        }
    }

    public function auth(Request $request) {
        // Получаем токен из заголовка запроса или из другого места, где он передается.
        $token = $request->bearerToken(); // Пример получения токена из заголовка Authorization Bearer.
        if (!$token) {
            $token = $request->header('Authorization');
            if (!$token) {
                return null;
            }
        }

        // Ищем токен в таблице токенов для соответствующего охранника.
        $guardConfig = $this->SLGuardArray['guards'][$this->guard];
        $tokenModel = new $guardConfig['tokenModel'];
        $tokenRecord = $tokenModel->where($this->SLGuardArray['default_token_field'], $token)->first();

        $userModel = new $guardConfig['model'];

        if ($tokenRecord) {
            // Если токен найден, возвращаем данные пользователя (из первой таблицы или других источников).
            $userId = $tokenRecord->{$this->SLGuardArray['default_parentId_field']};
            $user = $userModel->find($userId); // Получаем пользователя по ID.

            $request->user = $user;

            return $request;
        }

        // Если токен не найден или истек, возвращаем null или какой-то другой результат в зависимости от логики вашего приложения.
        return null;
    }
}
