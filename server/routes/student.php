<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\Auth\StudentAuthController as StudentAuthController;

use App\Http\Middleware\Student\StudentAuthMiddleware as StudentAuthMiddleware;
use App\Http\Controllers\Student\LibraryController;
use App\Http\Controllers\Student\CalendarController;
use App\Http\Controllers\Student\MeController;


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





Route::middleware(StudentAuthMiddleware::class)->group(function() {

    Route::get('me', [MeController::class, 'me']);
    
    Route::prefix('library')->group(function() {

        Route::get('books/search', [LibraryController::class, 'search']);
        Route::get('books', [LibraryController::class, 'index']);

    });
    Route::prefix('calendar')->group(function() {
        Route::get('events', [CalendarController::class, 'eventsByDate']);
    });
});