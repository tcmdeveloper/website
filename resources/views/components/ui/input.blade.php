{{-- resources/views/components/ui/input.blade.php --}}

@props([
    'name',
    'type' => 'text',
    'value' => null,
    'label' => null,
    'hideLabel' => false,
])


<div class="form-group">

    @if($label)
        <label 
            for="{{ $name }}" 
            class="{{ $hideLabel ? 'sr-only' : 'block mb-1 text-sm text-stone-500 font-medium' }}"
        >
            {{ $label }}
        </label>
    @endif

    
    <input
        x-ref="{{ $name }}"
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type ?? 'text' }}"
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


    {{-- Validation message if present for this field --}}
    @if ($errors->has($name))
        <p class="error-text">
            {{ $errors->first($name) }}
        </p>
    @endif

</div>

