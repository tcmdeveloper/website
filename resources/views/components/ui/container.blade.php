{{-- components/ui/container.blade.php --}}
@props([
    'maxWidth' => 'max-w-5xl',
])

<div {{ $attributes->class([
    'mx-auto mb-12 pt-10 border-t border-zinc-300',
    $maxWidth,
]) }}>
    {{ $slot }}
</div>