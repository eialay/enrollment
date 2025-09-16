<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'address' => 'required|string|max:255',
            'studentImage' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'birthCertificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'form137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'goodMoral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'reportCard' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'guardianFName' => 'required|string|max:255',
            'guardianMName' => 'required|string|max:255',
            'guardianLName' => 'required|string|max:255',
            'guardianEmail' => 'required|email|max:255',
            'guardianContact' => 'required|string|max:255',
            'guardianRelationship' => 'required|string|max:255',
            'guardianAddress' => 'required|string|max:255',
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
            'guardianFName' => $validated['guardianFName'],
            'guardianMName' => $validated['guardianMName'],
            'guardianLName' => $validated['guardianLName'],
            'guardianEmail' => $validated['guardianEmail'],
            'guardianContact' => $validated['guardianContact'],
            'guardianRelationship' => $validated['guardianRelationship'],
            'guardianAddress' => $validated['guardianAddress'],
        ];
        // Handle student image upload
        if ($request->hasFile('studentImage')) {
            $studentData['studentImage'] = $request->file('studentImage')->store('student_images', 'public');
        }

    foreach(['birthCertificate', 'form137', 'goodMoral', 'reportCard'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $studentData[$fileField] = $request->file($fileField)->store('documents', 'public');
            }
        }

        $student = Student::create($studentData);

        

        // Generate enrollment reference code (e.g., ENR-<year>-<student_id>)
        $enrollmentReference = 'ENR-' . date('Y') . '-' . $student->id;

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
