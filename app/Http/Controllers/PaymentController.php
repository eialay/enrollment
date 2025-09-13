<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student.user'])->orderByDesc('updated_at');
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $payments = $query->get();
        return view('payments_list', compact('payments'));
    }

    public function show()
    {
        return view('payments');
    }

    public function showDetails($id)
    {
        $payment = Payment::with(['student.user'])->findOrFail($id);
        return view('payment_details', compact('payment'));
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
            // Also update enrollment status to Paid
            if ($payment->student && $payment->student->enrollment) {
                $payment->student->enrollment->status = 'Paid';
                $payment->student->enrollment->save();
            }
        }
        $payment->save();
        return redirect()->back()->with('success', 'Payment successful!');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $payment = Payment::findOrFail($id);
        $payment->status = 'Approved';
        $payment->remarks = $request->input('remarks', 'Approved by cashier');
        $payment->save();
        if ($payment->student && $payment->student->enrollment) {
            $payment->student->enrollment->status = 'Enrolled';
            $payment->student->enrollment->save();
        }
        return redirect()->back()->with('success', 'Payment approved.');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $payment = Payment::findOrFail($id);
        $payment->status = 'Rejected';
        $payment->remarks = $request->input('remarks', 'Rejected by cashier');
        $payment->save();
        // Optionally update enrollment status
        // if ($payment->student && $payment->student->enrollment) {
        //     $payment->student->enrollment->status = 'Rejected';
        //     $payment->student->enrollment->save();
        // }
        return redirect()->back()->with('success', 'Payment rejected.');
    }

    public function sendTestEmail()
    {
        $user = Auth::user();
        \Mail::raw('This is a test email from the enrollment system.', function ($message) use ($user) {
            $message->to('json.mamon.1990@gmail.com')
                ->subject('Enrollment Test Email');
        });
        return back()->with('success', 'Test email sent to ' . 'json.mamon.1990@gmail.com');
    }
}
