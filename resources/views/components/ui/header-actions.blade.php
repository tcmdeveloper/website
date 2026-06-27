{{-- resources/views/categories/admin-index.blade.php --}}

@props([
    'title' => null,
    'subtitle' => null,
    'href' => null,
    'label' => null,
    'buttonVariant' => 'primary'
])

<div class="flex items-center justify-between mb-6">

    <div>
        <h1 class="text-2xl font-semibold">
            {{$title}}
        </h1>

        <p class="text-sm text-zinc-500 mt-1">
            {{$subtitle}}
        </p>
    </div>

    <x-ui.button
        href="{{ $href }}"
        size="sm"
        variant="{{ $buttonVariant }}"
    >
        {{$label}}
    </x-ui.button>

</div>