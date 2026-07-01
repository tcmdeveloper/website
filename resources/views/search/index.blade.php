{{-- resources/views/search/index.blade.php --}}

<x-layouts.app 
    title="Search Results" 
    subtitle="List of articles for your search."
    :meta="[
        'title' => 'Search results for \'' . $query . '\'',
        'description' => 'Browse search results for \'' . $query . '\'on True Crime Metrix, including articles, case analysis and investigative commentary.'
    ]"
>

    <x-ui.container class="max-w-5xl">

        @if($query)

            <p class="text-zinc-700 mb-8 text-center">
                {{ $articles->total() }}
                {{ Str::plural('result', $articles->total()) }}
                for
                <span class="font-semibold">
                    "{{ $query }}"
                </span>
            </p>

        @endif

        @if($articles)

            <x-articles.grid :articles="$articles" />

        @else

            <div class="rounded-lg border border-zinc-700 p-8 text-center">

                <h2 class="text-2xl font-semibold mb-2">
                    No results found
                </h2>

                <p class="text-zinc-400">
                    Try different keywords or browse our latest articles.
                </p>

            </div>

        @endif

        <div class="mt-10">
            {{ $articles->links() }}
        </div>


    </x-ui.container>

</x-layouts.app>