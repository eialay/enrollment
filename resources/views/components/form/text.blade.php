@props([
    'name',
    'label' => '',
    'required' => false,
    'helpText' => '',
])
<div class="w-full mb-4">
    <label for="{{$name}}" class="block text-blue-900 font-semibold mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
    <input
        type="text"
        id="{{$name}}"
        name="{{$name}}"
        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    />
    <p class="mt-1 text-sm text-gray-500" id="{{$name}}_help">{{ $helpText ?? '' }}</p>
</div>