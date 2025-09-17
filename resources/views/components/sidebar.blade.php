<x-app>
    <x-slot:title>
        {{ $title }}
    </x-slot>

    <div class="flex h-full bg-gray-100 min-h-screen">
        <!-- Mobile menu button -->
        <button id="sidebar-toggle" class="md:hidden fixed top-4 left-4 z-40 bg-white p-2 rounded shadow focus:outline-none">
            <svg class="h-6 w-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Sidebar -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-40 z-30 hidden md:hidden"></div>
        <aside id="sidebar" class="w-64 bg-white shadow-md flex flex-col justify-between h-screen fixed md:sticky top-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out md:relative md:flex md:translate-x-0">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-blue-900 mb-6">Enrollment System</h2>
                <!-- Menu -->
                <nav class="flex-1">
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Main</p>
                        <a href="/dashboard" class="sidebar-item flex items-center px-2 py-3 rounded-lg {{ request()->is('dashboard') ? 'active bg-indigo-100 text-indigo-700 font-bold' : 'text-gray-700' }}">
                            <i class="fas fa-tachometer-alt mr-3 {{ request()->is('dashboard') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                    @if(Auth::user()->role->name !== 'Student')   
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Management</p>
                        <a href="/enrollment" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('enrollment*') ? 'active bg-indigo-100 text-indigo-700 font-bold' : 'text-gray-700' }}">
                            <i class="fas fa-user-graduate mr-3 w-5 text-center {{ request()->is('enrollment*') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>Enrollment</span>
                        </a>
                        @if(Auth::user()->role->name === 'Admin')
                        <a href="{{ route('users.index') }}" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('users*') ? 'active bg-indigo-100 text-indigo-700 font-bold' : 'text-gray-700' }}">
                            <i class="fas fa-users-cog mr-3 w-5 text-center {{ request()->is('users*') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>User Management</span>
                        </a>
                        @endif
                    </div>
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Operations</p>
                        <a href="{{route('payments.list')}}" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('payments/list') ? 'active bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                            <i class="fas fa-money-bill-wave mr-3 w-5 text-center {{ request()->is('payments/list') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>Payments</span>
                        </a>
                        <a href="{{route('payments.queueList')}}" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('payments/queue-list') ? 'active bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                            <i class="fas fa-list-ol mr-3 w-5 text-center {{ request()->is('payments/queue-list') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>Payment Queue</span>
                        </a>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Account</p>
                        @if(Auth::user()->role->name === 'Student' && Auth::user()->student)
                            <a href="{{ route('students.show', Auth::user()->student->id) }}" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('student/' . Auth::user()->student->id) ? 'active bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                                <i class="fas fa-id-card mr-3 w-5 text-center {{ request()->is('student/' . Auth::user()->student->id) ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                                <span>My Student Info</span>
                            </a>
                        @else
                            <a href="#" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('profile') ? 'active bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                                <i class="fas fa-user mr-3 w-5 text-center {{ request()->is('profile') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                                <span>Profile</span>
                            </a>
                        @endif
                        <a href="/logout" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-sign-out-alt mr-3 w-5 text-center text-gray-500"></i>
                            <span>Log Out</span>
                        </a>
                    </div>
                </nav>
            </div>
            <!-- Fixed User Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative">
                        @php
                            $user = Auth::user();
                            $profileImage = '/img/default-dp.jpg';
                            if ($user->role->name === 'Student' && $user->student && $user->student->studentImage) {
                                $profileImage = asset('storage/' . $user->student->studentImage);
                            }
                        @endphp
                        <img src="{{ $profileImage }}" alt="Profile image" class="w-10 h-10 object-cover rounded-full mr-3 border-2 border-indigo-200">
                        <span class="absolute bottom-0 right-3 h-3 w-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role->name }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6 mt-16 md:mt-0 w-full">
            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('sidebar-toggle');
            if (toggle && sidebar && overlay) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
                overlay.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        });
    </script>
</x-app>