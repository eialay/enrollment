<x-sidebar>
    <x-slot:title>
        Enrollment Queue
    </x-slot>

    <div class="max-w-6xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Enrollment Queue</h2>
            <a href="{{ route('payments.list') }}" 
               class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow hover:shadow-lg transition duration-300 text-sm font-medium">
                Back to Enrollments
            </a>
        </div>

        {{-- Alert Messages --}}
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

        {{-- Table Container --}}
        <div class="overflow-hidden rounded-xl shadow-lg border border-gray-100 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Queue Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($queueList as $queue)
                        <tr class="hover:bg-blue-50 transition duration-200">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $queue->queue_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $queue->enrollment->student->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $queue->enrollment_code ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    @if($queue->status === 'Pending') bg-yellow-100 text-yellow-700 
                                    @elseif($queue->status === 'Approved') bg-green-100 text-green-700 
                                    @elseif($queue->status === 'Rejected') bg-red-100 text-red-700 
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $queue->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <a href="{{ route('students.show', $queue->enrollment->student->id) }}" 
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553 2.276a1 1 0 010 1.448L15 16m0-6V8a2 2 0 00-2-2H7v12h6a2 2 0 002-2v-2" />
                                    </svg>
                                    View
                                </a>
                                <form method="POST" action="{{ route('enrollment.queueDelete', $queue->id) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this queue record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow hover:bg-red-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-lg">No queue items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-sidebar>
