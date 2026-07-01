{{-- components/ui/container.blade.php --}}

<div {{ $attributes->class([
    'mx-auto mb-12 px-3',
]) }}>
    {{ $slot }}
</div>