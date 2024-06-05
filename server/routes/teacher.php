<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teacher\Auth\TeacherAuthController as TeacherAuthController;

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

// TODO - Aвторизации преподавателя. Выдаем токен.
Route::post('signin', [TeacherAuthController::class, 'signin']);

// TODO - Выход преподавателя. Удаляем токен.
Route::post('signout', [TeacherAuthController::class, 'signout']);





