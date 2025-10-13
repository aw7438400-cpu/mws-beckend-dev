<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\EmotionalCheckinsController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/auth-google-redirect', [AuthController::class, 'google_redirect']);
Route::get('/auth-google-callback', [AuthController::class, 'google_callback']);
Route::post('auth/google/token', [AuthController::class, 'loginWithToken']);

Route::controller(UserController::class)->group(function () {
    Route::get('user', 'index');
    Route::get('user/{uuid}', 'get');
    Route::post('user', 'store');
    Route::patch('user/{uuid}', 'update');
    Route::delete('user/{uuid}', 'destroy');
});

/*
|--------------------------------------------------------------------------
| Emotional Check-in Routes
|--------------------------------------------------------------------------
*/
Route::controller(EmotionalCheckinsController::class)
    ->middleware(['auth:sanctum']) // semua route wajib login
    ->group(function () {
        Route::get('emotional-checkin', 'index')->middleware('permission:index emotional checkin');
        Route::get('emotional-checkin/{id}', 'get')->middleware('permission:get emotional checkin');
        Route::post('emotional-checkin', 'store')->middleware('permission:create emotional checkin');
        Route::patch('emotional-checkin/{uuid}', 'update')->middleware('permission:update emotional checkin');
        Route::delete('emotional-checkin/{id}', 'destroy')->middleware('permission:delete emotional checkin');
    });
