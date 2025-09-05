<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    <div class="h-full flex items-center justify-center">
        <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Student Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Personal Information</h3>
                    <div class="mb-2"><span class="font-medium">Student ID:</span> {{ $student->formatted_id }}</div>
                    <div class="mb-2"><span class="font-medium">First Name:</span> {{ $student->firstname ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Middle Name:</span> {{ $student->middlename ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Last Name:</span> {{ $student->lastname ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Email:</span> {{ $student->email ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Contact Number:</span> {{ $student->contact ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Birthdate:</span> {{ $student->birthdate ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Address:</span> {{ $student->address ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Status:</span> 
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ $student->enrollment->status }}
                        </span>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Parent/Guardian</h3>
                    <div class="mb-2"><span class="font-medium">First Name:</span> {{ $student->guardianFName ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Middle Name:</span> {{ $student->guardianMName ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Last Name:</span> {{ $student->guardianLName ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Email:</span> {{ $student->guardianEmail ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Contact Number:</span> {{ $student->guardianContact ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Relationship:</span> {{ $student->guardianRelationship ?? '-' }}</div>
                    <div class="mb-2"><span class="font-medium">Address:</span> {{ $student->guardianAddress ?? '-' }}</div>
                </div>
            </div>

            <h3 class="font-semibold text-blue-800 mt-8 mb-2">Documents</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <span class="font-medium">Birth Certificate:</span>
                    @if(!empty($student->birthCertificate))
                        <a href="{{ asset('storage/' . $student->birthCertificate) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                    @else
                        Not uploaded
                    @endif
                </div>
                <div>
                    <span class="font-medium">Form 137:</span>
                    @if(!empty($student->form137))
                        <a href="{{ asset('storage/' . $student->form137) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                    @else
                        Not uploaded
                    @endif
                </div>
                <div>
                    <span class="font-medium">Good Moral:</span>
                    @if(!empty($student->goodMoral))
                        <a href="{{ asset('storage/' . $student->goodMoral) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                    @else
                        Not uploaded
                    @endif
                </div>
                <div>
                    <span class="font-medium">Report Card:</span>
                    @if(!empty($student->reportCard))
                        <a href="{{ asset('storage/' . $student->reportCard) }}" target="_blank" class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                    @else
                        Not uploaded
                    @endif
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <div class="w-full max-w-xl bg-gray-50 rounded-lg shadow p-6">
                    @if(Auth::user()->role->name === 'Registrar' && $student->status === 'Pending Review')
                        <form method="POST" action="{{ route('students.approve', $student->id) }}" class="mb-4">
                            @csrf
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="w-full border-gray-300 rounded px-3 py-2 mb-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter remarks (optional)"></textarea>
                            <div class="flex gap-4">
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('students.reject', $student->id) }}">
                            @csrf
                            <input type="hidden" name="remarks" value="" />
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Reject</button>
                        </form>
                            </div>
                    @endif
                    <div class="mt-4 flex justify-center">
                        <a href="{{ route('enrollment.index') }}" class="inline-block px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar>