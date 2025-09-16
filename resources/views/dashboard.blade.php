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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
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
        
        @if(isset($student) && isset($student->enrollment) && $student->enrollment->status === 'Pending Review')
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                <div class="font-bold mb-2">Next Steps</div>
                <ul class="list-disc pl-5">
                    <li>Your enrollment is currently <strong>Pending Review</strong>.</li>
                    <li>Head over to the Registrar's office, show your reference code and bring your physical documents for further review.</li>
                    <li>You will be notified once your status changes.</li>
                    <li>If you need to update your information, visit your profile page.</li>
                </ul>
            </div>
        @endif
    </div>
</x-sidebar>