<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Enrollment;

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
                $paidCount = Enrollment::where('status', 'Paid')->count();

                $data['cards'] = [
                    ['title' => 'Paid', 'value' => $paidCount, 'icon' => 'fa-check-circle', 'color' => 'teal', 'link' => route('payments.list', ['status' => 'Paid'])],
                    [ 'title' => 'Pending Payment', 'value' => $pendingPaymentCount, 'icon' => 'fa-wallet', 'color' => 'orange', 'link' => route('enrollment.index', ['status' => 'Pending Payment']) ],
                    [ 'title' => 'Enrolled Students', 'value' => $enrolledCount, 'icon' => 'fa-user-check', 'color' => 'green', 'link' => route('enrollment.index', ['status' => 'Enrolled'])    ],
                    [ 'title' => 'Total Students ('.date('Y').')' , 'value' => $studentCount, 'icon' => 'fa-users', 'color' => 'blue', 'link' => route('enrollment.index')  ],
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
                break;
            default:
                return redirect()->route('login')->with('error', 'Unauthorized access.');
        }


        return view('dashboard', $data);
    }



}