<x-sidebar>
    <x-slot:title>
        Payments
    </x-slot>

    <div class="h-full flex flex-col items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8 mt-8">
            <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Settle Your Balance</h2>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @php
                $payment = Auth::user()->student ? Auth::user()->student->payment : null;
            @endphp
            @if($payment)
                <div class="mb-6">
                    <div class="text-lg font-semibold text-blue-800 mb-2">Current Balance: <span class="text-red-600">₱{{ number_format($payment->balance - $payment->paid_amount, 2) }}</span></div>
                    <div class="text-sm text-gray-600 mb-1">Status: <span class="font-bold">{{ $payment->status }}</span></div>
                </div>
                @if($payment->balance - $payment->paid_amount > 0)
                <form method="POST" action="{{ route('payments.settle') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount to Pay</label>
                        <input type="number" name="amount" id="amount" min="1" max="{{ $payment->balance - $payment->paid_amount }}" step="0.01" class="w-full border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-800 transition duration-300">Settle Payment</button>
                </form>
                @else
                <div class="text-green-700 font-bold">Your balance is fully paid.</div>
                @endif
            @else
                <div class="text-red-600 font-bold">No payment record found.</div>
            @endif
        </div>
    </div>
</x-sidebar>
