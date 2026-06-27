{{-- components/ui/container.blade.php --}}
@props([
    'maxWidth' => 'max-w-5xl',
])

<div {{ $attributes->class([
    'mx-auto mb-12',
    $maxWidth,
]) }}>
    {{ $slot }}
</div>