@props([
    'name',
    'label' => '',
    'required' => false,
    'helpText' => '',
])
<div class="w-full mb-4">
    
    <label class="block text-blue-900 font-semibold mb-1" for="{{$name}}">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
    <input 
        type="file"
        id="{{$name}}"
        name="{{$name}}"
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" aria-describedby="{{$name}}_help"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    />
    <p class="mt-1 text-sm text-gray-500" id="{{$name}}_help">{{ $helpText ?? '' }}</p>

</div>