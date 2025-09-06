<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    <div class="h-full flex flex-col items-center justify-center">
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
        <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Student Details</h2>
            <!-- Row 1: Student ID Card (Uniform Design) -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow p-6 mb-6">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink-0 flex flex-col items-center justify-center h-full mr-6">
                        @php
                            $studentImageUrl = $student->studentImage ? asset('storage/' . $student->studentImage) : 'https://placehold.co/120x120';
                        @endphp
                        <a href="{{ $studentImageUrl }}" target="_blank">
                            <img src="{{ $studentImageUrl }}" alt="Student Image" class="w-24 h-24 object-cover rounded-lg border-4 border-white shadow hover:opacity-90 transition">
                        </a>
                        <span class="mt-2 inline-block px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 align-middle">
                            {{ $student->enrollment->status }}
                        </span>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="font-bold text-lg text-blue-900 mb-1 col-span-2">{{ $student->firstname }} {{ $student->middlename }} {{ $student->lastname }}</div>
                        <div class="text-xs text-gray-600">Student ID: {{ $student->formatted_id }}</div>
                        <div class="text-xs text-gray-600">Birthdate: {{ $student->birthdate ?? '-' }}</div>
                        <div class="text-xs text-gray-600">Email: {{ $student->user->email ?? '-' }}</div>
                        <div class="text-xs text-gray-600">Contact: {{ $student->contact ?? '-' }}</div>
                        <div class="text-xs text-gray-600 col-span-2">Address: {{ $student->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Documents -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow p-6 mb-6">
                <h3 class="font-bold text-lg text-blue-900 mb-4">Documents</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-xs text-gray-600"><span class="font-bold">Birth Certificate:</span>
                        @if(!empty($student->birthCertificate))
                            <a href="{{ asset('storage/' . $student->birthCertificate) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                        @else
                            Not uploaded
                        @endif
                    </div>
                    <div class="text-xs text-gray-600"><span class="font-bold">Form 137:</span>
                        @if(!empty($student->form137))
                            <a href="{{ asset('storage/' . $student->form137) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                        @else
                            Not uploaded
                        @endif
                    </div>
                    <div class="text-xs text-gray-600"><span class="font-bold">Good Moral:</span>
                        @if(!empty($student->goodMoral))
                            <a href="{{ asset('storage/' . $student->goodMoral) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                        @else
                            Not uploaded
                        @endif
                    </div>
                    <div class="text-xs text-gray-600"><span class="font-bold">Report Card:</span>
                        @if(!empty($student->reportCard))
                            <a href="{{ asset('storage/' . $student->reportCard) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                        @else
                            Not uploaded
                        @endif
                    </div>
                </div>
            </div>

            <!-- Row 3: Parent/Guardian Details -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow p-6">
                <h3 class="font-bold text-lg text-blue-900 mb-4">Parent/Guardian</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-xs text-gray-600"><span class="font-bold">Name:</span> {{ $student->guardianFName ?? '-' }} {{ $student->guardianMName ?? '' }} {{ $student->guardianLName ?? '' }}</div>
                    <div class="text-xs text-gray-600"><span class="font-bold">Email:</span> {{ $student->guardianEmail ?? '-' }}</div>
                    <div class="text-xs text-gray-600"><span class="font-bold">Contact Number:</span> {{ $student->guardianContact ?? '-' }}</div>
                    <div class="text-xs text-gray-600"><span class="font-bold">Relationship:</span> {{ $student->guardianRelationship ?? '-' }}</div>
                    <div class="text-xs text-gray-600 md:col-span-2"><span class="font-bold">Address:</span> {{ $student->guardianAddress ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="justify-center mt-8">
            <div class="w-full max-w-xl bg-gray-50 rounded-lg shadow p-6">
                @if(Auth::user()->role->name === 'Registrar' && $student->enrollment->status === 'Pending Review')
                    <form method="POST" action="" class="flex flex-col items-end gap-4 mb-0">
                        @csrf
                        <div class="w-full">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="w-full border-gray-300 rounded px-3 py-2 mb-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter remarks (optional)"></textarea>
                        </div>
                        <div class="flex gap-2 mb-4">
                            <button type="submit" formaction="{{ route('students.approve', $student->id) }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Approve</button>
                            <button type="submit" formaction="{{ route('students.reject', $student->id) }}" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Reject</button>
                            <a href="{{ route('enrollment.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-700 hover:text-white transition">Back to List</a>
                        </div>
                    </form>
                @else
                    <a href="{{ route('enrollment.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-700 hover:text-white transition">Back to List</a>
                @endif
            </div>
        </div>
    </div>
    
</x-sidebar>