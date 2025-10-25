<x-app>
    <x-slot:title>
        Student Registration
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('/img/img.jpg');"
    >

        <div class="md:w-3/4 my-8 bg-white p-10 flex flex-col justify-center shadow-[0px_0px_8px_0px_rgba(0,_0,_0,_0.4)]">
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
            
            <form id="registrationForm" action="{{ route('register.submit') }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validateLoginFields();">
                @csrf

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Enrollment Information</h3>
                <div class="md:flex gap-2">
                    <x-form.select name="admissionType" label="Admission Type" required nodefault :options="['Freshman' => 'Freshman', 'Transferee' => 'Transferee', 'Returnee' => 'Returnee']" />
                    <x-form.select name="course" label="Course" required :options="['BSIT' => 'BS Information Technology', 'BSED' => 'BS Education', 'BSBA' => 'BS Business Administration']" />
                    <x-form.select name="yearLevel" label="Year Level" required nodefault :options="['firstYear' => 'First Year', 'secondYear' => 'Second Year', 'thirdYear' => 'Third Year', 'fourthYear' => 'Fourth Year']" />
                </div>

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Primary Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="birthCertificate" label="Birth Certificate (PSA)" />
                    <x-form.file name="reportCard" label="Form 138 (Report Card)" />
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
                    <x-form.file name="goodMoral" label="Certificate of Good Moral" />                    
                    <x-form.file name="brgyClearance" label="Baranggay Clearance" />
                </div>

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Parent/Guardian Information</h3>
                <div class="md:flex gap-2">
                    <x-form.text name="fatherFName" label="Father's First Name" required />
                    <x-form.text name="fatherMName" label="Father's Middle Name" required />
                    <x-form.text name="fatherLName" label="Father's Last Name" required /> 
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="motherFName" label="Mother's First Name" required />
                    <x-form.text name="motherMName" label="Mother's Middle Name" required />
                    <x-form.text name="motherLName" label="Mother's Last Name" required /> 
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="guardianFName" label="Guardian First Name" required />
                    <x-form.text name="guardianMName" label="Guardian Middle Name" required />
                    <x-form.text name="guardianLName" label="Guardian Last Name" required /> 
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="guardianEmail" label="Guardian Email" required />
                    <x-form.text name="guardianContact" label="Guardian Contact Number" required maxLength="11"/>
                    <x-form.text name="guardianRelationship" label="Relationship to Student" required />
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
            <a href="/" class="text-blue-700 p-5 text-center hover:underline">Back to Login</a>
            
            <script>
                document.getElementById('tor').parentElement.style.display = 'none';
                document.getElementById('honDismissal').parentElement.style.display = 'none';
                var yearLevelSelect = document.getElementById('yearLevel');
                    
                // save original options once
                if (!yearLevelSelect.dataset.originalOptions) {
                    yearLevelSelect.dataset.originalOptions = yearLevelSelect.innerHTML;
                }
                var opt = document.createElement('option');
                opt.value = 'firstYear';
                opt.textContent = 'First Year';
                opt.selected = true;

                yearLevelSelect.value = 'firstYear';
                yearLevelSelect.innerHTML = '';
                yearLevelSelect.appendChild(opt);

                function fillFieldsFromOcr(elementID) {
                    var fileInput = document.getElementById(elementID);
                    var statusDiv = document.getElementById(elementID+'_help');
                    var firstNameInput = document.querySelector('[name="firstname"]');
                    var middleNameInput = document.querySelector('[name="middlename"]');
                    var lastNameInput = document.querySelector('[name="lastname"]');
                    var birthdateInput = document.querySelector('[name="birthdate"]');
                    var fields = {};

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
                            fields = data.fields;

                            if(fields.firstname && firstNameInput.value == "" ) firstNameInput.value = fields.firstname;
                            if(fields.middlename && middleNameInput.value == "") middleNameInput.value = fields.middlename;
                            if(fields.lastname && lastNameInput.value == "") lastNameInput.value = fields.lastname;
                            if(fields.birthdate) birthdateInput.value = fields.birthdate;

                            statusDiv.textContent = 'Fields auto-filled. Please review and complete the form.';
                        } else {
                            statusDiv.textContent = '';
                        }
                    })
                    .catch(err => {
                        statusDiv.textContent = '';
                    });
                }

                document.getElementById('birthCertificate').addEventListener('change', function() {
                    fillFieldsFromOcr('birthCertificate');
                });

                document.getElementById('reportCard').addEventListener('change', function() {
                    fillFieldsFromOcr('reportCard');
                });

                document.getElementById('admissionType').addEventListener('change', function() {
                    var admissionType = document.getElementById('admissionType').value;
                    var torInput = document.getElementById('tor');
                    var honDismissalInput = document.getElementById('honDismissal');
                    var form137Input = document.getElementById('form137');
                    var reportCardInput = document.getElementById('reportCard');                

                    if (admissionType === 'Freshman') {
                        form137Input.parentElement.style.display = 'block';
                        reportCardInput.parentElement.style.display = 'block';
                        torInput.parentElement.style.display = 'none';
                        torInput.value = '';
                        honDismissalInput.parentElement.style.display = 'none';
                        honDismissalInput.value = '';                        
                        
                        yearLevelSelect.innerHTML = '';
                        yearLevelSelect.appendChild(opt);
                    } else if (admissionType === 'Transferee') {
                        torInput.parentElement.style.display = 'block';
                        honDismissalInput.parentElement.style.display = 'block';
                        form137Input.parentElement.style.display = 'none';
                        form137Input.value = '';
                        reportCardInput.parentElement.style.display = 'none';
                        reportCardInput.value = '';

                        yearLevelSelect.innerHTML = yearLevelSelect.dataset.originalOptions;
                    }
                });

                document.querySelector('[name="email"]').addEventListener('blur', function() {
                    var emailInput = document.querySelector('[name="email"]');
                    var emailHelptext = document.getElementById('email_help');

                    // Clear previous messages
                    emailHelptext.textContent = '';
                    emailHelptext.style.color = '';

                    fetch(`{{ route('email.check') }}?email=${emailInput.value}`)
                    .then(response => response.json())
                    .then(response => {
                        if (response.exists) {
                            emailHelptext.textContent = 'This email is already in use.';
                            emailHelptext.style.color = 'crimson';
                        } else {
                            emailHelptext.textContent = '';
                            emailHelptext.style.color = '';
                        }
                    });
                });
            </script>
            <script>
                function validateLoginFields() {
                    var emailInput = document.querySelector('[name="email"]');
                    var emailHelptext = document.getElementById('email_help');

                    var password = document.querySelector('[name="password"]');
                    var confirm = document.querySelector('[name="password_confirmation"]');
                    var errorDiv = document.getElementById('passwordError');

                    if(emailInput.value == "") {
                        emailHelptext.textContent = 'Email is required.';
                        emailHelptext.style.color = 'crimson';
                        emailInput.focus();
                        return false;
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                        emailHelptext.textContent = 'Invalid email format.';
                        emailHelptext.style.color = 'crimson';
                        emailInput.focus();
                        return false;
                    } else if (emailHelptext.style.color === 'crimson') {
                        emailInput.focus();
                        return false;
                    } else {
                        emailHelptext.textContent = '';
                        emailHelptext.style.color = '';
                    }
                    
                    if (password && confirm && password.value !== confirm.value) {
                        errorDiv.textContent = 'Passwords do not match.';
                        errorDiv.style.display = 'block';
                        confirm.focus();
                        return false;
                    } else if (password.value.length < 8) {
                        errorDiv.textContent = 'Password must be at least 8 characters long.';
                        errorDiv.style.display = 'block';
                        password.focus();
                        return false;

                    } else if (password.value === "" || confirm.value === "") {
                        errorDiv.textContent = 'Password fields cannot be empty.';
                        errorDiv.style.display = 'block';
                        password.focus();
                        return false;
                    } else {
                        errorDiv.textContent = '';
                        errorDiv.style.display = 'none';
                        return true;
                    }
                }
            </script>
        </div>
    </div>

  </div>
</x-app>