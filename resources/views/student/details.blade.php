<x-sidebar>
    <x-slot:title>
        Student Dashboard
    </x-slot>

    <div class="min-h-screen py-10 px-4 sm:px-8 bg-gradient-to-br from-blue-50 via-white to-blue-100 font-sans animate-fadeIn">

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white rounded-xl shadow-lg p-6 mb-8 flex flex-col sm:flex-row justify-between items-center animate-slideDown">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">Enrollment Management System</h1>
                <p class="text-blue-100 text-sm sm:text-base">Student Profile Overview & Enrollment Status</p>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="mt-4 sm:mt-0 bg-white text-blue-700 font-semibold px-5 py-2 rounded-lg shadow hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
               Back to Dashboard
            </a>
        </div>

        <!-- Alerts -->
        <div class="max-w-4xl mx-auto space-y-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-fadeIn" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-fadeIn" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Student Information -->
        <div class="max-w-5xl mx-auto mt-8 space-y-8">

            <!-- Student Card -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-8 transition transform hover:scale-[1.01] duration-300 animate-slideUp">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Profile Image -->
                    <div class="flex flex-col items-center">
                        <a href="{{ $studentImageUrl }}" target="_blank">
                            <img src="{{ $studentImageUrl }}" alt="Student Image" 
                                 class="w-28 h-28 object-cover rounded-full border-4 border-blue-200 shadow-md hover:shadow-lg transition">
                        </a>
                        <span class="mt-3 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-{{ $color ?? 'blue' }}-100 text-{{ $color ?? 'blue' }}-800">
                            {{ $student->enrollment->status }}
                        </span>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <h2 class="text-xl md:col-span-3 font-bold text-blue-900 mb-1">{{ $student->firstname }} {{ $student->middlename }} {{ $student->lastname }}</h2>
        
                        @if($student->studentID && $student->studentID->id_number)
                            <p class="text-sm text-gray-700 col-span-1">Student ID: <strong>{{ $student->studentID->id_number }}</strong></p>
                        @endif
                        <p class="text-sm text-gray-700 col-span-1">Admission: <strong>{{ $student->enrollment->admission_type ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-1">Course: <strong>{{ $student->enrollment->course ?? '-' }}</strong></p>                        
                        <p class="text-sm text-gray-700 col-span-1">Year Level: <strong>{{ $student->enrollment->year_level ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-1">School Year: <strong>{{ $student->enrollment->school_year ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-1">Birthdate: <strong>{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') : '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-1">Gender: <strong>{{ $student->gender ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-1">Email: <strong>{{ $student->user->email ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-1">Contact: <strong>{{ $student->contact ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700 col-span-3">Address: <strong>{{ $student->address ?? '' }}, {{ $student->baranggay }}, {{ $student->city }} , {{ $student->province }}</strong></p>

                        @if($student->enrollment->status === 'Rejected' && !empty($student->enrollment->remarks))
                            <div class="col-span-3 mt-2 text-sm text-red-700 font-medium bg-red-50 border-l-4 border-red-500 p-3 rounded-md">
                                Remarks: {{ $student->enrollment->remarks }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Uploaded Documents -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-8 animate-slideUp delay-150">
                <h3 class="text-xl font-bold text-blue-900 mb-4 flex items-center">
                    📁 Student Documents
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">                
                    @foreach($documents as $label => $file)
                        <div>
                            <span class="font-bold">{{ $label }}:</span>
                            @if(!empty($file))
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" 
                                   class="ml-2 inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">View</a>
                            @else
                                <span class="text-gray-500 ml-2">Not uploaded</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Parent / Guardian Details -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-8 animate-slideUp delay-300">
                <h3 class="text-xl font-bold text-blue-900 mb-4 flex items-center">
                    👨‍👩‍👧 Parent / Guardian Information
                </h3>
                <h4 class="text-sm font-semibold text-blue-800 mb-4">Parents' Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                    <p><span class="font-bold">Father's Name:</span> {{ $student->fatherFName ?? '-' }} {{ $student->fatherMName ?? '' }} {{ $student->fatherLName ?? '' }}</p>
                    <p><span class="font-bold">Mother's Name:</span> {{ $student->motherFName ?? '-' }} {{ $student->motherMName ?? '' }} {{ $student->motherLName ?? '' }}</p>
                </div>
                <hr class="my-6 border-gray-300">
                <h4 class="text-sm font-semibold text-blue-800 mb-4">Guardian Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                    <p><span class="font-bold">Name:</span> {{ $student->guardianFName ?? '-' }} {{ $student->guardianMName ?? '' }} {{ $student->guardianLName ?? '' }}</p>
                    <p><span class="font-bold">Email:</span> {{ $student->guardianEmail ?? '-' }}</p>
                    <p><span class="font-bold">Contact Number:</span> {{ $student->guardianContact ?? '-' }}</p>
                    <p><span class="font-bold">Relationship:</span> {{ $student->guardianRelationship ?? '-' }}</p>
                    <p class="sm:col-span-2"><span class="font-bold">Address:</span> {{ $student->guardianAddress ?? '-' }}</p>
                </div>
            </div>

            <!-- Admission / Student Buttons -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-8 text-center animate-fadeIn delay-500">
                @if(Auth::user()->role->name === 'Admission' && $student->enrollment->status === 'Pending Review')
                    <form method="POST" action="" class="flex flex-col items-center gap-4">
                        @csrf
                        <textarea name="remarks" id="remarks" rows="3" 
                                  class="w-full max-w-xl border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Enter remarks (optional)"></textarea>
                        <div class="flex flex-wrap justify-center gap-3">
                            <button type="submit" formaction="{{ route('students.approve', $student->id) }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Approve</button>
                            <button type="submit" formaction="{{ route('students.reject', $student->id) }}" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Reject</button>
                            <a href="{{ route('students.edit', $student->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Edit</a>
                            <a href="{{ route('enrollment.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-700 hover:text-white transition">Back to List</a>
                        </div>
                    </form>
                @elseif(Auth::user()->role->name === 'Student' && $student->id === Auth::user()->student->id)
                    <div class="flex flex-wrap justify-center gap-3">
                        @if(in_array($student->enrollment->status, ['Rejected', 'Pending Review']))
                            <a href="{{ route('students.edit', $student->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Edit</a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-700 hover:text-white transition">Back</a>
                    </div>
                @else
                    <a href="{{ route('enrollment.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-700 hover:text-white transition">Back to List</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Animations -->
    <style>
        @keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
        @keyframes slideUp { from {opacity:0; transform:translateY(40px);} to {opacity:1; transform:translateY(0);} }
        @keyframes slideDown { from {opacity:0; transform:translateY(-40px);} to {opacity:1; transform:translateY(0);} }
        .animate-fadeIn { animation: fadeIn 0.8s ease-out; }
        .animate-slideUp { animation: slideUp 0.9s ease-out; }
        .animate-slideDown { animation: slideDown 0.9s ease-out; }
        .delay-150 { animation-delay: 0.15s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>
</x-sidebar>
