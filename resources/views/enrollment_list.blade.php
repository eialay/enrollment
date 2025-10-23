<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    <div class="max-w-6xl mx-auto mt-12 px-6 font-sans">

        <!-- Header Card -->
        <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 text-white rounded-2xl shadow-lg p-8 mb-10 relative overflow-hidden animate-fadeIn">
            <div class="relative z-10">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">Enrollment Management Dashboard</h1>
                <p class="text-blue-100 text-sm sm:text-base">
                    Manage, monitor, and track student enrollment progress in one place.
                </p>
            </div>
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm rounded-2xl"></div>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="" class="flex flex-col sm:flex-row items-center justify-between bg-white rounded-xl shadow-md p-5 mb-8 border border-gray-100 animate-fadeIn">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <label for="status" class="text-sm font-medium text-gray-700">Status:</label>
                <select name="status" id="status" 
                    class="border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition hover:shadow"
                    onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" @if(isset($status) && $status == $s) selected @endif>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3 mt-4 sm:mt-0">
                <label for="reference_code" class="text-sm font-medium text-gray-700">Reference Code:</label>
                <input type="text" name="reference_code" id="reference_code" 
                    value="{{ request('reference_code') }}"
                    class="border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition hover:shadow"
                    placeholder="Search reference code..." />
            </div>
        </form>

        <!-- Students Table -->
        <div class="overflow-x-auto rounded-2xl shadow-lg border border-gray-100 bg-white animate-fadeIn">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Reference Code</th>
                        <th class="px-6 py-3 text-left font-semibold">Student ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Name</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-center font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($students->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-lg">No records found.</td>
                        </tr>
                    @else
                        @foreach($students as $student)
                            <tr class="hover:bg-blue-50 transition duration-300">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->enrollment->reference_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->formatted_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $student->lastname .', '. $student->firstname .' '. $student->middlename }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->enrollment)
                                        @php
                                            $status = $student->enrollment->status;
                                            $colorMap = [
                                                'Enrolled' => 'bg-green-100 text-green-800',
                                                'Pending Review' => 'bg-yellow-100 text-yellow-800',
                                                'Pending Payment' => 'bg-orange-100 text-orange-800',
                                                'Rejected' => 'bg-red-100 text-red-800'
                                            ];
                                            $color = $colorMap[$status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">{{ $status }}</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('students.show', $student->id) }}"
                                       class="inline-block px-4 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition transform hover:scale-105 shadow-sm">
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

    <!-- Animations -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</x-sidebar>
