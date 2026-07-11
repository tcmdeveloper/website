@props([
    'image',
    'alt' => null,
    'loading' => 'lazy',
    'fetchpriority' => 'auto',
    'decoding' => 'async',
    'sizes' => '100vw',
])




@if ($image->hasAvailableFormats())

    <picture>

        {{-- AVIF --}}

        <source
            type="image/avif"
            srcset="
                {{ $image->url('160', 'avif') }} 160w,
                {{ $image->url('320', 'avif') }} 320w,
                {{ $image->url('480', 'avif') }} 480w,
                {{ $image->url('640', 'avif') }} 640w,
                {{ $image->url('800', 'avif') }} 800w,
                {{ $image->url('1200', 'avif') }} 1200w,
            "
            sizes="{{ $sizes }}"
        >

        {{-- WebP --}}

        <source
            type="image/webp"
            srcset="
                {{ $image->url('160', 'webp') }} 160w,
                {{ $image->url('320', 'webp') }} 320w,
                {{ $image->url('480', 'webp') }} 480w,
                {{ $image->url('640', 'webp') }} 640w,
                {{ $image->url('800', 'webp') }} 800w,
                {{ $image->url('1200', 'webp') }} 1200w,
            "
            sizes="{{ $sizes }}"
        >

        {{-- JPG fallback --}}

        <img
            src="{{ $image->url('800', 'jpg') }}"
            srcset="
                {{ $image->url('160', 'jpg') }} 160w,
                {{ $image->url('320', 'jpg') }} 320w,
                {{ $image->url('480', 'jpg') }} 480w,
                {{ $image->url('640', 'jpg') }} 640w,
                {{ $image->url('800', 'jpg') }} 800w,
                {{ $image->url('1200', 'jpg') }} 1200w,
            "
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

@else

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

@endif

