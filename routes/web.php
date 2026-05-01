<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ==========================================
// Controllers
// ==========================================
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HospitalRequestController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\InsuranceController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\DepartmentScheduleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HospitalProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// مسارات عامة (Public)
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');


// ==========================================
// مسارات الزوار (الغير مسجلين)
// ==========================================
Route::middleware('guest')->group(function () {
    // تسجيل الدخول
    Route::get('/login', function () { return view('welcome'); })->name('welcome');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.auth.login');

    // إنشاء حساب
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('admin.auth.register');
});


// ==========================================
// مسارات المستخدمين المسجلين (Auth)
// ==========================================
Route::middleware('auth')->group(function () {

   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // مسار تقديم طلب المستشفى (لأول مرة)
    Route::post('/hospital/setup', [HospitalProfileController::class, 'store'])->name('hospital.setup.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // مسارات الإدارة (Admin)
    Route::prefix('admin')->name('admin.')->group(function () {

        // الشكاوى والاقتراحات
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');

        // الموافقات (Approvals)
        Route::prefix('approvals')->name('approvals.')->group(function () {
            Route::get('/', [HospitalRequestController::class, 'index'])->name('index');
            Route::get('/fetch', [HospitalRequestController::class, 'fetch'])->name('fetch');
            Route::get('/{hospitalRequest}', [HospitalRequestController::class, 'show'])->name('show');
            Route::post('/{hospitalRequest}/approve', [HospitalRequestController::class, 'approve'])->name('approve');
            Route::post('/{hospitalRequest}/reject', [HospitalRequestController::class, 'reject'])->name('reject');
        });

        // المستشفيات (Hospitals)
        Route::prefix('hospitals')->name('hospitals.')->group(function () {
            Route::get('/', [HospitalController::class, 'index'])->name('index');
            Route::get('/fetch', [HospitalController::class, 'fetch'])->name('fetch');
            Route::get('/{hospital}', [HospitalController::class, 'show'])->name('show');
            Route::delete('/{hospital}', [HospitalController::class, 'destroy'])->name('destroy');
        });

        // التأمين (Insurance)
        Route::prefix('insurance')->name('insurance.')->group(function () {
            Route::get('/', [InsuranceController::class, 'index'])->name('index');
            Route::get('/fetch', [InsuranceController::class, 'fetch'])->name('fetch');
            Route::get('/{insuranceRequest}', [InsuranceController::class, 'show'])->name('show');
            Route::post('/{insuranceRequest}/status', [InsuranceController::class, 'updateStatus'])->name('updateStatus');
        });

        // الموظفين (Employees)
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/fetch', [EmployeeController::class, 'fetch'])->name('fetch');
            Route::post('/store', [EmployeeController::class, 'store'])->name('store');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
        });

        // الأقسام والدوام (Departments & Schedules)
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [AdminDepartmentController::class, 'index'])->name('index');
            Route::post('/', [AdminDepartmentController::class, 'store'])->name('store');

            Route::prefix('{id}')->group(function () {
                Route::get('/schedule', [DepartmentScheduleController::class, 'schedule'])->name('schedule');
                Route::post('/schedule', [DepartmentScheduleController::class, 'storeSchedule'])->name('schedule.store');
                Route::get('/queue', [DepartmentScheduleController::class, 'queue'])->name('queue');
            });
        });

    });
});
