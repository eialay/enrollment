<x-app>
    <x-slot:title>
        Registration
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('../assets/img/img.jpg');"
    >

        <div class="w-3/4 mx-5 bg-white p-10 flex flex-col justify-center">
            <h1 class="text-2xl font-bold text-blue-900 mb-6 text-center">
                Registration
            </h1>
        
            <form action="/register" method="POST" class="space-y-6">
                <div class="md:flex gap-2">
                    <x-form.text name="firstname" label="First Name" required />
                    <x-form.text name="middlename" label="Middle Name" required />
                    <x-form.text name="lastname" label="Last Name" required /> 
                </div>

                <div class="md:flex gap-2">
                    <x-form.text name="email" label="Email" required />
                    <x-form.text name="contact" label="Contact Number" required />
                    <x-form.text name="birthdate" label="Birthdate" required />
                </div>

                <div class="md:flex gap-2">
                    <x-form.password name="password" label="Password" required />
                    <x-form.password name="confirm_password" label="Confirm Password" required />
                </div>

                <x-form.text name="address" label="Address" required />

                <h3 class="font-bold text-blue-900">Documents</h3>
                <div class="md:flex gap-2">
                    <x-form.file name="birthCertificate" label="Birth Certificate" helpText="PSA" />
                    <x-form.file name="form137" label="Form 137" helpText=""/>
                </div>
                <div class="md:flex gap-2">
                    <x-form.file name="goodMoral" label="Good Moral" />
                    <x-form.file name="reportCard" label="Report Card" />
                </div>


                <h3 class="font-bold text-blue-900">Parent/Guardian</h3>
                <div class="md:flex gap-2">
                    <x-form.text name="guardianFName" label="First Name" required />
                    <x-form.text name="guardianMName" label="Middle Name" required />
                    <x-form.text name="guardianLName" label="Last Name" required /> 
                </div>
                <div class="md:flex gap-2">
                    <x-form.text name="guardianEmail" label="Email" required />
                    <x-form.text name="guardianContact" label="Contact Number" required />
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

            <a href="/" class="text-blue-700 p-5 text-center hover:underline">Back to Login</a>
        </div>
    </div>

  </div>
</x-app>