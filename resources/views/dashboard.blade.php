<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    <div class="w-full mx-auto px-2 sm:px-4">
        @if(session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 sm:mb-6">Dashboard</h1>
        <p class="text-gray-700 mb-6 sm:mb-8">Welcome to the Enrollment System Dashboard!</p>

        @if (isset($cards))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
            @foreach ($cards as $card)
                <x-form.card 
                    :title="$card['title']" 
                    :value="$card['value']" 
                    :icon="$card['icon']" 
                    :color="$card['color']"
                    :change="isset($card['change']) ? $card['change'] : ''" 
                    :changeType="isset($card['changeType']) ? $card['changeType'] : ''" 
                    :comparisonText="isset($card['comparisonText']) ? $card['comparisonText'] : ''"
                    :link="isset($card['link']) ? $card['link'] : ''" />
            @endforeach
        </div>
        @endif
        
        @if(isset($student) && isset($student->enrollment))
            @if($student->enrollment->status === 'Pending Review')
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                <div class="font-bold mb-2">Next Steps</div>
                <ul class="list-disc pl-5">
                    <li>Your enrollment is currently <strong>Pending Review</strong>.</li>
                    <li>Head over to the Registrar's office, show your reference code and bring your physical documents for further review.</li>
                    <li>You will be notified once your status changes.</li>
                    <li>If you need to update your information, visit your profile page.</li>
                </ul>
            </div>
            @elseif($student->enrollment->status === 'Pending Payment')
                @if($student->payment && $student->payment->status === 'Unpaid')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                    <div class="font-bold mb-2">Next Steps</div>
                    <ul class="list-disc pl-5 mb-4">
                        <li>Your enrollment is currently <strong>Pending Payment</strong>.</li>
                        <li>Choose a payment method below to complete your enrollment.</li>
                        <li>You will be notified once your payment is confirmed.</li>
                    </ul>
                    <div class="flex gap-4">
                        <a href="{{ route('payments.show') }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Pay Online</a>
                            <a href="{{ route('payments.cashier') }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Pay at Cashier</a>
                    </div>
                </div>
                @elseif($student->payment && in_array($student->payment->status, ['Pending Approval', 'Partial']))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                    <div class="font-bold mb-2">Next Steps</div>
                    <ul class="list-disc pl-5">
                        <li>Your payment is currently <strong>{{ $student->payment->status }}</strong>.</li>
                        <li>Your payment reference code is <strong>{{ $student->payment->reference_code }}</strong>.</li>
                        <li>Please wait for the cashier to review and approve your payment.</li>
                        <li>You will be notified once your payment is approved.</li>
                    </ul>
                </div>
                @endif
            @elseif($student->enrollment->status === 'Enrolled')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <div class="font-bold mb-2">Congratulations!</div>
                <p>Your enrollment is <strong>Enrolled</strong>. Welcome aboard!</p>
                <div class="mt-4">
                    <a href="{{ route('students.show', $student->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">View Student Details</a>
                </div>
            </div>
            @endif
        @endif
    </div>
</x-sidebar>