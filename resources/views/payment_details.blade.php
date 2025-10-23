<x-sidebar>
    <x-slot:title>
        💳 Payment Details
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 px-4 font-sans animate-fadeIn">
        
        {{-- SUCCESS & ERROR ALERTS --}}
        @if(session('success'))
            <div class="mb-4 animate-fadeInUp">
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 animate-fadeInUp">
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- HEADER --}}
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center animate-fadeInUp">Payment Details</h2>

        {{-- PAYMENT CARD --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-[1.01] animate-fadeInUp">
            <div class="space-y-3 text-gray-700">
                <div><span class="font-semibold">Payment Reference Code:</span> {{ $payment->reference_code ?? 'N/A' }}</div>

                @if($queue)
                    <div><span class="font-semibold">Queue Number:</span> {{ $queue->queue_number }}</div>
                @endif

                <div><span class="font-semibold">Student Name:</span> {{ $payment->student->user->name ?? 'N/A' }}</div>
                <div><span class="font-semibold">Balance:</span> ₱{{ number_format($payment->balance, 2) }}</div>

                {{-- STATUS BADGE --}}
                <div>
                    <span class="font-semibold">Status:</span>
                    @php
                        $status = $payment->status;
                        $color = config('enrollment.payment_status_colors')[$status] ?? 'gray';
                    @endphp
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 animate-pulse">
                        <span class="w-2 h-2 bg-{{ $color }}-500 rounded-full"></span>
                        {{ $status }}
                    </span>
                </div>

                {{-- RECEIPT LINK --}}
                @if(!empty($payment->receipt_path))
                    <div>
                        <span class="font-semibold">Receipt:</span>
                        <a href="{{ asset('storage/'.$payment->receipt_path) }}" target="_blank"
                            class="text-blue-600 hover:underline ml-1 hover:text-blue-800 transition">
                            View Receipt
                        </a>
                    </div>
                @endif

                <div><span class="font-semibold">Remarks:</span> {{ $payment->remarks ?? '-' }}</div>
                <div><span class="font-semibold">Last Updated:</span> {{ $payment->updated_at }}</div>
            </div>
        </div>

        {{-- CASHIER ACTIONS --}}
        <div class="mt-10">
            <div class="w-full bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl shadow-lg p-6 animate-fadeInUp">

                @if(auth()->user() && auth()->user()->role->name === 'Cashier' && ($payment->status === 'Pending Approval' || $queue))
                    <form method="POST" action="" class="flex flex-col gap-4">
                        @csrf

                        {{-- AMOUNT INPUT --}}
                        <div>
                            <label for="amount_paid" class="font-semibold text-gray-700">Amount Paid:</label>
                            <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0.01" 
                                max="{{ max(0, $payment->balance - $payment->paid_amount) }}"
                                class="border border-gray-300 focus:ring-2 focus:ring-blue-500 rounded-lg px-3 py-2 w-full mt-1 outline-none transition duration-200"
                                placeholder="Enter amount received" required>
                            <div class="text-gray-500 text-xs mt-1">
                                Remaining: ₱{{ number_format(max(0, $payment->balance - $payment->paid_amount), 2) }}
                            </div>
                            @error('amount_paid')
                                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- REMARKS --}}
                        <div>
                            <label for="remarks" class="font-semibold text-gray-700">Remarks:</label>
                            <textarea name="remarks" id="remarks" rows="2" 
                                class="border border-gray-300 focus:ring-2 focus:ring-blue-500 rounded-lg px-3 py-2 w-full mt-1 outline-none transition duration-200">{{ old('remarks', $payment->remarks) }}</textarea>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex flex-wrap gap-3 justify-center mt-4">
                            <button formaction="{{ route('payments.approve', $payment->id) }}" 
                                formmethod="POST" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow hover:shadow-md transition-transform transform hover:scale-105">
                                Approve
                            </button>

                            <button formaction="{{ route('payments.reject', $payment->id) }}" 
                                formmethod="POST" 
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow hover:shadow-md transition-transform transform hover:scale-105">
                                Reject
                            </button>

                            <a href="{{ route('payments.list') }}" 
                                class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow hover:shadow-md transition-transform transform hover:scale-105">
                                Back to List
                            </a>
                        </div>
                    </form>
                @else
                    <div class="flex justify-center">
                        <a href="{{ route('payments.list') }}" 
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow hover:shadow-md transition-transform transform hover:scale-105">
                            Back to List
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-sidebar>
