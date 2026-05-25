{{-- resources/views/components/ui/input.blade.php --}}

@props([
    'name',
    'type' => 'text',
    'value' => null,
    'label' => null,
])

<div class="form-group">

    @if($label)
        <label for="{{ $name }}" class="label">
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
                'w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm
                focus:border-olive-500 focus:ring-2 focus:ring-olive-500 focus:ring-offset-1 transition'
        ]) }}
    />

    @error($name)
        <p class="error-text">{{ $message }}</p>
    @enderror

</div>