<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CashierController extends Controller
{
    public function instructions()
    {
        return view('payments.cashier_instructions');
    }

    public function getQueueNumber(Request $request)
    {
        // Simulate queue number generation (could be replaced with DB logic)
        $queueNumber = 'Q' . rand(1000, 9999);
        Session::flash('queue_number', $queueNumber);
        return redirect()->route('cashier.instructions');
    }
}
