<x-sidebar>
    <x-slot:title>
        Online Payment
    </x-slot>

    <div class="w-full mx-auto px-2 sm:px-4 max-w-3xl">
        @if(session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 sm:mb-6">Pay Online</h1>
        <p class="text-gray-700 mb-6 sm:mb-8">Follow the instructions below to complete your online payment, then upload your payment receipt screenshot for verification.</p>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-3">Payment Details</h2>
            <ul class="text-sm text-gray-700 space-y-1">
                <li><span class="font-semibold">Payment Reference Code:</span> {{ $payment->reference_code ?? 'N/A' }}</li>
                <li><span class="font-semibold">Balance:</span> ₱{{ number_format($payment->balance - $payment->paid_amount, 2) }}</li>
                <li><span class="font-semibold">Status:</span>
                    @php
                        $status = $payment->status;
                        $color = config('enrollment.payment_status_colors')[$status] ?? 'gray';
                    @endphp
                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800">
                        {{ $status }}
                    </span>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-3">Instructions</h2>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <li>Send your payment to our official account:</li>
                <ul class="list-disc pl-6">
                    <li><span class="font-semibold">GCash:</span> 09xx-xxx-xxxx (Account Name: School Name)</li>
                    <li><span class="font-semibold">Bank:</span> 1234-5678-90 (Bank Name: Sample Bank, Account Name: School Name)</li>
                </ul>
                <li>Include your Reference Code in the payment notes.</li>
                <li>Take a clear screenshot/photo of the payment confirmation.</li>
                <li>Upload the receipt below and submit for verification.</li>
            </ol>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-3">Upload Receipt</h2>
            <form action="{{ route('payments.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-form.file name="receipt" label="Receipt Screenshot (JPEG/PNG/PDF)" helpText="" required />
                    @error('receipt')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add any details about your payment..."></textarea>
                </div>
                @if($payment->receipt_path)
                    <div class="text-sm text-gray-700">
                        <span class="font-semibold">Last uploaded receipt:</span>
                        <a href="{{ asset('storage/'.$payment->receipt_path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                    </div>
                @endif
                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Submit for Verification</button>
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
 
