<x-app>
    <x-slot:title>
        Login
    </x-slot>

    <div 
        class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center font-sans" 
        style="background-image: linear-gradient(rgba(250, 250, 250, 0.937), rgba(8, 52, 117, 0.942)), url('/img/img.jpg');"
    >

        <div class="bg-white bg-opacity-80 rounded-lg shadow-lg max-w-4xl w-full mx-4 flex flex-col md:flex-row overflow-hidden">
        
            <!-- Left Panel -->
            <div class="md:w-1/2 p-10 flex flex-col justify-center items-center text-center bg-gradient-to-br from-white via-blue-50 to-blue-100 text-gray-800 relative animate-fadeInLeft">
                <img src="/img/sms.png" alt="School Logo" class="w-32 h-32 mb-6 rounded-full shadow-lg border-4 border-white" />

                <h1 class="text-4xl md:text-5xl font-extrabold mb-3 tracking-tight text-blue-900">Welcome to</h1>
                <h2 class="text-5xl font-extrabold mb-4 text-blue-700 drop-shadow-md">SCHOOL MANAGEMENT SYSTEM</h2>
                <p class="text-gray-700 mb-8 max-w-md leading-relaxed font-medium">
                    Empowering education through a unified academic management system that enhances learning, streamlines processes, and connects the academic community.
                </p>
                
            </div>

            <!-- Right Panel -->
            <div class="md:w-1/2 bg-white p-10 flex flex-col justify-center animate-fadeInRight">
                <h2 class="text-3xl font-extrabold text-blue-800 text-center mb-2 tracking-tight">
                    Enrollment Management System
                </h2>
                <h3 class="text-2xl font-bold text-blue-900 mb-6 text-center">
                    Log in to your account
                </h3>
                
                <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="transform transition-all duration-700 ease-in-out animate-slideUp">
                        <x-form.text name="email" label="Email" required />
                    </div>
                    <div class="transform transition-all duration-700 ease-in-out delay-150 animate-slideUp">
                        <x-form.password name="password" label="Password" required />
                    </div>

                    @if ($errors->has('email'))
                        <div class="mb-4 text-red-600 text-sm animate-fadeIn">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    <div>
                        <button
                            type="submit"
                            class="w-full bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 transform hover:bg-blue-800 hover:scale-105 animate-bounceOnce"
                        >
                            Log In
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('password.request') }}" class="text-blue-700 hover:underline text-sm">Forgot Password?</a>
                </div>

                <p class="mt-6 text-center text-gray-600 text-sm">
                    New Student?
                    <a href="/register" class="text-blue-700 hover:underline">Register Here</a>
                    <span style="display: none;">test</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Animation Styles -->
    <style>
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes bounceOnce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-fadeInLeft { animation: fadeInLeft 0.8s ease-out; }
        .animate-fadeInRight { animation: fadeInRight 0.8s ease-out; }
        .animate-slideUp { animation: slideUp 0.9s ease-out; }
        .animate-fadeIn { animation: fadeIn 1s ease-in; }
        .animate-bounceOnce { animation: bounceOnce 1s ease-in-out; }
    </style>
</x-app>
