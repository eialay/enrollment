<x-app>
    <x-slot:title>
        {{ $title }}
    </x-slot>

    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col ">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-blue-900 mb-6">Enrollment System</h2>
                <!-- Menu -->
                <nav class="flex-1">
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Main</p>
                        <a href="#" class="sidebar-item active flex items-center px-2 py-3 rounded-lg font-medium">
                            <i class="fas fa-tachometer-alt mr-3 text-indigo-600"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Management</p>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-user-graduate mr-3 w-5 text-center text-gray-500"></i>
                            <span>Enrollment</span>
                            <span class="ml-auto bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">24 New</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-clipboard-list mr-3 w-5 text-center text-gray-500"></i>
                            <span>Registrar (SIS)</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-book mr-3 w-5 text-center text-gray-500"></i>
                            <span>Curriculum</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-certificate mr-3 w-5 text-center text-gray-500"></i>
                            <span>Accreditation</span>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">3 Updates</span>
                        </a>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Operations</p>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-money-bill-wave mr-3 w-5 text-center text-gray-500"></i>
                            <span>Payments</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center text-gray-500"></i>
                            <span>Faculty</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-calendar-alt mr-3 w-5 text-center text-gray-500"></i>
                            <span>Class Scheduling</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-icons mr-3 w-5 text-center text-gray-500"></i>
                            <span>Co-curricular</span>
                        </a>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-1">Learning</p>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-laptop-code mr-3 w-5 text-center text-gray-500"></i>
                            <span>Online Learning</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-2 py-2 text-gray-700 rounded-lg mb-1">
                            <i class="fas fa-flask mr-3 w-5 text-center text-gray-500"></i>
                            <span>CRAD (Research)</span>
                            <span class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">5 New</span>
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
                        <p class="text-sm font-medium">Admin User</p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                    <button class="text-gray-400 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</x-app>