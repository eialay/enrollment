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
            'address' => 'required|string|max:255',
            'birthCertificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
        ]);

        $studentData = [
            'user_id' => $user->id,
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'],
            'lastname' => $validated['lastname'],
            'contact' => $validated['contact'],
            'birthdate' => $validated['birthdate'],
            'address' => $validated['address'],
            'guardianFName' => $validated['guardianFName'],
            'guardianMName' => $validated['guardianMName'],
            'guardianLName' => $validated['guardianLName'],
            'guardianEmail' => $validated['guardianEmail'],
            'guardianContact' => $validated['guardianContact'],
            'guardianRelationship' => $validated['guardianRelationship'],
            'guardianAddress' => $validated['guardianAddress'],
        ];

        foreach(['birthCertificate', 'form137', 'goodMoral', 'reportCard'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $studentData[$fileField] = $request->file($fileField)->store('documents', 'public');
            }
        }

        $student = Student::create($studentData);

        // Insert into enrollments table
        Enrollment::create([
            'student_id' => $student->id,
            'status' => 'Pending Review',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
