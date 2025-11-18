<?php

use Illuminate\Http\Request;
use App\Models\EmotionalCheckin;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SlackTestController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\ClassStudentController;
use App\Http\Controllers\Admin\InterventionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\admin\TeacherStudentController;
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


Route::post('/send-emotional-checkin/{checkin}', function (Request $request, $checkin) {
    $checkin = EmotionalCheckin::findOrFail($checkin);
    return app(NotificationController::class)->sendToSelected($checkin);
});

Route::post('/slack/test', [SlackTestController::class, 'sendNotification']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('students', StudentController::class);

    Route::get('/mentors', [MentorController::class, 'index']);
    Route::post('/mentors/{id}/assign-student', [MentorController::class, 'assignStudent']);

    Route::post('/interventions', [InterventionController::class, 'store']);
});
