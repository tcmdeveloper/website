{{-- resources/views/components/ui/file.blade.php --}}

@props([
    'name',
    'label' => null,
    'accept' => null,
])

<div class="form-group">

    @if($label)
        <label
            for="{{ $name }}"
            class="block mb-1 text-sm text-stone-500 font-medium"
        >
            {{ $label }}
        </label>
    @endif

    <input
        x-ref="{{ $name }}"
        id="{{ $name }}"
        name="{{ $name }}"
        type="file"
        @if($accept) accept="{{ $accept }}" @endif
        {{ $attributes->merge([
            'class' =>
                'w-full 
                text-sm
                text-gray-900
                bg-white 
                border border-gray-300
                shadow-sm
                file:mr-4
                file:py-2.5
                file:px-4
                file:border-0
                file:bg-sky-50
                file:text-sky-700
                file:font-medium
                hover:file:bg-sky-100
                focus:outline-none
                focus:ring-1
                focus:ring-sky-300
                transition'
        ]) }}
    />

    {{-- Validation message --}}
    @if ($errors->has($name))
        <p class="error-text">
            {{ $errors->first($name) }}
        </p>
    @endif

</div>