
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentProfileController;

use App\Http\Controllers\OcrController;

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
    Route::get('/test-email', [PaymentController::class, 'sendTestEmail'])->name('test.email');
});

// Student profile edit routes
Route::middleware(['auth'])->group(function () {
    Route::get('/student/{id}', [StudentProfileController::class, 'show'])->name('students.show');
    Route::get('/student/{id}/edit', [StudentProfileController::class, 'edit'])->name('students.edit');
    Route::post('/student/{id}/update', [StudentProfileController::class, 'update'])->name('students.update');
});

// OCR scan endpoint (no auth required for registration)
Route::post('/ocr/scan', [OcrController::class, 'scan'])->name('ocr.scan');

// Test regex extraction route
Route::get('/test-regex', function () {
    $fullText = "PINE\nPage 1 of 1, 2 Copies\nSTATISTICS\nPS\nMunicipal Form No. 102\n(Revised January 2007)\nProvince LAGUNA\nRepublic of the Philippines\nOFFICE OF THE CIVIL REGISTRAR GENERAL\nccomplished in quadruplicate using black ink)\nCERTIFICATE OF LIVE BIRTH\nRegistry No.\nMALE\n4. PLACE OF\nBIRTH\n(Name of Hospitaliniston\nHouse No, 5. Darangay\nCALAMBA MEDICAL CENTER, CROSSING,\nD\nSa. TYPE OF DIRTH\n(Single Twin Troe, etc))\nSINGLE\nN\/A\nNAME\nM\nT. MAIDEN\n8. CITIZENSHIP\nH10a. Total number of\n(Fire)\nWELLA\nCity\/Municipality CALAMBA CITY\n1. NAME\nMIGUEL ENRICO\n(Middle)\nUY\n2. SEX (Male\/Female)\n3. DATE OF\nBIRTH\n(Day)\n2014 00341\n(18)\nONTE\n(Month)\n(Year)\n04 JANUARY 2014\n(City Municity)\nGb. IF MULTIPLE BIRTH, CHILD WAS\n(ref. Second, Thed, etc.)\nCALAMBA CITY, LAGUNA\n50 BIRTH ORDER (\npiqueve birth\nSecond T\n(Province)\n6.WEIGHTAT BIRTH\nclin\nFIRST\n3,100\n(Middle)\nGONZALES\n8.RELIGION RELIGIOUS SECT\n(Last)\nUY\nFILIPINO\n100 No. of child\nCATHOLIC\n10 No. of children bom\n11. OCCUPATION\n12. AGE\nchildren bem w\nE\n01\nR\n13 RESIDENCE\narg including this birth\n01\nHouse No.. S. Barangay)\n(City\/Municipality)\nLOT 22 BLOCK 2 CUERVO 11, REAL, CALAMBA CITY, LAGUNA, PHILIPPINES\nave but are now dead\n00\nFINANCE SPECIALIST II\nbith joompleted yeen\n29\n(Province)\n(Country)\n14. NAME\nF\n(Fi\nEMERSON\n(Middle)\nDIMACULANGAN\n(Last)\nONTE\n15 CITIZENSHIP\n18. RELIGION RELIGIOUS SECT\nCATHOLIC\nFILIPINO\nE\nR\nRESIDENCE (House No. St., Barangay)\nLOT 22 BLOCK 2 CUERVO 11, REAL, CALAMBA CITY, LAGUNA, PHILIPPINES\nMARRIAGE OF PARENTS (not married, accomplish Afbdevil of Acknowledgementdmeston of Paternity at the bes)\n20.DATE\n(Mar (Day)\nDECEMBER 08, 2012\n21a ATTENDANT\n(Year)\n206.PLACE\n(City\/Municipality)\n(Province)\nSTO. TOMAS, BATANGAS, PHILIPPINES\n17.OCCUPATION\nSENIOR ENGINEER\n(City\/Municipality)\n(Province)\n16. AGE at theme of th\nbeth (completed years)\n30\n(Country)\n(Country)\nX\n1 Physician\n2 Nurse\n3 Midwe\n4 Hilot (Traditional Bets Atendent)\n5 Others (Specify).\n216 CERTIFICATION OF ATTENDANT AT BIRTH (Pycan, Nurse, Mdwila, Traditional Band)\nSignature\nI hereby certify that I attended the birth of the child who was born alive at 8:56 AM amipm on the date of birth specified above.\nName in Print CATHERINE REMOLLINO, MD.\nOB-GYNE\nTitle or Position\nC\/O CALAMBA MEDICAL CENTER, CROSSING,\nAddress CALAMBA CITY, LAGUNA\nJANUARY 06, 2014\nData\n22. CERTIFICATION OF INFORMAT\nI hereby cetry that\nmation suppiled and\ncorrect to my cowlege, and belief.\nSignature\nName in Prir EMERSON D. ONTE\nReletionship to the Chad FATHER\nAddress L22 82 CUERVO 1, REAL, CALAMBA CITY, LAGUNA\nDate\nJANUARY 06, 2014\n24. RECEIVED BY\nSignature\nName in Print\nTitle or Poon\nDate\nEDNALINA & MASONGSONG\nRegistration Officer V\nJAN 10 2010\nREMARKS\/ANNOTATIONS (For LCRO\/OCRG Use Only)\n23. PREPARED BY\nSignature\nName in Princ\nMARLYN A. ALMARIO, RN.\nTitle or Position MEDICAL RECORDS CLERK\nDate\nJANUARY 06, 2014\n25. REGISTERED BY THE CIVIL REGISTRAR\nSignature\nName in Print BILAGROS B. PINION\nTitle or Position CITY CIVIL REGISTRAR\nJAN 18 2014\nDate\nTO BE FILLED-UP AT THE OFFICE OF THE CIVIL REGISTRAR\n9\n11\n8241\n06365-60-991 HBD-01374-B1001\nBEST POSSIBLE IMAGE\nT080063659910137408052017001\nGC300675331\n60803405010821560803405\nBREN\n03405-B14A408-1\nDocumentary\nStamp Tax Paid\nLisa Graces. Pervates\nLISA GRACE S. BERSALES, Ph.D.\nNational Statistician and Civil Registrar General\nPhilippine Statistics Authority";

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

    // Extract last name (ONTE)
    if (preg_match('/\(18\)\s*([A-Z]+)\s*\(Month\)/i', $fullText, $matches)) {
        $fields['lastname'] = trim($matches[1]);
    }

    // Extract birthdate
    if (preg_match('/(\d{2} [A-Z]+ \d{4})/', $fullText, $matches)) {
        $fields['birthdate'] = $matches[1];
    }

    dd($fields);

});
