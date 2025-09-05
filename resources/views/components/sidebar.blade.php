<x-app>
    <x-slot:title>
        {{ $title }}
    </x-slot>

    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-blue-900 mb-6">Enrollment System</h2>
                <!-- Menu -->
                <nav class="flex-1">
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Main</p>
                        <a href="/dashboard" class="sidebar-item flex items-center px-2 py-3 rounded-lg font-medium {{ request()->is('dashboard') ? 'active bg-indigo-100 text-indigo-700 font-bold' : 'text-gray-700' }}">
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
                            <!-- <span class="ml-auto bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">24 New</span> -->
                        </a>
                    </div>
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Operations</p>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('payments*') ? 'active bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                            <i class="fas fa-money-bill-wave mr-3 w-5 text-center {{ request()->is('payments*') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>Payments</span>
                        </a>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Account</p>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 rounded-lg mb-1 {{ request()->is('profile') ? 'active bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                            <i class="fas fa-user mr-3 w-5 text-center {{ request()->is('profile') ? 'text-indigo-700' : 'text-gray-500' }}"></i>
                            <span>Profile</span>
                        </a>
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
                        <img src="https://placehold.co/40x40" alt="Admin profile" class="rounded-full mr-3 border-2 border-indigo-200">
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
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</x-app>