<a href="{{ isset($link) && $link ? $link : '#' }}" class="block group focus:outline-none">
<div class="card-hover bg-white rounded-xl p-6 border-l-4 border-{{ $color }}-500 relative overflow-hidden">
    <div class="absolute top-0 right-0 h-full w-16 bg-{{ $color }}-50 opacity-30"></div>
    <div class="relative">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $value }}</h3>
            </div>
            <div class="bg-{{ $color }}-100 text-{{ $color }}-800 p-3 rounded-lg">
                <i class="fas {{ $icon }}"></i>
            </div>
        </div>
        <div class="flex justify-between items-center">
            @if($change)
            <p class="text-sm text-{{$changeType == 'increase' ? 'green' : 'red'}}-500 font-medium">
                <i class="fas {{ $changeType == 'increase' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                {{ $change }}
            </p>
            <p class="text-sm text-gray-500">{{ $comparisonText }}</p>
            @endif
        </div>
    </div>
</div>
</a>