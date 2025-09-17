<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Http\Controllers\EmailController;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student.user'])->orderByDesc('updated_at');
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('reference_code')) {
            $ref = $request->input('reference_code');
            $query->where('reference_code', 'like', "%$ref%");
        }
        $payments = $query->get();
        $statuses = Payment::select('status')->distinct()->pluck('status');
        return view('payments_list', [
            'payments' => $payments,
            'status' => $request->input('status'),
            'referenceCode' => $request->input('reference_code'),
            'statuses' => $statuses,
        ]);
    }

    public function show()
    {
        $student = Auth::user()->student;
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student record not found.');
        }
        $payment = $student->payment;
        if (!$payment) {
            return redirect()->route('dashboard')->with('error', 'Payment record not found.');
        }
        return view('payments_online', compact('student', 'payment'));
    }

    public function upload(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student || !$student->payment) {
            return redirect()->back()->with('error', 'No payment record found.');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'notes' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('receipt')->store('payment_receipts', 'public');
        $student->payment->receipt_path = $path;
        if ($request->filled('notes')) {
            $student->payment->remarks = $request->notes;
        }
        $student->payment->status = 'Pending Approval';
        $student->payment->save();

        return redirect()->route('payments.show')->with('success', 'Receipt uploaded. Please wait for approval.');
    }

    public function showDetails($id)
    {
        $payment = Payment::with(['student.user'])->findOrFail($id);
        return view('payment_details', compact('payment'));
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $payment = Payment::findOrFail($id);

        // Validate and apply amount paid
        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:1000',
        ]);
        $amountPaid = (float) $request->input('amount_paid');
        $remaining = (float) ($payment->balance - $payment->paid_amount);
        if ($amountPaid > $remaining + 1e-6) {
            return redirect()->back()->with('error', 'Amount paid exceeds remaining balance.');
        }

        $payment->paid_amount += $amountPaid;
        $payment->remarks = $request->input('remarks', $payment->remarks);
        $payment->reviewed_by = $user->id;
        $payment->reviewed_at = now();

        // Update status and enrollment based on remaining balance
        if ($payment->paid_amount >= $payment->balance) {
            $payment->status = 'Paid';
            if ($payment->student && $payment->student->enrollment) {
                $payment->student->enrollment->status = 'Enrolled';
                $payment->student->enrollment->save();

                // Notify student and guardian that enrollment is now Enrolled
                $student = $payment->student;
                $studentEmail = $student->user->email ?? null;
                $parentEmail = $student->guardianEmail ?? null;
                $enrollmentRef = $student->enrollment->reference_code ?? 'N/A';
                $subjectEnroll = 'Enrollment Status: Enrolled - ' . $enrollmentRef;
                $bodyEnroll = "Hello,\n\nCongratulations! Your enrollment has been confirmed.\n\nDetails:\n- Enrollment Reference: " . $enrollmentRef . "\n- Payment Reference: " . ($payment->reference_code ?? 'N/A') . "\n- Status: Enrolled\n\nWelcome aboard!";
                EmailController::sendEmail(
                    ['student' => $studentEmail, 'parent' => $parentEmail],
                    $subjectEnroll,
                    $bodyEnroll
                );
            }
        } else {
            $payment->status = 'Partial';
            // Keep enrollment pending until fully paid
        }

        $payment->save();

        return redirect()->back()->with('success', 'Payment approved and amount recorded.');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $payment = Payment::findOrFail($id);
        $payment->status = 'Rejected';
        $payment->remarks = $request->input('remarks', 'Rejected by cashier');
        $payment->save();

        // Notify student and guardian about rejection
        $studentEmail = optional($payment->student->user)->email;
        $parentEmail = $payment->student->guardianEmail ?? null;
        $subjectEnroll = 'Payment Update: Rejected - ' . ($payment->reference_code ?? 'No Ref');
        $bodyEnroll = "Hello,\n\nYour submitted payment/receipt was rejected.\n\nDetails:\n- Payment Reference: " . ($payment->reference_code ?? 'N/A') . "\n- Enrollment Reference: " . (optional($payment->student->enrollment)->reference_code ?? 'N/A') . "\n- Status: Rejected\n- Remarks: " . ($payment->remarks ?? 'N/A') . "\n\nPlease review and resubmit the correct proof of payment or contact the cashier.\n\nThank you.";
        EmailController::sendEmail(
            ['student' => $studentEmail, 'parent' => $parentEmail],
            $subjectEnroll,
            $bodyEnroll
        );

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
