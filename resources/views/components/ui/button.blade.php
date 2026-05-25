{{-- resources/views/components/ui/button.blade.php --}}

@props([
    'variant' => 'primary',
    'size' => 'md',
    'full' => false,
    'type' => 'button',
    'href' => null,
])

@php

    $base = '
        inline-flex items-center justify-center
        mt-1
        px-6 py-2
        border rounded
        shadow-sm
        font-medium
        transition-all duration-300
        hover:opacity-90
        focus:outline-none focus:ring-2 focus:ring-offset-2
        active:scale-[0.98]
    ';

    $variants = [
        'primary' => '
            bg-gradient-to-r
            from-indigo-600 to-blue-500
            border-indigo-500
            text-white
            focus:ring-indigo-500
        ',

        'secondary' => '
            bg-gradient-to-r
            from-slate-600 to-gray-500
            border-slate-500
            text-white
            focus:ring-slate-500
        ',

        'danger' => '
            bg-gradient-to-r
            from-red-600 to-orange-500
            border-red-500
            text-white
            focus:ring-red-500
        ',

        'success' => '
            bg-gradient-to-r
            from-green-600 to-lime-500
            border-green-500
            text-white
            focus:ring-green-500
        ',

        'warning' => '
            bg-gradient-to-r
            from-indigo-600 to-blue-500
            border-indigo-500
            text-white
            focus:ring-indigo-500
        ',

        'ghost' => '
            bg-gradient-to-r
            from-indigo-600 to-blue-500
            border-indigo-500
            text-white
            focus:ring-indigo-500
        ',
    ];

    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-3 py-1 text-sm',
        'md' => 'px-6 py-2 text-base',
        'lg' => 'px-9 py-3 text-lg',
        'xl' => 'px-12 py-4 text-xl',
    ];

    $classes = implode(' ', [
        $base,
        $variants[$variant],
        $sizes[$size],
        $full ? 'w-full' : '',
    ]);

@endphp

@if($href)

    <a
        href="{{ $href }}"
        {{ $attributes->merge([
            'class' => $classes
        ]) }}
    >
        {{ $slot }}
    </a>

@else

    <button
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => $classes
        ]) }}
    >
        {{ $slot }}
    </button>

@endif