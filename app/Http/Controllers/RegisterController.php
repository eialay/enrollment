<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Validate an email via query parameter and return JSON { exists: bool }
     * GET /api/validate-email?email=...
     */
    public function validateEmail(Request $request)
    {
        $email = $request->query('email');
        if (empty($email)) {
            return response()->json(['error' => 'email parameter is required'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'invalid email format'], 422);
        }

        $exists = User::where('email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'course' => 'required|in:BSIT,BSED,BSBA',
            'admissionType' => 'required|in:Freshman,Transferee,Returnee',
            'yearLevel' => 'required|in:First Year,Second Year,Third Year,Fourth Year',
            'address' => 'required|string|max:255',
            'baranggay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'studentImage' => 'required|file|mimes:jpg,jpeg,png',
            'birthCertificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'form137' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'goodMoral' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'reportCard' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'tor' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'honDismissal' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'brgyClearance' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'guardianFName' => 'required|string|max:255',
            'guardianMName' => 'required|string|max:255',
            'guardianLName' => 'required|string|max:255',
            'guardianEmail' => 'required|email|max:255',
            'guardianContact' => 'required|string|max:255',
            'guardianRelationship' => 'required|string|max:255',
            'guardianAddress' => 'required|string|max:255',
            'fatherFName' => 'required|string|max:255',
            'fatherMName' => 'required|string|max:255',
            'fatherLName' => 'required|string|max:255',
            'fatherSuffix' => 'nullable|string|max:50',
            'motherFName' => 'required|string|max:255',
            'motherMName' => 'required|string|max:255',
            'motherLName' => 'required|string|max:255',
            'primarySchool' => 'required|string|max:255',
            'primarySchoolYearGraduated' => 'required|digits:4',
            'secondarySchool' => 'required|string|max:255',
            'secondarySchoolYearGraduated' => 'required|digits:4',
            'lastSchoolAttended' => 'required|string|max:255',
            'lastSchoolAttendedYearGraduated' => 'required|digits:4',
        ]);

        $user = User::create([
            'name' => $validated['firstname'].' '.$validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 4,
        ]);

        $studentData = [
            'user_id' => $user->id,
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'],
            'lastname' => $validated['lastname'],
            'contact' => $validated['contact'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'baranggay' => $validated['baranggay'],
            'city' => $validated['city'],
            'province' => $validated['province'],        
            'guardianFName' => $validated['guardianFName'],
            'guardianMName' => $validated['guardianMName'],
            'guardianLName' => $validated['guardianLName'],
            'guardianEmail' => $validated['guardianEmail'],
            'guardianContact' => $validated['guardianContact'],
            'guardianRelationship' => $validated['guardianRelationship'],
            'guardianAddress' => $validated['guardianAddress'],
            'fatherFName' => $validated['fatherFName'],
            'fatherMName' => $validated['fatherMName'],
            'fatherLName' => $validated['fatherLName'],            
            'motherFName' => $validated['motherFName'],
            'motherMName' => $validated['motherMName'],
            'motherLName' => $validated['motherLName'],
            'primarySchool' => $validated['primarySchool'],
            'primarySchoolYearGraduated' => $validated['primarySchoolYearGraduated'],
            'secondarySchool' => $validated['secondarySchool'],
            'secondarySchoolYearGraduated' => $validated['secondarySchoolYearGraduated'],
            'lastSchoolAttended' => $validated['lastSchoolAttended'],
            'lastSchoolAttendedYearGraduated' => $validated['lastSchoolAttendedYearGraduated'],
        ];
        // Handle student image upload
        if ($request->hasFile('studentImage')) {
            $studentData['studentImage'] = $request->file('studentImage')->store('student_images', 'public');
        }

        foreach(['birthCertificate', 'form137', 'goodMoral', 'reportCard', 'tor', 'honDismissal', 'brgyClearance'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $studentData[$fileField] = $request->file($fileField)->store('documents', 'public');
            }
        }

        $student = Student::create($studentData);

        // Generate enrollment reference code (e.g., ENR-<year><student_id>)
        $enrollmentReference = 'ENR-'.date('y').str_pad($student->id, 6, '0', STR_PAD_LEFT);

        // Insert into enrollments table
        Enrollment::create([
            'student_id' => $student->id,
            'status' => 'Pending Review',
            'course' => $validated['course'],
            'admission_type' => $validated['admissionType'],
            'year_level' => $validated['yearLevel'],
            'school_year' => date('Y').'-'.(date('Y') + 1), // Current to next year
            'reference_code' => $enrollmentReference,
        ]);

        EmailController::sendEmail(
            ['student' => optional($student->user)->email, 'parent' => $student->guardianEmail ?? null],
            'Registration Successful - ' . ($student->enrollment->reference_code ?? 'No Ref'),
            "Hello,\n\nYour registration has been successful and is now pending review.\n\nDetails:\n- Enrollment Reference: " . ($student->enrollment->reference_code ?? 'N/A') . "\n- Status: Pending Review\n\nLog into your account for the next steps.\n\nThank you."
        );

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
