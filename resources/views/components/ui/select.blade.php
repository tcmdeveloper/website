@props([
    'name',
    'options' => [],
    'label' => null,
    'value' => null,
    'placeholder' => null,
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

    $stateReadonly = "bg-zinc-50 text-zinc-700 cursor-default select-text border-zinc-200";

    $state = $disabled
        ? $stateDisabled
        : ($readonly
            ? $stateReadonly
            : $stateDefault
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

    <select
        x-ref="{{ $name }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @disabled($disabled)
        @readonly($readonly)
        {{ $attributes->merge([
            'class' => "{$base} {$sizeClasses} {$state}"
        ]) }}
    >

        @if($placeholder)
            <option value="">
                {{ $placeholder }}
            </option>
        @endif

        @foreach($options as $key => $labelOption)
            <option
                value="{{ $key }}"
                @selected(old($name, $value) == $key)
            >
                {{ $labelOption }}
            </option>
        @endforeach

    </select>

    {{-- Validation message if present for this field --}}
    @if ($errors->has($name))
        <p class="error-text">
            {{ $errors->first($name) }}
        </p>
    @endif

</div>