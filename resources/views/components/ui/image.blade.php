@props([
    'image' => null,
    'alt' => null,
    'loading' => 'lazy',
    'fetchpriority' => 'auto',
    'decoding' => 'async',
    'sizes' => '100vw',
])

@php
    $responsiveSizes = $image->responsiveSizes();

    $avifSrcset = collect($responsiveSizes)
        ->map(
            fn (int $width) =>
                "{$image->url($width, 'avif')} {$width}w"
        )
        ->implode(', ');

    $webpSrcset = collect($responsiveSizes)
        ->map(
            fn (int $width) =>
                "{$image->url($width, 'webp')} {$width}w"
        )
        ->implode(', ');
@endphp

<picture>

    {{-- AVIF --}}

    <source
        type="image/avif"
        srcset="{{ $avifSrcset }}"
        sizes="{{ $sizes }}"
    >

    {{-- WebP fallback --}}

    <source
        type="image/webp"
        srcset="{{ $webpSrcset }}"
        sizes="{{ $sizes }}"
    >

    <img
        src="{{ $image->url(max($responsiveSizes), 'webp') }}"
        srcset="{{ $webpSrcset }}"
        sizes="{{ $sizes }}"
        alt="{{ $alt ?? $image->alt_text }}"
        loading="{{ $loading }}"
        fetchpriority="{{ $fetchpriority }}"
        decoding="{{ $decoding }}"
        {{ $attributes->class([
            'rounded-xs object-cover',
        ]) }}
    >

</picture>