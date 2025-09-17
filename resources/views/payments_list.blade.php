<x-sidebar>
    <x-slot:title>
        Payments
    </x-slot>

    <div class="max-w-5xl mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-6">Payments List</h2>
        @if(session('success'))
            <div class="mb-6 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <!-- Filters (match enrollments list style) -->
        <form method="GET" action="" class="mb-6 flex items-center gap-4">
            <label for="status" class="text-sm font-medium text-gray-700">Filter by Status:</label>
            <select name="status" id="status" class="border-gray-300 rounded px-3 py-2 pr-8 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
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
            <label for="reference_code" class="text-sm font-medium text-gray-700">Payment Reference Code:</label>
            <input type="text" name="reference_code" id="reference_code" value="{{ request('reference_code') }}" class="border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search reference code..." />
        </form>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($payments->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-lg">No payments found.</td>
                        </tr>
                    @else
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $payment->reference_code ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $payment->student->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($payment->balance, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($payment->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $status = $payment->status;
                                    $color = config('enrollment.payment_status_colors')[$status] ?? 'gray';
                                @endphp
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $payment->updated_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-2">
                                <a href="{{ route('payments.details', $payment->id) }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">View</a>                                
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-sidebar>
