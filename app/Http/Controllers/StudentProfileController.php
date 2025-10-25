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
        $data['student'] = $student;
        
        $data['studentImageUrl'] = $student->studentImage ? asset('storage/' . $student->studentImage) : '/img/default-dp.jpg';

        // Status color maps from config
        $data['color'] = config('enrollment.enrollment_status_colors')[$student->enrollment->status ] ?? 'gray';

        $data['documents'] = [
            'PSA Birth Certificate'     => $student->birthCertificate,
            'Certificate of Good Moral' => $student->goodMoral,
            'Barangay Clearance'        => $student->brgyClearance,            
        ];

        $requiredDocuments = [];
        if($student->enrollment->admissionType == 'Transferee') {
            $requiredDocuments = [
                'Transcript of Records'     => $student->tor,
                'Honorable Dismissal'       => $student->honDismissal,
            ];
        } else {
            $requiredDocuments = [
                'Form 138 (Report Card)'    => $student->reportCard,
                'Form 137'                  => $student->form137,
            ];
        }
        $data['documents'] = array_merge($data['documents'], $requiredDocuments);

        $data['enrolled'] = $student->enrollment && in_array($student->enrollment->status, ['Enrolled', 'Completed']);
        
        return view('student.details', $data);
    }
    
    public function edit($id)
    {
        if (Auth::user()->role->name == 'Student') {
            $student = Auth::user()->student;
            $user = Auth::user();
            if (!$student) {
                abort(404);
            }
        } else {
            $student = Student::findOrFail($id);
            $user = $student->user;
        }

        $student = Student::findOrFail($id);

        $data['student'] = $student;
        $data['isStudent'] = Auth::user()->role->name == 'Student';

        return view('student.edit', $data);
    }

    public function update(Request $request)
    {
        // if (Auth::user()->role->name == 'Student') {
        //     $student = Auth::user()->student;
        //     $user = Auth::user();
        //     if (!$student) {
        //         abort(404);
        //     }
        // } else {
        // }
        $student = Student::findOrFail($request->input('id'));
        $user = $student->user;
        
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'course' => 'required|in:BSIT,BSED,BSBA',
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
        // Update course in enrollment
        if ($student->enrollment) {
            $student->enrollment->course = $validated['course'];
            $student->enrollment->save();
        }
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
