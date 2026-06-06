{{-- resources/views/admin/articles/index.blade.php --}}

<x-layouts.app 
    title="Articles"
    subtitle="Here are the articles you've been working on."
>

    <x-ui.card class="mx-auto">

        <div class="flex justify-between items-center mb-9">

            <x-ui.button href="{{ route('dashboard') }}" variant="ghost" size="sm" class="gap-2!">
                <x-heroicon-o-arrow-left class="w-4 aspect-square" />
                Dashboard
            </x-ui.button>

            <x-ui.button href="{{ route('articles.create') }}" variant="primary" size="sm" class="gap-2!">
                <x-heroicon-o-plus class="w-4 aspect-square" />
                New Article
            </x-ui.button>

        </div>

    </x-ui.card>
    

</x-layouts.app>