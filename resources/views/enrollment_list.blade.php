<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>


<div class="max-w-5xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Enrollment - Students List</h2>

    <form method="GET" action="" class="mb-6 flex items-center gap-4">
        <label for="status" class="text-sm font-medium text-gray-700">Filter by Status:</label>
        <select name="status" id="status" class="border-gray-300 rounded px-3 py-2 pr-8 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
            <option value="">All</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" @if(isset($status) && $status == $s) selected @endif>{{ $s }}</option>
            @endforeach
        </select>
        <label for="reference_code" class="text-sm font-medium text-gray-700">Reference Code:</label>
        <input type="text" name="reference_code" id="reference_code" value="{{ request('reference_code') }}" class="border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search reference code..." />
    </form>

    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($students->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-lg">No records found.</td>
                    </tr>
                @else
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->enrollment->reference_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->formatted_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->lastname .', '. $student->firstname .' '. $student->middlename  }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($student->enrollment)
                                @php
                                    $status = $student->enrollment->status;
                                    $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold  bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ $status ?? '-' }}
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('students.show', $student->id) }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">View</a>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

</x-sidebar>