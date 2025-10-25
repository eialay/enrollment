@props([
    'name',
    'label' => '',
    'options' => [],
    'required' => false,
    'nodefault' => false,
    'disabled' => false,
    'helpText' => '',
    'value' => old($name),
])
<div class="w-full mb-4">
    <label for="{{ $name }}" class="block text-blue-900 font-semibold mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
    <select name="{{ $name }}" id="{{ $name }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" @if($required) required @endif {{ $disabled ? 'disabled' : '' }}>
        @if(!$nodefault)
            <option value="">Select...</option>
        @endif
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @if($helpText)
        <div class="text-xs text-gray-500 mt-1">{{ $helpText }}</div>
    @endif
    @error($name)
        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
    @enderror
</div>
