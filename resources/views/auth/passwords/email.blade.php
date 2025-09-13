<x-app>
    <x-slot:title>
        Forgot Password
    </x-slot>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Forgot Password</h2>
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-800 transition duration-300">
                        Send Password Reset Link
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-blue-700 hover:underline text-sm">Back to Login</a>
            </div>
        </div>
    </div>
</x-app>
