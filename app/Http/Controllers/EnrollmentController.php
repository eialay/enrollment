<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Student::query();
        if ($status) {
            $query->where('status', $status);
        }
        $students = $query->get();
        $statuses = Student::select('status')->distinct()->pluck('status');

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

    public function approve($id)
    {
        $student = Student::findOrFail($id);
        if ($student->status === 'Pending Review') {
            $student->status = 'Pending Payment';
            $student->save();
            return redirect()->back()->with('success', 'Student approved and status updated to Pending Payment.');
        }
        return redirect()->back()->with('error', 'Unable to approve student.');
    }
}
