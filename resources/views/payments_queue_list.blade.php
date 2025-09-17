<x-sidebar>
    <x-slot:title>
        Payment Queue
    </x-slot>

    <div class="max-w-5xl mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-6">Payment Queue List</h2>
        @if(session('success'))
            <div class="mb-6 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($queueList as $queue)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->queue_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->student->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->payment_reference_code ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-2">
                            @if($queue->payment)
                                <a href="{{ route('payments.details', $queue->payment->id) }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">View</a>
                            @endif
                            <form method="POST" action="{{ route('payments.queueDelete', $queue->id) }}" onsubmit="return confirm('Are you sure you want to delete this queue record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-lg">No queue items found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-sidebar>
