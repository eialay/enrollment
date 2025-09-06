<x-app>
    <x-slot:title>
        Student Registration
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('../assets/img/img.jpg');"
    >

        <div class="w-3/4 my-8 bg-white p-10 flex flex-col justify-center">
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
                    <x-form.date name="birthdate" label="Birthdate" required pattern="\\d{4}-\\d{2}-\\d{2}" placeholder="YYYY-MM-DD" />
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