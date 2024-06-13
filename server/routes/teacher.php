<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teacher\Auth\TeacherAuthController as TeacherAuthController;
use App\Http\Controllers\Teacher\GroupController as GroupController;
use App\Http\Controllers\Teacher\ScheduleController as ScheduleController;
use App\Http\Controllers\Teacher\TeacherController as TeacherController;
use App\Http\Controllers\Teacher\LibraryController;
use App\Http\Controllers\Teacher\CalendarController;

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

// Aвторизации преподавателя. Выдаем токен.
Route::post('signin', [TeacherAuthController::class, 'signin']);

// Выход преподавателя. Удаляем токен.
Route::post('signout', [TeacherAuthController::class, 'signout']);

// Список групп.
Route::middleware(TeacherAuthMiddleware::class)->group(function() {
    Route::prefix('group')->group(function() {
        Route::get('list', [GroupController::class, 'list']);
        Route::get('tutorList', [GroupController::class, 'tutorList']);

        Route::prefix('{groupId}')->group(function () {

            // TODO Получение всех предметов преподавателя, которые можно указать в запросе на замены
            Route::get('teacherSubjectList', [TeacherController::class ,'teacherSubjectList']);


            Route::prefix('schedule')->group(function() {
                // Создание замены на определнный день и пару
                Route::post('addRequest', [ScheduleController::class ,'addRequest']);
            }); 
            
        });
    });

    Route::prefix('schedule')->group(function() {
        Route::get('list', [ScheduleController::class, 'list']);
    });     

    Route::prefix('library')->group(function() {
        Route::post('books', [LibraryController::class, 'store']);
        Route::get('books', [LibraryController::class, 'index']);
        Route::get('books/search', [LibraryController::class, 'search']);
    });

    Route::prefix('calendar')->group(function() {
        Route::get('events', [CalendarController::class, 'eventsByDate']);
        Route::post('createEvent', [CalendarController::class, 'createEvent']);
    });


    Route::prefix('classroom')->group(function() {
        // TODO - Список классрумов
        Route::get('list', [CalendarController::class, 'indexForTeachers']);
    });


});









