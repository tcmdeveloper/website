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
                text-sm font-medium
                text-gray-600
                transition-colors duration-200
                cursor-pointer
                hover:text-gray-900
                underline-offset-4
                focus:outline-none
                focus:ring-2
                focus:ring-olive-500
                rounded-sm
            '
        ])
    }}
>
    {{ $slot }}
</button>