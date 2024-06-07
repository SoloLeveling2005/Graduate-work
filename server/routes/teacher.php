<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teacher\Auth\TeacherAuthController as TeacherAuthController;
use App\Http\Controllers\Teacher\GroupController as GroupController;
use App\Http\Controllers\Teacher\ScheduleController as ScheduleController;

use App\Http\Middleware\Teacher\TeacherAuthMiddleware as TeacherAuthMiddleware;

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

// TODO - Список групп.
Route::middleware(TeacherAuthMiddleware::class)->group(function() {
    Route::prefix('group')->group(function() {
        Route::get('list', [GroupController::class, 'list']);
        Route::get('tutorList', [GroupController::class, 'tutorList']);
    });

    Route::prefix('schedule')->group(function() {
        Route::get('list', [ScheduleController::class, 'list']);
    }); 
});







