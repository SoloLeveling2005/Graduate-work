<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\Auth\StudentAuthController as StudentAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// TODO - Aвторизации cтудента. Выдаем токен.
Route::post('signin', [StudentAuthController::class, 'signin']);

// TODO - Выход cтудента. Удаляем токен.
Route::post('signout', [StudentAuthController::class, 'signout']);



