<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// INI YANG WAJIB ADA — JANGAN DI-COMMENT LAGI
Route::get('/auth/google', [AuthController::class, 'google_redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'google_callback']);

// Kalau kamu mau tetap pakai file terpisah, aktifkan ini:
// require __DIR__.'/auth.php';