{{-- resources/views/components/ui/divider.blade.php --}}

<div
    {{
        $attributes->merge([
            'class' => '
                border-t 
                border-stone-300
            '
        ])
    }}
    role="separator"
></div>