<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OcrController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');


// Password Reset Routes
Route::get('password/reset', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

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
    Route::get('/test-email', [PaymentController::class, 'sendTestEmail'])->name('test.email');
});

// Student profile
Route::middleware(['auth'])->group(function () {
    Route::get('/student/{id}', [StudentProfileController::class, 'show'])->name('students.show');
    Route::get('/student/{id}/edit', [StudentProfileController::class, 'edit'])->name('students.edit');
    Route::post('/student/{id}/update', [StudentProfileController::class, 'update'])->name('students.update');
});

// User Management
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});


// OCR scan endpoint (no auth required for registration)
Route::post('/ocr/scan', [OcrController::class, 'scan'])->name('ocr.scan');

// Test regex extraction route
Route::get('/test-regex', function () {
    $fullText = "NATIONAL\nPage 1 of 1, 1 Copy\n(Copy for OCRG)\nwww.\nNS\nMunicipal Form No. 102\nRevised January 1993)\nCE\n(To be accomplished in quadruplicate)\nRepublic of the Philippines\nOFFICE OF THE CIVIL REGISTRAR GENERAL\nCERTIFICATE OF LIVE BIRTH\n(Fill out completely, accurately and legibly. Use ink or typewriter.\nPlace X before the appropriate answer in Items 2, 5a, 5b and 19a.)\nMETRO MANTLA\nProvince.\nCity\/Municipality.\nCH\n1. NAME\nPARA\u00d1AQUE CITY\n(First)\nLEVHYLYN\nREMARKS\/ANNOTATION\n5787\n(Last)\nVILLANUEVA\n(month) (year)\n2003\n(Province)\nFor OCRG USE ONLY:\nPopulation Reference No.\nTO BE FILLED UP AT THE\nOFFICE OF THE CIVIL\nREGISTRAR\nRegistry No.\n2003-\n(Middle)\nLAS MARIAS\n2. SEX\n3. DATE OF BIRTH\n1 Male\nX2 Female\n4. PLACE OF\n(Name of Hospital\/Clinic\/Institution\/\n(day)\n26 July\n(City\/Municipality)\nBIRTH\nHouse No. Street, Barangay";

    $fields = [
        'firstname' => '',
        'middlename' => '',
        'lastname' => '',
        'birthdate' => '',
    ];

    // Extract first and middle name
    if (preg_match('/1\. NAME\s*([A-Z ]+)\s*\(Middle\)\s*([A-Z]+)\s*2\. SEX/i', $fullText, $matches)) {
        $fields['firstname'] = trim($matches[1]);
        $fields['middlename'] = trim($matches[2]);
    }
    if (preg_match('/\(18\)\s*([A-Z]+)\s*\(Month\)/i', $fullText, $matches)) {
        $fields['lastname'] = trim($matches[1]);
    }

    if (preg_match('/\(First\)\s*\n?([A-Z\- ]+)/i', $fullText, $matches)) {
        $fields['firstname'] = trim($matches[1]);
    }
    if (preg_match('/\(Middle\)\s*\n?([A-Z\- ]+)/i', $fullText, $matches)) {
        $fields['middlename'] = trim($matches[1]);
    }
    if (preg_match('/\(Last\)\s*\n?([A-Z\- ]+)/i', $fullText, $matches)) {
        $fields['lastname'] = trim($matches[1]);
    }

    // Extract birthdate
    if (preg_match('/(\d{2} [A-Z]+ \d{4})/', $fullText, $matches)) {
        $fields['birthdate'] = $matches[1];
    }

    dd($fields);

});
