{{-- resources/views/components/ui/category-pip.blade.php --}}

@props([
    'href' => null,
    'color' => 'gray',
])

@php
    $colors = [
        'gray' => 'bg-gray-300 text-gray-700 hover:bg-gray-200',
        'red' => 'bg-red-300 text-red-700 hover:bg-red-200',
        'sky' => 'bg-sky-500 text-white hover:bg-sky-200',
        'lime' => 'bg-lime-500 text-white hover:bg-lime-200',
        'yellow' => 'bg-yellow-300 text-yellow-700 hover:bg-yellow-200',
        'purple' => 'bg-purple-300 text-purple-700 hover:bg-purple-200',
    ];
@endphp

<{{ $href ? 'a' : 'span' }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'inline-flex items-center rounded-full px-3 py-1 text-sm font-normal transition',
        $colors[$color] ?? $colors['gray'],
    ]) }}
>
    {{ $slot }}
</{{ $href ? 'a' : 'span' }}>