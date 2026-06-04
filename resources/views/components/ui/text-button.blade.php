{{-- resources/views/components/ui/text-button.blade.php --}}

@props([
    'type' => 'button',
])

<button
    type="{{ $type }}"
    {{
        $attributes->merge([
            'class' => '
                inline-flex items-center
                text-sm 
                text-zinc-500
                transition-colors duration-200
                cursor-pointer
                hover:text-yellow-500
                underline-offset-4
                focus:outline-none
                focus:ring-none
            '
        ])
    }}
>
    {{ $slot }}
</button>