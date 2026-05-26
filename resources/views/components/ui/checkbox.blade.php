{{-- resources/views/components/ui/checkbox.blade.php --}}

@props([
    'name',
    'value' => '1',
    'label' => null,
])


{{-- Root checkbox component with Alpine state --}}

<label
    x-data="{ checked: @js((bool) old($name, false)) }"
    class="flex items-start gap-3 cursor-pointer select-none"
>

    {{-- Hidden native checkbox (handles form submission) --}}

    <input
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        class="sr-only"
        x-model="checked"
    >

    
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
        :class="checked
            ? 'bg-blue-500 border-blue-500'
            : 'bg-white border-stone-300'
        "
    >
        
        {{-- Check icon (only visible when checked) --}}

        <x-heroicon-o-check
            class="w-4 h-4 text-white"
            x-show="checked"
            x-cloak
        />
        
    </div>

    
    {{-- Label / slot content --}}

    <div class="text-sm text-stone-600 leading-6">
        {{ $slot ?? $label }}
    </div>


</label>


{{-- Validation error message --}}

@error($name)
    <p class="mt-2 text-sm text-red-500">
        {{ $message }}  
    </p>
@enderror