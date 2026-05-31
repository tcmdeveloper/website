{{-- resources/views/components/ui/checkbox.blade.php --}}

@props([
    'name',
    'value' => '1',
    'label' => null,
])

<label class="flex items-start gap-3 cursor-pointer select-none">

    
    {{-- Custom checkbox UI --}}
    <div
        class="
            mt-0.5
            flex items-center justify-center
            w-5 h-5
            rounded
            border border-stone-300
            transition-all
        "
        :class="agreed
            ? 'bg-blue-500 border-blue-500'
            : 'bg-white border-stone-300'
        "
    >
        <x-heroicon-o-check
            class="w-4 h-4 text-white"
            x-show="agreed"
            x-cloak
        />

        {{-- Real checkbox --}}
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            class="sr-only"
            x-model="agreed"
        >
    </div>
    
    

    {{-- Label / slot content --}}
    <div class="leading-6">
        {{ trim($slot) ? $slot : $label }}
    </div>

</label>

