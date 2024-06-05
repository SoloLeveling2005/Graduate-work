<?php


return [
    // Параметр по умолчанию для поля таблицы tokenModel (запись токена).
    'default_token_field'=>'token',
    // Параметр по умолчанию для поля таблицы tokenModel (запись владельца токеном).
    'default_parentId_field'=>'parentId',

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | model - параметр, требует модель которой будет присвиваться токен входа.
    |
    | tokenModel -  параметр, требует модель в которую будет производиться запись токенов входа.
    |
    */

    'guards' => [
        'admin' => [
            'model' => App\Models\UserAdmin::class,
            'tokenModel' => App\Models\UserAdminToken::class,
        ],
        'student' => [
            'model' => App\Models\UserStudent::class,
            'tokenModel' => App\Models\UserStudentToken::class,
        ],
        'teacher' => [
            'model' => App\Models\UserTeacher::class,
            'tokenModel' => App\Models\UserTeacherToken::class,
        ],
    ],
];