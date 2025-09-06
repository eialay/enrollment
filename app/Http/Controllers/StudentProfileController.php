<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;

class StudentProfileController extends Controller
{
    public function show($id)
    {
        $student = Student::findOrFail($id);

        // Status color maps from config
        $color = config('enrollment.enrollment_status_colors')[$student->enrollment->status ] ?? 'gray';

        return view('student.details', compact('student', 'color'));
    }
    
    public function edit()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404);
        }
        return view('student.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::user()->student;
        $user = Auth::user();
        if (!$student) {
            abort(404);
        }
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'address' => 'required|string|max:255',
            'studentImage' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
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
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        // Update user email and password if changed
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        // Update student fields
        $student->fill($validated);
        // Handle file uploads
        foreach(['studentImage', 'birthCertificate', 'form137', 'goodMoral', 'reportCard'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $student->$fileField = $request->file($fileField)->store('documents', 'public');
            }
        }
        $student->save();

        // If enrollment status is rejected, set to Pending Review
        if ($student->enrollment && $student->enrollment->status === 'Rejected') {
            $student->enrollment->status = 'Pending Review';
            $student->enrollment->remarks = null;
            $student->enrollment->save();
        }
        return redirect()->route('students.show', ['id' => $student->id])->with('success', 'Profile updated successfully!');
    }
}
