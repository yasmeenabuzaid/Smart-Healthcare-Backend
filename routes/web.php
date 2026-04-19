<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HospitalRequestController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\InsuranceController;

Route::prefix('admin/insurance')->name('admin.insurance.')->group(function () {
    Route::get('/', [InsuranceController::class, 'index'])->name('index');
    Route::get('/fetch', [InsuranceController::class, 'fetch'])->name('fetch');
    Route::get('/{insuranceRequest}', [InsuranceController::class, 'show'])->name('show');
    Route::post('/{insuranceRequest}/status', [InsuranceController::class, 'updateStatus'])->name('updateStatus');
});
Route::prefix('admin/hospitals')->name('admin.hospitals.')->group(function () {
    Route::get('/', [HospitalController::class, 'index'])->name('index');
    Route::get('/fetch', [HospitalController::class, 'fetch'])->name('fetch');
    Route::get('/{hospital}', [HospitalController::class, 'show'])->name('show');
    Route::delete('/{hospital}', [HospitalController::class, 'destroy'])->name('destroy');
});
Route::prefix('admin/approvals')->name('admin.approvals.')->group(function () {
    Route::get('/', [HospitalRequestController::class, 'index'])->name('index');
    Route::get('/fetch', [HospitalRequestController::class, 'fetch'])->name('fetch');
    Route::get('/{hospitalRequest}', [HospitalRequestController::class, 'show'])->name('show');
    Route::post('/{hospitalRequest}/approve', [HospitalRequestController::class, 'approve'])->name('approve');
    Route::post('/{hospitalRequest}/reject', [HospitalRequestController::class, 'reject'])->name('reject');
});
Route::prefix('admin/employees')->name('admin.employees.')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('index');
    Route::get('/fetch', [EmployeeController::class, 'fetch'])->name('fetch'); // لجلب البيانات بالـ AJAX
    Route::post('/store', [EmployeeController::class, 'store'])->name('store');
    Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
});
Route::get('/', function () {
    return view('dashboard');
});
