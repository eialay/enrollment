<div class="w-full">
    <label for="{{$name}}" class="block text-blue-900 font-semibold mb-1">{{ $label }}</label>
    <input
        type="password"
        id="{{$name}}"
        name="{{$name}}"
        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        {{ $attributes }}
    />
</div>