<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentProfileController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Enrollment routes
Route::get('/enrollment', [EnrollmentController::class, 'index'])->middleware('auth')->name('enrollment.index');
Route::post('/enrollment/{id}/approve', [EnrollmentController::class, 'approve'])->middleware('auth')->name('students.approve');
Route::post('/enrollment/{id}/reject', [EnrollmentController::class, 'reject'])->middleware('auth')->name('students.reject');

// Payments routes
Route::middleware(['auth'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/list', [PaymentController::class, 'index'])->name('payments.list');
    Route::get('/payments/{id}', [PaymentController::class, 'showDetails'])->name('payments.details');
    Route::post('/payments/settle', [PaymentController::class, 'settle'])->name('payments.settle');
    Route::post('/payments/{id}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
});

// Student profile edit routes
Route::middleware(['auth'])->group(function () {
    Route::get('/student/{id}', [StudentProfileController::class, 'show'])->name('students.show');
    Route::get('/student/{id}/edit', [StudentProfileController::class, 'edit'])->name('students.edit');
    Route::post('/student/{id}/update', [StudentProfileController::class, 'update'])->name('students.update');
});