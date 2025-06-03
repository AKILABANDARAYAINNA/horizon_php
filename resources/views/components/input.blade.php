@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'required' => false,
    'label' => null,
    'placeholder' => '',
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">
            {{ $label }}
        </label>
    @endif

    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded px-4 py-2']) }}
    >
</div>
