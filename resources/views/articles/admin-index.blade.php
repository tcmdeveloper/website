{{-- resources/views/admin/articles/index.blade.php --}}

<x-layouts.app 
    title="Articles"
    subtitle="Here are the articles you've been working on."
>

<x-ui.card class="mx-auto max-w-5xl">

    <div class="flex justify-between items-center mb-9">
        <x-ui.button href="{{ route('dashboard.index') }}" variant="ghost" size="sm" class="gap-2!">
            <x-heroicon-o-arrow-left class="w-4 aspect-square" />
            Dashboard
        </x-ui.button>
        <x-ui.button href="{{ route('admin.articles.create') }}" variant="primary" size="sm" class="gap-2!">
            <x-heroicon-o-plus class="w-4 aspect-square" />
            New Article
        </x-ui.button>
    </div>

    @foreach($articles as $article)
        <div class="bg-white border border-zinc-300 rounded-lg p-4 mb-3 flex justify-between items-center">

            <div>
                <h2 class="font-semibold">{{ $article->title }}</h2>
                <p class="text-sm text-gray-500">Article ID: {{ $article->hex }}</p>
            </div>

            <div class="flex gap-2">
                <x-ui.button href="{{ route('admin.articles.show', $article) }}" variant="primary" size="xs" class="gap-2!">
                    <x-heroicon-o-magnifying-glass class="w-4 aspect-square" />
                    Inspect
                </x-ui.button>
                <x-ui.button href="{{ route('admin.articles.edit', $article) }}" variant="secondary" size="xs" class="gap-2!">
                    <x-heroicon-o-trash class="w-4 aspect-square" />
                    Edit
                </x-ui.button>

                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}">
                    @csrf
                    @method('DELETE')


                    <x-ui.button type="submit" variant="danger" size="xs" class="gap-2!" onclick="return confirm('Delete article?')">
                        <x-heroicon-o-trash class="w-4 aspect-square" />
                        Delete
                    </x-ui.button>
                </form>
            </div>

        </div>
    @endforeach

    <div class="mt-6">
        {{ $articles->links() }}
    </div>

</x-ui.card>

</x-layouts.app>