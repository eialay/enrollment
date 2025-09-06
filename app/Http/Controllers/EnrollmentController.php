<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;

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

        return view('student_details', compact('student', 'color'));
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
