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
            'admissionType' => 'required|in:Freshmen,Transferee,Returnee',
            'yearLevel' => 'required|in:firstYear,secondYear,thirdYear,fourthYear',
            'address' => 'required|string|max:255',
            'baranggay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'studentImage' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'birthCertificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'form137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'goodMoral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'reportCard' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tor' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'honDismissal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'brgyClearance' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'guardianFName' => 'required|string|max:255',
            'guardianMName' => 'required|string|max:255',
            'guardianLName' => 'required|string|max:255',
            'guardianEmail' => 'required|email|max:255',
            'guardianContact' => 'required|string|max:255',
            'guardianRelationship' => 'required|string|max:255',
            'guardianAddress' => 'required|string|max:255',
            // Parent/guardian and educational background additional fields
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
            'baranggay' => $validated['baranggay'] ?? null,
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'admissionType' => $validated['admissionType'] ?? null,
            'yearLevel' => $validated['yearLevel'] ?? null,
            'guardianFName' => $validated['guardianFName'],
            'guardianMName' => $validated['guardianMName'],
            'guardianLName' => $validated['guardianLName'],
            'guardianEmail' => $validated['guardianEmail'],
            'guardianContact' => $validated['guardianContact'],
            'guardianRelationship' => $validated['guardianRelationship'],
            'guardianAddress' => $validated['guardianAddress'],
            // parents
            'fatherFName' => $validated['fatherFName'] ?? null,
            'fatherMName' => $validated['fatherMName'] ?? null,
            'fatherLName' => $validated['fatherLName'] ?? null,
            'fatherSuffix' => $validated['fatherSuffix'] ?? null,
            'motherFName' => $validated['motherFName'] ?? null,
            'motherMName' => $validated['motherMName'] ?? null,
            'motherLName' => $validated['motherLName'] ?? null,
            // educational background
            'primarySchool' => $validated['primarySchool'] ?? null,
            'primarySchoolYearGraduated' => $validated['primarySchoolYearGraduated'] ?? null,
            'secondarySchool' => $validated['secondarySchool'] ?? null,
            'secondarySchoolYearGraduated' => $validated['secondarySchoolYearGraduated'] ?? null,
            'lastSchoolAttended' => $validated['lastSchoolAttended'] ?? null,
            'lastSchoolAttendedYearGraduated' => $validated['lastSchoolAttendedYearGraduated'] ?? null,
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
            'school_year' => date('Y').'-'.(date('Y') + 1), // Current to next year
            'reference_code' => $enrollmentReference,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
