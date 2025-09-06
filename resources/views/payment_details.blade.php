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
                <span class="font-semibold">Student ID:</span>
                <span>{{ $payment->student->formatted_id ?? $payment->student->id ?? 'N/A' }}</span>
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
                <span class="font-semibold">Paid Amount:</span>
                <span>{{ number_format($payment->paid_amount, 2) }}</span>
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
            <div class="mb-4">
                <span class="font-semibold">Remarks:</span>
                <span>{{ $payment->remarks ?? '-' }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Last Updated:</span>
                <span>{{ $payment->updated_at }}</span>
            </div>
        </div>
        <div class="mt-6">
            @if(auth()->user() && auth()->user()->role->name === 'Cashier' && $payment->status === 'Paid')
                    <form method="POST" action="" class="mb-4 flex flex-col gap-2">
                        @csrf
                        <label for="remarks" class="font-medium">Remarks:</label>
                        <textarea name="remarks" id="remarks" rows="2" class="border rounded px-2 py-1">{{ old('remarks', $payment->remarks) }}</textarea>
                        <div class="flex gap-2 mt-2">
                            <button formaction="{{ route('payments.approve', $payment->id) }}" formmethod="POST" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Approve</button>
                            <button formaction="{{ route('payments.reject', $payment->id) }}" formmethod="POST" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Reject</button>
                        </div>
                    </form>
            @endif
            <a href="{{ route('payments.list') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Back to List</a>
        </div>
    </div>
</x-sidebar>
