<x-sidebar>
    <x-slot:title>
        Payments
    </x-slot>

    <div class="max-w-6xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                💰 Payments List
            </h2>
            <a href="{{ route('payments.queueList') }}" 
               class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow hover:shadow-lg transition duration-300 text-sm font-medium">
                View Queue
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 flex items-center gap-2 p-3 bg-green-50 text-green-800 border border-green-200 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 flex items-center gap-2 p-3 bg-red-50 text-red-800 border border-red-200 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filters -->
        <form method="GET" action="" class="mb-8 grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 bg-white/70 backdrop-blur-md p-5 rounded-xl shadow-md border border-gray-100">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status:</label>
                <select name="status" id="status"
                        class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition"
                        onchange="this.form.submit()">
                    <option value="">All</option>
                    @if(isset($statuses))
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @if(isset($status) && $status == $s) selected @endif>{{ $s }}</option>
                        @endforeach
                    @else
                        <option value="Paid" @if(request('status')==='Paid') selected @endif>Paid</option>
                        <option value="Unpaid" @if(request('status')==='Unpaid') selected @endif>Unpaid</option>
                        <option value="Partial" @if(request('status')==='Partial') selected @endif>Partial</option>
                    @endif
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-3">
                <label for="reference_code" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference Code:</label>
                <input type="text" name="reference_code" id="reference_code"
                       value="{{ request('reference_code') }}"
                       class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition"
                       placeholder="Search reference code..." />
            </div>
        </form>

        <!-- Payments Table -->
        <div class="overflow-hidden rounded-xl shadow-lg border border-gray-100 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if($payments->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-lg">No payments found.</td>
                        </tr>
                    @else
                        @foreach($payments as $payment)
                            <tr class="hover:bg-blue-50 transition duration-200">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $payment->reference_code ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->student->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">₱{{ number_format($payment->balance, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">₱{{ number_format($payment->paid_amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $payment->status;
                                        $color = config('enrollment.payment_status_colors')[$status] ?? 'gray';
                                    @endphp
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->updated_at }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('payments.details', $payment->id) }}"
                                       class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553 2.276a1 1 0 010 1.448L15 16m0-6V8a2 2 0 00-2-2H7v12h6a2 2 0 002-2v-2" />
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-sidebar>
