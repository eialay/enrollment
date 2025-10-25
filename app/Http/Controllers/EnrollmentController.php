<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\EnrollmentQueue;
use App\Models\Payment;
use Illuminate\Support\Str;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $referenceCode = $request->query('reference_code');
        $query = Student::query();
        if ($status) {
            $query->whereHas('enrollment', function($q) use ($status) {
                $q->where('status', $status);
            });
        }
        if ($referenceCode) {
            $query->whereHas('enrollment', function($q) use ($referenceCode) {
                $q->where('reference_code', 'like', "%$referenceCode%");
            });
        }
        $students = $query->get();
        $statuses = Enrollment::select('status')->distinct()->pluck('status');

        // Status color maps from config
        $statusColors = config('enrollment.enrollment_status_colors');

        return view('enrollment_list', compact('students', 'statuses', 'status', 'referenceCode', 'statusColors'));
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

            // Ensure a Payment record exists for the student
            if (!$student->payment) {
                $referenceCode = 'PAY'.date('y') . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                Payment::create([
                    'student_id' => $student->id,
                    'reference_code' => $referenceCode,
                    'balance' => 2000,
                    'paid_amount' => 0,
                    'status' => 'Unpaid',
                    'remarks' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]);
            }

            // Notify student and guardian about approval
            EmailController::sendEmail(
                ['student' => optional($student->user)->email, 'parent' => $student->guardianEmail ?? null],
                'Enrollment Approved - ' . ($student->enrollment->reference_code ?? 'No Ref'),
                "Hello,\n\nYour enrollment has been approved and is now pending payment.\n\nDetails:\n- Enrollment Reference: " . ($student->enrollment->reference_code ?? 'N/A') . "\n- Status: Pending Payment\n\nPlease proceed with the payment to complete your enrollment.\n\nThank you."
            );

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


    public function queueList()
    {
        $queueList = EnrollmentQueue::orderBy('created_at', 'asc')
            ->get();
        return view('enrollment_queue_list', compact('queueList'));
    }
    public function getQueueNumber(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student record not found.');
        }
        
        // Generate a unique queue number
        $queueNumber = 'Q' . rand(1000, 9999);
        // Store in DB
        $queue = EnrollmentQueue::create([
            'enrollment_code' => $student->enrollment->reference_code,
            'queue_number' => $queueNumber,
            'status' => 'Waiting',
        ]);
        
        EmailController::sendEmail(
            ['student' => $student->user->email ?? null],
            'Enrollment Queue Number',
            "Hello,\n\nYour enrollment queue number is: $queueNumber\n\nPlease wait for your number to be called for enrollment processing.\n\nThank you."
        );

        //test
        
        return redirect()->route('dashboard');
    }
    public function deleteQueue($id)
    {
        $queue = EnrollmentQueue::findOrFail($id);
        $queue->delete();
        return redirect()->route('enrollment.index')->with('success', 'Queue record deleted successfully.');
    }
}
