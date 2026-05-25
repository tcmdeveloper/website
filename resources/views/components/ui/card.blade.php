{{-- resources/views/components/ui/card.blade.php --}}

<div
    {{
        $attributes->merge([
            'class' => '
                bg-white
                border border-gray-200
                shadow-sm
                p-8
            '
        ])
    }}
>
    {{ $slot }}
</div>