{{-- resources/views/components/ui/icon.blade.php --}}

@props(['name', 'size' => 'md'])

@php
    $sizeClasses = match ($size) {
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
        default => 'w-5 h-5',
    };
@endphp

<x-dynamic-component
    :component="'heroicon-o-' . $name"
    {{ $attributes->merge([
        'class' => $sizeClasses . ' stroke-2 inline-flex shrink-0'
    ]) }}
/>