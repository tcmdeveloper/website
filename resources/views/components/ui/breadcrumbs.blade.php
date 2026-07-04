{{-- resources/views/components/ui/breadcrumbs.blade.php --}}

@props([
    'breadcrumbs' => [],  
])

<div class="mx-auto w-full py-3">

    <a
        href="{{ $breadcrumbs['href'] }}"
        class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 mb-6"
    >
        <x-ui.icon name="arrow-left" class="w-4 h-4" />
        {{ $breadcrumbs['label'] }}
    </a>

</div>