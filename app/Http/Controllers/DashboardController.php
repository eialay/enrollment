<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

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
        $pendingReviewCount = $student->where('status', 'Pending Review')->count();
        $pendingPaymentCount = $student->where('status', 'Pending Payment')->count();
        $enrolledCount = $student->where('status', 'Enrolled')->count();

         $data['cards'] = [
            [ 'title' => 'Total Students ('.date('Y').')' , 'value' => $studentCount, 'icon' => 'fa-users', 'color' => 'blue' ],
            [ 'title' => 'Pending Review', 'value' => $pendingReviewCount, 'icon' => 'fa-hourglass-half', 'color' => 'yellow' ],
            [ 'title' => 'Pending Payment', 'value' => $pendingPaymentCount, 'icon' => 'fa-wallet', 'color' => 'orange' ],
            [ 'title' => 'Enrolled Students', 'value' => $enrolledCount, 'icon' => 'fa-user-check', 'color' => 'green' ],
        ];


        switch (Auth::user()->role->name) {
            case 'Admin':

                break;
            case 'Registrar':

                break;
            case 'Cashier':
                break;
            case 'Student':
                $studentRecord = $student->where('user_id', Auth::id())->first();
                if ($studentRecord) {
                    $data['student'] = $studentRecord;
                    $statusColorMap = [
                        'Enrolled' => 'green',
                        'Pending' => 'yellow',
                        'Rejected' => 'red',
                    ];

                    $data['cards'] = [
                        [ 
                            'title' => 'Enrollment Status', 
                            'value' => $studentRecord->status, 
                            'icon' => 'fa-user-graduate', 
                            'color' => $statusColorMap[ explode(' ', $studentRecord->status)[0] ] ?? 'yellow'
                        ],
                    ];
                } 

                break;
            default:
                return redirect()->route('login')->with('error', 'Unauthorized access.');
        }


        return view('dashboard', $data);
    }



}