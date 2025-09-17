<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        $data = [
            'user' => Auth::user(),
        ];

        $student = new Student();
        $studentCount = $student->whereYear('created_at', date('Y'))->count();
        $pendingReviewCount = Enrollment::where('status', 'Pending Review')->count();
        $pendingPaymentCount = Enrollment::where('status', 'Pending Payment')->count();
        $enrolledCount = Enrollment::where('status', 'Enrolled')->count();

        $data['cards'] = [
            [ 'title' => 'Pending Review', 'value' => $pendingReviewCount, 'icon' => 'fa-hourglass-half', 'color' => 'yellow', 'link' => route('enrollment.index', ['status' => 'Pending Review']) ],
            [ 'title' => 'Pending Payment', 'value' => $pendingPaymentCount, 'icon' => 'fa-wallet', 'color' => 'orange', 'link' => route('enrollment.index', ['status' => 'Pending Payment']) ],
            [ 'title' => 'Enrolled Students', 'value' => $enrolledCount, 'icon' => 'fa-user-check', 'color' => 'green', 'link' => route('enrollment.index', ['status' => 'Enrolled'])    ],
            [ 'title' => 'Total Students ('.date('Y').')' , 'value' => $studentCount, 'icon' => 'fa-users', 'color' => 'blue', 'link' => route('enrollment.index')  ],
        ];


        switch (Auth::user()->role->name) {
            case 'Admin':

                break;
            case 'Registrar':

                break;
            case 'Cashier':
                $unpaidCount = Payment::where('status', 'Unpaid')->count();
                $forApprovalCount = Payment::where('status', 'Pending Approval')->count();
                $paidCount = Payment::where('status', 'Paid')->count();

                $data['cards'] = [
                    ['title' => 'Unpaid', 'value' => $unpaidCount, 'icon' => 'fa-times-circle', 'color' => 'red', 'link' => route('payments.list', ['status' => 'Unpaid']) ],
                    ['title' => 'For Approval', 'value' => $forApprovalCount, 'icon' => 'fa-clock', 'color' => 'yellow', 'link' => route('payments.list', ['status' => 'Pending Approval']) ],
                    ['title' => 'Paid', 'value' => $paidCount, 'icon' => 'fa-check-circle', 'color' => 'green', 'link' => route('payments.list', ['status' => 'Paid']) ],
                ];

                break;
            case 'Student':
                $studentRecord = $student->where('user_id', Auth::id())->first();
                if ($studentRecord && $studentRecord->enrollment) {
                    $data['student'] = $studentRecord;
                    $status = $studentRecord->enrollment->status;
                    $statusColors = config('enrollment.enrollment_status_colors');
                    
                    $data['cards'] = [
                        [ 
                            'title' => 'Enrollment Status', 
                            'value' => $status, 
                            'icon' => 'fa-user-graduate', 
                            'color' => $statusColors[$status] ?? 'yellow',
                            'link' => route('students.show', $studentRecord->id)
                        ],
                    ];

                    if($status === 'Pending Review') {
                        $data['cards'][] = [
                            'title' => 'Enrollment Reference Code', 
                            'value' => $studentRecord->enrollment->reference_code, 
                            'icon' => 'fa-id-card', 
                            'color' => 'blue',
                        ];
                    }
                }

                if ( $studentRecord && $studentRecord->payment) {
                    $paymentStatus = $studentRecord->payment->status;
                    $paymentStatusColors = config('enrollment.payment_status_colors');

                    $data['cards'][] = [
                        'title' => 'Payment Status',
                        'value' => $paymentStatus,
                        'icon' => 'fa-receipt',
                        'color' => $paymentStatusColors[$paymentStatus] ?? 'yellow',                        
                    ];
                }
                break;
            default:
                return redirect()->route('login')->with('error', 'Unauthorized access.');
        }


        return view('dashboard', $data);
    }



}