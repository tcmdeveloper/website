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
    $selectedValue = old($name, $value);

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
        : ($readonly ? $stateReadonly : $stateDefault);
@endphp


<div class="form-group">

    {{-- Label --}}
    @if($label)
        <label
            for="{{ $name }}"
            class="{{ $hideLabel ? 'sr-only' : 'block mb-1 text-sm text-stone-500 font-medium' }}"
        >
            {{ $label }}
        </label>
    @endif

    {{-- Select --}}
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @disabled($disabled)
        @readonly($readonly)
        {{ $attributes->merge([
            'class' => "{$base} {$sizeClasses} {$state}"
        ]) }}
    >

        {{-- Placeholder --}}
        @if($placeholder)
            <option value="" disabled selected hidden class="text-red-500">
                {{ $placeholder }}
            </option>
        @endif

        {{-- Options --}}
        @foreach($options as $key => $labelOption)
            <option
                value="{{ $key }}"
                @selected((string) $selectedValue === (string) $key)
            >
                {{ $labelOption }}
            </option>
        @endforeach

    </select>

    {{-- Error message --}}
    @error($name)
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>