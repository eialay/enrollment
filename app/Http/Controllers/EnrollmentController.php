<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Student::query();
        if ($status) {
            $query->whereHas('enrollment', function($q) use ($status) {
                $q->where('status', $status);
            });
        }
        $students = $query->get();
        $statuses = Enrollment::select('status')->distinct()->pluck('status');

    // Status color maps from config
    $statusColors = config('enrollment.enrollment_status_colors');

    return view('enrollment_list', compact('students', 'statuses', 'status', 'statusColors'));
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        // Status color maps from config
        $color = config('enrollment.enrollment_status_colors')[$student->enrollment->status ] ?? 'gray';

        return view('student.details', compact('student', 'color'));
    }

    public function approve($id, Request $request)
    {
        $student = Student::findOrFail($id);
        if ($student->enrollment->status === 'Pending Review') {
            $student->enrollment->status = 'Pending Payment';
            $student->enrollment->remarks = $request->input('remarks');
            $student->enrollment->reviewed_by = auth()->user()->id;
            $student->enrollment->reviewed_at = now();
            $student->enrollment->save();

            // Insert a record to payments table
            Payment::create([
                'student_id' => $student->id,
                'balance' => 2000,
                'paid_amount' => 0,
                'status' => 'Pending Payment',
                'remarks' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);

            return redirect()->back()->with('success', 'Student approved and status updated to Pending Payment.');
        }
        return redirect()->back()->with('error', 'Unable to approve student.');
    }

    public function reject($id, Request $request)
    {
        $student = Student::findOrFail($id);
        if ($student->enrollment->status === 'Pending Review') {
            $student->enrollment->status = 'Rejected';
            $student->enrollment->remarks = $request->input('remarks');
            $student->enrollment->reviewed_by = auth()->user()->id;
            $student->enrollment->reviewed_at = now();
            $student->enrollment->save();
            return redirect()->back()->with('success', 'Student has been rejected.');
        }
        return redirect()->back()->with('error', 'Unable to reject student.');
    }
}
