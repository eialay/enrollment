<x-sidebar>
    <x-slot:title>
        Dashboard
    </x-slot>

    @if(session('success'))
        <div class="w-full max-w-3xl mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>
    <p class="text-gray-700 mb-8">Welcome to the Enrollment System Dashboard!</p>
    
    @if (isset($cards))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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

    
</x-sidebar>