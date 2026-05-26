{{-- resources/views/components/ui/checkbox.blade.php --}}

@props([
    'name',
    'value' => '1',
    'label' => null,
])


<label
    x-data="{ checked: @js(old($name) ? true : false) }"
    class="flex items-start gap-3 cursor-pointer select-none"
>

    <input
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        class="sr-only"
        x-model="checked"
    >

    <div
        class="
            mt-0.5
            flex items-center justify-center
            w-5 h-5
            rounded
            border border-stone-300
            transition-all
        "
        :class="checked
            ? 'bg-blue-500 border-blue-500'
            : 'bg-white border-stone-300'
        "
    >
        <x-heroicon-o-check
            class="w-4 h-4 text-white"
            x-show="checked"
            x-cloak
        />
    </div>

    <div class="text-sm text-stone-600 leading-6">
        {{ $slot ?? $label }}
    </div>

</label>



@error($name)
    <p class="mt-2 text-sm text-red-500">
        {{ $message }}
    </p>
@enderror