<x-sidebar>
    <x-slot:title>
        Payment Details
    </x-slot>

    <div class="text-xs max-w-2xl mx-auto mt-10">
        @if(session('success'))
            <div class="w-full max-w-3xl mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="w-full max-w-3xl mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        <h2 class="text-2xl font-bold mb-6">Payment Details</h2>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <span class="font-semibold">Payment Reference Code:</span>
                <span>{{ $payment->reference_code ?? 'N/A' }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Student Name:</span>
                <span>{{ $payment->student->user->name ?? 'N/A' }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Balance:</span>
                <span>{{ number_format($payment->balance, 2) }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Status:</span>
                @php
                    $status = $payment->status;
                    $color = config('enrollment.payment_status_colors')[$status] ?? 'gray';
                @endphp
                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800">
                    {{ $status }}
                </span>
            </div>
            @if(!empty($payment->receipt_path))
            <div class="mb-4">
                <span class="font-semibold">Receipt:</span>
                <a href="{{ asset('storage/'.$payment->receipt_path) }}" target="_blank" class="text-blue-600 hover:underline ml-1">View Receipt</a>
            </div>
            @endif
            <div class="mb-4">
                <span class="font-semibold">Remarks:</span>
                <span>{{ $payment->remarks ?? '-' }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Last Updated:</span>
                <span>{{ $payment->updated_at }}</span>
            </div>
        </div>
        <div class="justify-center mt-8">
            <div class="w-full max-w-xl bg-gray-50 rounded-lg shadow p-6">
                @if(auth()->user() && auth()->user()->role->name === 'Cashier' && $payment->status === 'Pending Approval')
                <form method="POST" action="" class="mb-4 flex flex-col gap-2">
                        @csrf
                        <div>
                            <label for="amount_paid" class="font-medium">Amount Paid:</label>
                            <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0.01" max="{{ max(0, $payment->balance - $payment->paid_amount) }}" class="border rounded px-2 py-1 w-full" placeholder="Enter amount received" required>
                            <div class="text-gray-500 text-xs mt-1">Remaining: ₱{{ number_format(max(0, $payment->balance - $payment->paid_amount), 2) }}</div>
                            @error('amount_paid')
                                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="remarks" class="font-medium">Remarks:</label>
                        <textarea name="remarks" id="remarks" rows="2" class="border rounded px-2 py-1">{{ old('remarks', $payment->remarks) }}</textarea>
                        <div class="flex gap-2 mt-2">
                            <button formaction="{{ route('payments.approve', $payment->id) }}" formmethod="POST" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Approve</button>
                            <button formaction="{{ route('payments.reject', $payment->id) }}" formmethod="POST" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Reject</button>
                            <a href="{{ route('payments.list') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Back to List</a>
                        </div>
                </form>
                @else 
                    <a href="{{ route('payments.list') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Back to List</a>
                @endif
            </div>
        </div>


       
    </div>
</x-sidebar>
