<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ApiRegisterController;
use App\Http\Controllers\Api\Auth\ApiLoginController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\QueueController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('app')->group(function () {

    Route::prefix('auth')->middleware('throttle:auth')->group(function () {
        Route::post('/register', [ApiRegisterController::class, 'register']);
        
        Route::post('/login/request', [ApiLoginController::class, 'requestLogin']);
        Route::post('/login/verify', [ApiLoginController::class, 'verifyLogin']);
        
        Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [ApiLoginController::class, 'logout']);
        Route::get('/me', [UserController::class, 'show']);
        Route::put('/me/update', [UserController::class, 'updateProfile']);
    });

});

// we not need token for this route because it's public and used to show hospitals to users without login
Route::get('/hospital', [HospitalController::class, 'getHospitalsByType'])->name('hospital.index');

Route::prefix('hospital')->name('hospital.')->middleware('auth:sanctum')->group(function () {
    Route::get('/types', [HospitalController::class, 'getHospitalTypes'])->name('types');
    Route::get('/{id}', [HospitalController::class, 'getHospitalDetails'])->name('show');
});

Route::prefix('department')->name('department.')->middleware('auth:sanctum')->group(function () {
    Route::get('/schedule', [DepartmentController::class, 'getDepartmentSchedule'])->name('schedule');
    Route::get('/{departmentId}/calendar', [DepartmentController::class, 'calendar'])->name('calendar');
    Route::post('/{departmentId}/appointments/book', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/{departmentId}/queue/book', [QueueController::class, 'store'])->name('queue.store');
});

Route::prefix('feedback')->name('feedback.')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [FeedbackController::class, 'store'])->name('store');
});

Route::prefix('queue')->name('queue.')->middleware('auth:sanctum')->group(function () {
    Route::post('/{queueId}/arrive', [QueueController::class, 'arrive'])->name('arrive');
    Route::get('/today', [QueueController::class, 'myQueuesToday'])->name('today');
    Route::get('/department/{id}/status', [QueueController::class, 'departmentStatus'])->name('department.status');
    Route::post('/{queueId}/done', [QueueController::class, 'done'])->name('done');
    Route::post('/{queueId}/skip', [QueueController::class, 'skip'])->name('skip');
});

Route::prefix('appointment')->name('appointment.')->middleware('auth:sanctum')->group(function () {
    Route::get('/my', [AppointmentController::class, 'myAppointments'])->name('my');
});
