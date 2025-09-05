<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;

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
Route::get('/enrollment/{id}', [EnrollmentController::class, 'show'])->middleware('auth')->name('students.show');
Route::post('/enrollment/{id}/approve', [EnrollmentController::class, 'approve'])->middleware('auth')->name('students.approve');
Route::post('/enrollment/{id}/reject', [EnrollmentController::class, 'reject'])->middleware('auth')->name('students.reject');

