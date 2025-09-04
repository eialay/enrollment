<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>
    <p class="text-gray-700">Welcome to the Enrollment System Dashboard!</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="mt-4 px-4 py-2 bg-red-600 text-white rounded">Logout</button>
    </form>
</x-sidebar>