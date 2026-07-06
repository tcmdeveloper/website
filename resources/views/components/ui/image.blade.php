@props([
    'image',
    'alt' => null,
    'loading' => 'lazy',
    'fetchpriority' => 'auto',
    'decoding' => 'async',
])

<img
    src="{{ $image->image_url }}"
    alt="{{ $alt ?? $image->alt_text }}"
    loading="{{ $loading }}"
    fetchpriority="{{ $fetchpriority }}"
    decoding="{{ $decoding }}"
    {{ $attributes->class([
        'rounded-xs object-cover',
    ]) }}
>