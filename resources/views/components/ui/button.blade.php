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
        
        px-6 py-2
        border rounded
        shadow-sm
        font-medium
        cursor-pointer
        transition-all duration-300
        hover:opacity-90 hover:no-underline hover:translate-y-[1px] 
        focus:outline-none focus:ring-2 focus:ring-offset-2
        active:scale-[0.98]
    ';

    $variants = [
        'primary' => '
        mt-1
            gap-3
            bg-gradient-to-r
            from-blue-600 to-sky-500
            border-blue-500
            text-white
            focus:ring-blue-600
        ',

        'secondary' => '
        mt-1
            gap-3
            bg-gradient-to-r
            from-slate-600 to-gray-500
            border-slate-500
            text-white
            focus:ring-slate-600
        ',

        'danger' => '
        mt-1
            gap-3
            bg-gradient-to-r
            from-red-600 to-orange-500
            border-red-500
            text-white
            focus:ring-red-600
        ',

        'success' => '
        mt-1
            gap-3
            bg-gradient-to-r
            from-green-600 to-lime-500
            border-green-500
            text-white
            focus:ring-green-600
        ',

        'warning' => '
        mt-1
            gap-3
            bg-gradient-to-r
            from-indigo-600 to-blue-500
            border-indigo-500
            text-white
            focus:ring-indigo-600
        ',

        'ghost' => '
        mt-1
            gap-3
            bg-gradient-to-t
            from-gray-100 via-gray-50 to-white
            border-gray-300
            text-stone-700
            focus:ring-gray-400
            hover:from-yellow-50 to-white
        ',
    ];

    $sizes = [
        'xs' => 'px-2.5! py-1.5! text-xs',
        'sm' => 'px-3! py-1! text-sm',
        'md' => 'px-6! py-2! text-base',
        'lg' => 'px-9! py-3! text-lg',
        'xl' => 'px-12! py-4! text-xl',
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