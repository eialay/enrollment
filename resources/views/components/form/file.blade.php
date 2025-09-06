<div class="w-full mb-4">
    
    <label class="block text-blue-900 font-semibold mb-1" for="{{$name}}">{{ $label }}</label>
    <input 
        type="file"
        id="{{$name}}"
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" aria-describedby="{{$name}}_help"
        {{ $attributes }}
    />
    <p class="mt-1 text-sm text-gray-500" id="{{$name}}_help">{{ $helpText ?? '' }}</p>

</div>