<x-app>
    <x-slot:title>
        Student Registration
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('../assets/img/img.jpg');"
    >

        <div class="md:w-3/4 my-8 bg-white p-10 flex flex-col justify-center">
            <h1 class="text-2xl font-bold text-blue-900 mb-6 text-center">
                Student Registration
            </h1>

            @if ($errors->any())
                <div class="mb-4">
                    <ul class="text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form id="registrationForm" action="{{ route('register.submit') }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validatePasswordConfirmation();">
                @csrf

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Enrollment Information</h3>
                <div class="md:flex gap-2">
                    <x-form.select name="admissionType" label="Admission Type" required :options="['Freshmen' => 'Freshmen', 'Transferee' => 'Transferee', 'Returnee' => 'Returnee']" />
                    <x-form.select name="course" label="Course" required :options="['BSIT' => 'BS Information Technology', 'BSED' => 'BS Education', 'BSBA' => 'BS Business Administration']" />
                    <x-form.select name="yearLevel" label="Year Level" required :options="['firstYear' => 'First Year', 'secondYear' => 'Second Year', 'thirdYear' => 'Third Year', 'fourthYear' => 'Fourth Year']" />
                </div>

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Primary Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="reportCard" label="Report Card" />
                    <x-form.file name="tor" label="Transcript of Records" />
                    <x-form.file name="studentImage" label="Passport Size ID Picture" helpText="(White Background, Formal Attire)" required />
                </div>

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Student Information</h3>
                <div class="md:flex gap-2">
                    <x-form.text name="firstname" label="First Name" required />
                    <x-form.text name="middlename" label="Middle Name" required />
                    <x-form.text name="lastname" label="Last Name" required /> 
                </div>

                <div class="md:flex gap-2">
                    <x-form.select name="gender" label="Gender" required :options="['Male' => 'Male', 'Female' => 'Female']" />
                    <x-form.date name="birthdate" label="Birthdate" required pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" :value="old('birthdate', \Carbon\Carbon::now()->subYears(16)->format('Y-m-d'))" />
                    <x-form.text name="contact" label="Contact Number" required maxLength="11"/>
                </div>

                <div class="md:flex gap-2">
                    <x-form.text name="email" label="Email" required />
                    <x-form.password name="password" label="Password" required />
                    <x-form.password name="password_confirmation" label="Confirm Password" required />
                </div>
                <div id="passwordError" class="text-red-600 text-sm text-center" style="display:none;"></div>

                <div class="md:flex gap-2">
                    <x-form.text name="address" label="Address" required />
                    <x-form.text name="baranggay" label="Baranggay" required />
                    <x-form.text name="city" label="Municipality/City" required />
                    <x-form.text name="province" label="Province" required />
                </div>


                <h3 class="font-bold text-blue-900 mt-12 uppercase">Secondary Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="form137" label="Form 137" />
                    <x-form.file name="honDismissal" label="Honorable Dismissal" />
                    <x-form.file name="goodMoral" label="Good Moral" />
                    <x-form.file name="birthCertificate" label="Birth Certificate (PSA)" />
                    <x-form.file name="brgyClearance" label="Baranggay Clearance" />
                </div>

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Parent/Guardian Information</h3>
                <div class="md:flex gap-2">
                    <x-form.text name="fatherFName" label="Father's First Name" required />
                    <x-form.text name="fatherMName" label="Father's Middle Name" required />
                    <x-form.text name="fatherLName" label="Father's Last Name" required /> 
                    <x-form.text name="fatherSuffix" label="Father's Suffix" />
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="motherFName" label="Mother's First Name" required />
                    <x-form.text name="motherMName" label="Mother's Middle Name" required />
                    <x-form.text name="motherLName" label="Mother's Last Name" required /> 
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="guardianFName" label="First Name" required />
                    <x-form.text name="guardianMName" label="Middle Name" required />
                    <x-form.text name="guardianLName" label="Last Name" required /> 
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="guardianEmail" label="Email" required />
                    <x-form.text name="guardianContact" label="Contact Number" required maxLength="11"/>
                    <x-form.text name="guardianRelationship" label="Relationship" required />
                </div>
                <x-form.text name="guardianAddress" label="Address" required />

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Educational Background</h3>
                <div class="md:flex gap-2">
                    <x-form.text name="primarySchool" label="Primary School" required />
                    <x-form.text name="primarySchoolYearGraduated" label="Year Graduated" required maxLength="4"/>
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="secondarySchool" label="Secondary School" required />
                    <x-form.text name="secondarySchoolYearGraduated" label="Year Graduated" required maxLength="4"/>
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="lastSchoolAttended" label="Last School Attended" required />
                    <x-form.text name="lastSchoolAttendedYearGraduated" label="Year Graduated" required maxLength="4"/>
                </div>
            
                <!-- Submit -->
                <div>
                <button
                    type="submit"
                    class="w-full bg-blue-700 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-800 transition duration-300"
                >
                    Submit
                </button>
                </div>
            </form>
            <script>
                function fillFieldsFromOcr(fields) {
                    if(fields.firstname) document.querySelector('[name="firstname"]').value = fields.firstname;
                    if(fields.middlename) document.querySelector('[name="middlename"]').value = fields.middlename;
                    if(fields.lastname) document.querySelector('[name="lastname"]').value = fields.lastname;
                    if(fields.birthdate) document.querySelector('[name="birthdate"]').value = fields.birthdate;
                }

                document.getElementById('birthCertificate').addEventListener('change', function() {
                    var fileInput = document.getElementById('birthCertificate');
                    var statusDiv = document.getElementById('birthCertificate_help');
                    if (!fileInput.files.length) {
                        statusDiv.textContent = 'Please select an image or PDF file.';
                        return;
                    }
                    var formData = new FormData();
                    formData.append('ocr_file', fileInput.files[0]);
                    statusDiv.textContent = 'Scanning...';
                    fetch("{{ route('ocr.scan') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.fields) {
                            fillFieldsFromOcr(data.fields);
                            statusDiv.textContent = 'Fields auto-filled. Please review and complete the form.';
                        } else {
                            statusDiv.textContent = 'Could not extract fields. Raw text: ' + (data.raw_text || '');
                        }
                    })
                    .catch(err => {
                        statusDiv.textContent = 'OCR failed: ' + err;
                    });
                });
            </script>
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

            <a href="/" class="text-blue-700 p-5 text-center hover:underline">Back to Login</a>
        </div>
    </div>

  </div>
</x-app>