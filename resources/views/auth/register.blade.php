<x-app>
    <x-slot:title>
        Student Registration
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans animate-fadeIn"
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.94), rgba(8, 52, 117, 0.96)), url('../assets/img/img.jpg');"
    >
        <!-- Card Container -->
        <div class="md:w-3/4 lg:w-2/3 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-10 md:p-14 my-8 animate-slideUp border border-gray-200">

            <!-- Gradient Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-white bg-gradient-to-r from-blue-700 via-indigo-500 to-cyan-400 py-4 rounded-xl shadow-md animate-gradient">
                    Student Registration
                </h1>
                <p class="text-gray-600 mt-2">Fill out all fields accurately to begin your enrollment process.</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 animate-fadeInUp">
                    <ul class="text-red-600 text-sm bg-red-50 border border-red-300 p-4 rounded-lg shadow-sm">
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Registration Form -->
            <form id="registrationForm" 
                action="{{ route('register.submit') }}" 
                method="POST" 
                class="space-y-6 animate-fadeInUp"
                enctype="multipart/form-data"
                onsubmit="return validatePasswordConfirmation();"
            >
                @csrf

                <!-- Primary Documents -->
                <h3 class="font-bold text-blue-900 mt-12 uppercase border-l-4 border-blue-700 pl-3">Primary Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="birthCertificate" label="Birth Certificate (PSA)" required />
                    <x-form.file name="studentImage" label="ID Picture" helpText="Upload a recent photo" required />
                </div>

                <!-- Student Info -->
                <h3 class="font-bold text-blue-900 mt-12 uppercase border-l-4 border-blue-700 pl-3">Student Information</h3>
                <div class="md:flex gap-2">
                    <x-form.text name="firstname" label="First Name" required />
                    <x-form.text name="middlename" label="Middle Name" required />
                    <x-form.text name="lastname" label="Last Name" required /> 
                </div>

                <div class="md:flex gap-2">
                    <x-form.select name="gender" label="Gender" required :options="['Male' => 'Male', 'Female' => 'Female']" />
                    <x-form.date name="birthdate" label="Birthdate" required :value="old('birthdate', \Carbon\Carbon::now()->subYears(16)->format('Y-m-d'))" />
                </div>
                
                <div class="md:flex gap-2">
                    <x-form.select name="course" label="Course" required :options="['BSIT' => 'BS Information Technology', 'BSED' => 'BS Education', 'BSBA' => 'BS Business Administration']" />
                </div>

                <div class="md:flex gap-2">
                    <x-form.text name="contact" label="Contact Number" required maxLength="11"/>
                    <x-form.text name="email" label="Email" required />
                </div>

                <div class="md:flex gap-2">
                    <x-form.password name="password" label="Password" required />
                    <x-form.password name="password_confirmation" label="Confirm Password" required />
                </div>
                <div id="passwordError" class="text-red-600 text-sm text-center" style="display:none;"></div>

                <x-form.text name="address" label="Address" required />

                <!-- Secondary Documents -->
                <h3 class="font-bold text-blue-900 mt-12 uppercase border-l-4 border-blue-700 pl-3">Secondary Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="form137" label="Form 137" />
                    <x-form.file name="goodMoral" label="Good Moral" />
                    <x-form.file name="reportCard" label="Report Card" />
                </div>

                <!-- Guardian Info -->
                <h3 class="font-bold text-blue-900 mt-12 uppercase border-l-4 border-blue-700 pl-3">Parent/Guardian Information</h3>
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

                <!-- Submit Button -->
                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-700 via-indigo-500 to-cyan-400 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:opacity-90 hover:scale-105 transition-all duration-300"
                    >
                        Submit Registration
                    </button>
                </div>
            </form>

            <!-- Scripts -->
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
                    statusDiv.textContent = '🔍 Scanning...';
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
                            statusDiv.textContent = '✅ Fields auto-filled successfully.';
                        } else {
                            statusDiv.textContent = '⚠️ Could not extract fields. Please check file.';
                        }
                    })
                    .catch(err => {
                        statusDiv.textContent = '❌ OCR failed: ' + err;
                    });
                });

                function validatePasswordConfirmation() {
                    var password = document.querySelector('[name="password"]');
                    var confirm = document.querySelector('[name="password_confirmation"]');
                    var errorDiv = document.getElementById('passwordError');
                    if (password && confirm && password.value !== confirm.value) {
                        errorDiv.textContent = '⚠️ Passwords do not match.';
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

            <!-- Back to Login -->
            <div class="text-center mt-10">
                <a href="/" class="text-blue-700 hover:text-blue-900 hover:underline transition">← Back to Login</a>
            </div>
        </div>
    </div>

    <!-- Tailwind Animations -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        .animate-fadeIn { animation: fadeIn 0.8s ease-out; }
        .animate-fadeInUp { animation: slideUp 0.8s ease-out; }
        .animate-slideUp { animation: slideUp 1s ease-in-out; }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradientShift 3s linear infinite alternate;
        }
    </style>
</x-app>
