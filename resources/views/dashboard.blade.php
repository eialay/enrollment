<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    <div class="w-full mx-auto px-2 sm:px-4 py-6 font-sans animate-fadeIn">

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white rounded-xl shadow-lg p-6 mb-8 flex flex-col sm:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">Enrollment Management Dashboard</h1>
                <p class="text-blue-100 text-sm sm:text-base">
                    Welcome to your student enrollment control center — manage, monitor, and track everything here.
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('logout') }}"
                    class="bg-white text-blue-800 font-semibold px-5 py-2 rounded-lg shadow hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                    Logout
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 animate-fadeIn">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Dashboard Intro -->
        <p class="text-gray-700 mb-6 sm:mb-8 text-center text-lg font-medium">
            Welcome to the <strong>Enrollment System Dashboard</strong>! Manage student records, monitor payment status, and stay updated with enrollment progress.
        </p>

        <!-- Statistic Cards -->
        @if (isset($cards))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach ($cards as $card)
                <div class="transform transition duration-500 hover:scale-105 animate-slideUp">
                    <x-form.card 
                        :title="$card['title']" 
                        :value="$card['value']" 
                        :icon="$card['icon']" 
                        :color="$card['color']"
                        :change="isset($card['change']) ? $card['change'] : ''" 
                        :changeType="isset($card['changeType']) ? $card['changeType'] : ''" 
                        :comparisonText="isset($card['comparisonText']) ? $card['comparisonText'] : ''"
                        :link="isset($card['link']) ? $card['link'] : ''" />
                </div>
            @endforeach
        </div>
        @endif

        <!-- Enrollment Status Panels -->
        @if(isset($student) && isset($student->enrollment))
            @if($student->enrollment->status === 'Pending Review')
                <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-6 mb-6 rounded-lg shadow animate-fadeIn">
                    <h3 class="font-bold text-lg mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 6a9 9 0 110 12 9 9 0 010-12z" />
                        </svg>
                        Next Steps
                    </h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Your enrollment is currently <strong>Pending Review</strong>.</li>
                        <li>Head to the Registrar’s office and show your reference code along with physical documents.</li>
                        <li>You will be notified once your status changes.</li>
                        <li>If needed, you may update your details in your profile.</li>
                    </ul>
                </div>

            @elseif($student->enrollment->status === 'Pending Payment')
                @if($student->payment && $student->payment->status === 'Unpaid')
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-6 mb-6 rounded-lg shadow animate-fadeIn">
                        <h3 class="font-bold text-lg mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m9 4A9 9 0 1112 3a9 9 0 0112 12z" />
                            </svg>
                            Next Steps
                        </h3>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li>Your enrollment is currently <strong>Pending Payment</strong>.</li>
                            <li>Choose a payment method below to complete your enrollment.</li>
                        </ul>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('payments.show') }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-all duration-300 transform hover:scale-105">Pay Online</a>
                            <a href="{{ route('payments.cashier') }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">Pay at Cashier</a>
                        </div>
                    </div>

                @elseif($student->payment && in_array($student->payment->status, ['Pending Approval', 'Partial']))
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-6 mb-6 rounded-lg shadow animate-fadeIn">
                        <h3 class="font-bold text-lg mb-3">Payment Review</h3>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Your payment is currently <strong>{{ $student->payment->status }}</strong>.</li>
                            <li>Reference Code: <strong>{{ $student->payment->reference_code }}</strong>.</li>
                            <li>Please wait for cashier review and approval.</li>
                        </ul>
                    </div>
                @endif

            @elseif($student->enrollment->status === 'Enrolled')
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-6 mb-6 rounded-lg shadow animate-fadeIn">
                    <h3 class="font-bold text-lg mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 5a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Congratulations!
                    </h3>
                    <p>Your enrollment is <strong>Enrolled</strong>. Welcome aboard!</p>
                    <div class="mt-4">
                        <a href="{{ route('students.show', $student->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">View Student Details</a>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Animations -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.8s ease-out; }
        .animate-slideUp { animation: slideUp 0.9s ease-out; }
    </style>
</x-sidebar>
