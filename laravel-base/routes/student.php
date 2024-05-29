<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminPanel\Auth\AdminAuthController as AdminAuthController;
use App\Http\Controllers\AdminPanel\AdminController as AdminController;
use App\Http\Middleware\AdminPanel\Auth\AdminController as AdminMiddleware;

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

Route::prefix('adminPanel')->group(function () {
    // Авторизация админа
    Route::post('adminAuth', [AdminAuthController::class, 'signin']);

    // Плучение информация о админе
    Route::get('adminInfo', [AdminController::class, 'info'])->middleware(AdminMiddleware::class);

    
});




