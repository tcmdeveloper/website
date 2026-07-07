{{-- resources/views/components/ui/breadcrumbs.blade.php --}}

@props([
    'breadcrumbs' => [],  
])

<div class="mx-auto max-w-5xl mt-10">
    <div class="">
        <x-ui.button
            size="xs"
            href="{{ $breadcrumbs['href'] }}"
        >   
            <x-heroicon-o-arrow-left class="w-3 h-3" />
            {{ $breadcrumbs['label'] }}
        </x-ui.button>
    </div>
</div>