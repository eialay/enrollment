<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function show()
    {
        return view('payments');
    }

    public function settle(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $student = Auth::user()->student;
        if (!$student || !$student->payment) {
            return redirect()->back()->with('error', 'No payment record found.');
        }
        $payment = $student->payment;
        $amount = floatval($request->input('amount'));
        $remaining = $payment->balance - $payment->paid_amount;
        if ($amount > $remaining) {
            return redirect()->back()->with('error', 'Amount exceeds remaining balance.');
        }
        $payment->paid_amount += $amount;
        if ($payment->paid_amount >= $payment->balance) {
            $payment->status = 'Paid';
        }
        $payment->save();
        return redirect()->back()->with('success', 'Payment successful!');
    }
}
