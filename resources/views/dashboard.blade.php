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
    </div>
</x-sidebar>