@props([
    'name',
    'options' => [],
    'value' => null,
    'label' => null,
    'placeholder' => null,
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

    <select
        x-ref="{{ $name }}"
        id="{{ $name }}"
        name="{{ $name }}"
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