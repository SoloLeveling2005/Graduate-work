<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\Auth\StudentAuthController as StudentAuthController;

use App\Http\Middleware\Student\StudentAuthMiddleware as StudentAuthMiddleware;
use App\Http\Controllers\Student\LibraryController;

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


Route::prefix('library')->group(function() {

    Route::get('books/search', [LibraryController::class, 'search']);
    Route::get('books', [LibraryController::class, 'index']);

});


Route::middleware(StudentAuthMiddleware::class)->group(function() {
    Route::prefix('calendar')->group(function() {
        Route::get('events', [CalendarController::class, 'eventsByDate']);
        Route::post('createEvent', [CalendarController::class, 'createEvent']);
    });
});