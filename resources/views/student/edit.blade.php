<x-sidebar>
    <x-slot:title>
        Edit Student Details
    </x-slot>

    <form id="editStudentForm" action="{{ route('students.update', $student->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validatePasswordConfirmation();">
    @csrf
    @method('POST')
        <div class="h-full flex flex-col items-center justify-center">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-6">Edit Student Details</h2>
            <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg p-8">
                    <div class="md:flex gap-2">
                        <x-form.text name="firstname" label="First Name" required :value="old('firstname', $student->firstname)" />
                        <x-form.text name="middlename" label="Middle Name" required :value="old('middlename', $student->middlename)" />
                        <x-form.text name="lastname" label="Last Name" required :value="old('lastname', $student->lastname)" /> 
                    </div>
                    <div class="md:flex gap-2">
                        <x-form.select name="gender" label="Gender" required :options="['Male' => 'Male', 'Female' => 'Female']" :value="old('gender', $student->gender)" />
                        <x-form.date name="birthdate" label="Birthdate" required pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" :value="old('birthdate', $student->birthdate)" />
                    </div>
                    <div class="md:flex gap-2">
                        <x-form.select name="course" label="Course" required :options="['BSIT' => 'BS Information Technology', 'BSED' => 'BS Education', 'BSBA' => 'BS Business Administration']" :value="old('course', $student->enrollment->course ?? '')" />
                    </div>
                    <div class="md:flex gap-2">
                        <x-form.text name="contact" label="Contact Number" required maxLength="11" :value="old('contact', $student->contact)"/>
                        <x-form.text name="email" label="Email" required :value="old('email', $student->user->email)" />
                    </div>
                    <div class="md:flex gap-2">
                        <x-form.password name="password" label="Password (leave blank to keep current)" />
                        <x-form.password name="password_confirmation" label="Confirm Password" />
                    </div>
                    <div id="passwordError" class="text-red-600 text-sm text-center" style="display:none;"></div>
                    <x-form.text name="address" label="Address" required :value="old('address', $student->address)" />
                    
                    <h3 class="font-bold text-blue-900 mt-12 uppercase mb-6">Documents (leave blank to keep current)</h3>
                    <div class="md:flex gap-2">
                        <x-form.file name="studentImage" label="Student Image" helpText="Upload a recent photo" />
                        <x-form.file name="birthCertificate" label="Birth Certificate" helpText="PSA" />
                    </div>
                    <div class="md:flex gap-2">
                        <x-form.file name="form137" label="Form 137" helpText=""/>
                        <x-form.file name="goodMoral" label="Good Moral"/>
                        <x-form.file name="reportCard" label="Report Card"/>
                    </div>
                    
                    <h3 class="font-bold text-blue-900 mt-12 uppercase mb-6">Parent/Guardian</h3>
                    <div class="md:flex gap-2">
                        <x-form.text name="guardianFName" label="First Name" required :value="old('guardianFName', $student->guardianFName)" />
                        <x-form.text name="guardianMName" label="Middle Name" required :value="old('guardianMName', $student->guardianMName)" />
                        <x-form.text name="guardianLName" label="Last Name" required :value="old('guardianLName', $student->guardianLName)" /> 
                    </div>
                    <div class="md:flex gap-2">
                        <x-form.text name="guardianEmail" label="Email" required :value="old('guardianEmail', $student->guardianEmail)" />
                        <x-form.text name="guardianContact" label="Contact Number" required maxLength="11" :value="old('guardianContact', $student->guardianContact)"/>
                        <x-form.text name="guardianRelationship" label="Relationship" required :value="old('guardianRelationship', $student->guardianRelationship)" />
                    </div>
                    <x-form.text name="guardianAddress" label="Address" required :value="old('guardianAddress', $student->guardianAddress)" />
                </div>

                <div class="justify-center mt-8">
                    <div class="w-full max-w-xl bg-gray-50 rounded-lg shadow p-6">
                        <div class="flex gap-2">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Submit</button>
                            <a href="{{ route('students.show', $student->id) }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-700 hover:text-white transition">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        function validatePasswordConfirmation() {
            var password = document.querySelector('[name="password"]');
            var confirm = document.querySelector('[name="password_confirmation"]');
            var errorDiv = document.getElementById('passwordError');
            if (password && confirm && password.value !== confirm.value) {
                errorDiv.textContent = 'Passwords do not match.';
                errorDiv.style.display = 'block';
                confirm.focus();
                return false;
            } else {
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
                return true;
            }
        }
    </script>
</x-sidebar>
