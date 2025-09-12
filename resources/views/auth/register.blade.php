<x-app>
    <x-slot:title>
        Student Registration
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('../assets/img/img.jpg');"
    >

        <div class="w-3/4 my-8 bg-white p-10 flex flex-col justify-center">
            <!-- OCR Upload and Scan -->
            <div class="mb-6 p-4 border border-blue-200 rounded bg-blue-50">
                <label class="block font-semibold text-blue-900 mb-2">Scan ID/Document for Auto-Fill (Image or PDF)</label>
                <input type="file" id="ocrFileInput" accept=".jpg,.jpeg,.png,.pdf" class="mb-2" />
                <button type="button" id="scanOcrBtn" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Scan & Auto-Fill</button>
                <div id="ocrStatus" class="text-sm text-blue-700 mt-2"></div>
            </div>
            <h1 class="text-2xl font-bold text-blue-900 mb-6 text-center">
                Student Registration
            </h1>
        
            <form id="registrationForm" action="{{ route('register.submit') }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validatePasswordConfirmation();">
                @csrf
                <div class="md:flex gap-2">
                    <x-form.text name="firstname" label="First Name" required />
                    <x-form.text name="middlename" label="Middle Name" required />
                    <x-form.text name="lastname" label="Last Name" required /> 
                </div>

                <div class="md:flex gap-2">
                    <x-form.text name="email" label="Email" required />
                    <x-form.text name="contact" label="Contact Number" required maxLength="11"/>
                    <x-form.date name="birthdate" label="Birthdate" required pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" :value="old('birthdate', \Carbon\Carbon::now()->subYears(16)->format('Y-m-d'))" />
                </div>

                <div class="md:flex gap-2">
                    <x-form.password name="password" label="Password" required />
                    <x-form.password name="password_confirmation" label="Confirm Password" required />
                </div>
                <div id="passwordError" class="text-red-600 text-sm text-center" style="display:none;"></div>

                <x-form.text name="address" label="Address" required />

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="studentImage" label="Student Image" helpText="Upload a recent photo" required />
                    <x-form.file name="birthCertificate" label="Birth Certificate" helpText="PSA" required />
                    <x-form.file name="form137" label="Form 137" helpText=""/>
                    <x-form.file name="goodMoral" label="Good Moral" />
                    <x-form.file name="reportCard" label="Report Card" />
                </div>

                <h3 class="font-bold text-blue-900 mt-12 uppercase">Parent/Guardian</h3>
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
                if(fields.email) document.querySelector('[name="email"]').value = fields.email;
                if(fields.contact) document.querySelector('[name="contact"]').value = fields.contact;
                // Add more as needed
            }

            document.getElementById('scanOcrBtn').addEventListener('click', function() {
                var fileInput = document.getElementById('ocrFileInput');
                var statusDiv = document.getElementById('ocrStatus');
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