<x-app>
    <x-slot:title>
        Custom Title
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('../assets/img/img.jpg');"
    >

        <div class="bg-white bg-opacity-80 rounded-lg shadow-lg max-w-4xl w-full mx-4 flex flex-col md:flex-row overflow-hidden">
        
        <!-- Left panel -->
        <div class="md:w-1/2 p-10 flex flex-col justify-center items-start relative bg-blue-50">
        <!-- <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900 mb-3">Welcome to</h1> -->
        <h2 class="text-4xl md:text-5xl font-extrabold text-blue-700 mb-6 leading-tight">ENROLLMENT SYSTEM</h2>
        <!-- <p class="text-gray-700 mb-8 max-w-md">
            Efficiently manage research proposals, monitor statuses, assign advisers and panels, and explore AI-powered categorization — all in one place.
        </p> -->
        
        <!-- <div class="absolute right-8 bottom-6 w-32 h-32">
            <img src="../assets/img/logo.png" alt="School Logo" class="w-full h-full object-contain" />
        </div> -->
        </div>

        <!-- Right panel -->
        <div class="md:w-1/2 bg-white p-10 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-blue-900 mb-6 text-center">
                Log in to your account
            </h3>
            
            <form id="loginForm" action="/login" method="POST" class="space-y-6">
                <x-form.text name="email" label="Email" required />
                <x-form.password name="password" label="Password" required />

                <div>
                    <button
                        type="submit"
                        class="w-full bg-blue-700 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-800 transition duration-300"
                    >
                        Log In
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-gray-600 text-sm">
                Don’t have an account?
                <a href="/register" class="text-blue-700 hover:underline">Sign up</a>
            </p>
        </div>
    </div>

  </div>
</x-app>