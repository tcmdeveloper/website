@props([
    'name',
    'type' => 'text',
    'value' => null,
    'label' => null,
    'hideLabel' => false,
    'size' => 'md',
    'disabled' => false,
    'readonly' => false,
])

@php

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-2 text-sm',
        'lg' => 'px-5 py-3 text-lg',
        default => 'px-4 py-2.5 text-base',
    };

    $base = "w-full rounded-sm border shadow-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-sky-300 transition";

    $stateDefault = "bg-white border-zinc-300 focus:ring-sky-300";

    $stateDisabled = "bg-zinc-100 text-zinc-500 cursor-not-allowed border-zinc-200";

    $stateReadonly = "bg-zinc-100 text-zinc-500 cursor-not-allowed select-text border-zinc-200";

    

    $error = $errors->has($name);
    $stateError = "bg-red-50 border-red-500 text-red-900 focus:ring-red-300";



    $state = $disabled
    ? $stateDisabled
    : ($error
        ? $stateError
        : ($readonly
            ? $stateReadonly
            : $stateDefault
        )
    );

@endphp

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
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @disabled($disabled)
        @readonly($readonly)
        {{ $attributes->merge([
            'class' => "{$base} {$sizeClasses} {$state}"
        ]) }}
    />

    {{-- Validation message --}}
    @if ($errors->has($name))
        <p class="mt-2 text-xs text-red-500">
            {{ $errors->first($name) }}
        </p>
    @endif

</div>