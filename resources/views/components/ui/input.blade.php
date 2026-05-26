{{-- resources/views/components/ui/input.blade.php --}}

@props([
    'name',
    'type' => 'text',
    'value' => null,
    'label' => null,
])

<div class="form-group">

    @if($label)
        <label for="{{ $name }}" class="block text-sm mb-1">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' =>
                'w-full 
                px-4 py-2.5
                bg-white 
                border border-gray-300
                shadow-sm
                text-gray-900 
                focus:outline-none
                focus:ring-1
                focus:ring-sky-300
                focus:ring-offset-0
                focus:ring-offset-white
                transition'
        ]) }}
    />

    @error($name)
        <p class="error-text">{{ $message }}</p>
    @enderror

</div>